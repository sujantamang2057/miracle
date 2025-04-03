<?php

namespace Modules\Sys\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Modules\Common\app\Http\Controllers\BackendController;
use Modules\Sys\app\DataTables\UserDataTable;
use Modules\Sys\app\DataTables\UserTrashDataTable;
use Modules\Sys\app\Models\Role;
use Modules\Sys\app\Models\User;
use Modules\Sys\app\Repositories\UserRepository;

class UserController extends BackendController
{
    public function __construct(UserRepository $userRepo)
    {
        $this->moduleName = 'sys.users';

        // Middleware mapping
        $middlewareMap = [
            // Permission Check - Any
            buildPermissionMiddleware($this->moduleName, ['index', 'create', 'edit', 'show', 'destroy', 'toggleActive', 'changePassword', 'trashList']) => ['index'],
            buildPermissionMiddleware($this->moduleName, ['create', 'store']) => ['create', 'store'],
            buildPermissionMiddleware($this->moduleName, ['edit', 'update']) => ['edit', 'update'],
            buildPermissionMiddleware($this->moduleName, ['changePassword', 'updatePassword']) => ['changePassword', 'updatePassword'],
            // Permission Check - Exact
            buildCanMiddleware($this->moduleName, ['show']) => ['show'],
            buildCanMiddleware($this->moduleName, ['destroy']) => ['destroy'],
            buildCanMiddleware($this->moduleName, ['toggleActive']) => ['toggleActive'],
            buildCanMiddleware($this->moduleName, ['removeImage']) => ['removeImage'],
            buildCanMiddleware($this->moduleName, ['trashList']) => ['trashList'],
            buildCanMiddleware($this->moduleName, ['restore']) => ['restore'],
        ];
        // Used middleware using the helper function
        applyMiddleware($this, $middlewareMap);

        $this->repository = $userRepo;
        $this->langFile = 'sys::models/users';
        // define route for redirect
        $this->listRoute = route('sys.users.index');
        $this->trashListRoute = route('sys.users.trashList');
        $this->detailRouteName = 'sys.users.show';
        // image path
        $this->imageFilePath = storage_path(USER_FILE_PATH);
        // image dimension
        $this->imageDimensions = json_decode(USER_FILE_DIMENSIONS, true);
    }

    /**
     * Display a listing of the User.
     */
    public function index(UserDataTable $userDataTable)
    {
        return $userDataTable->render('sys::users.index');
    }

    /**
     * Display a listing of the Trashed User.
     */
    public function trashList(UserTrashDataTable $userTrashDataTable)
    {
        return $userTrashDataTable->render('sys::users.trash');
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        $roleList = Role::getFilteredRoleList(getOldData('role', ''));

        return view('sys::users.create')
            ->with('id', null)
            ->with('roleList', $roleList)
            ->with('active', getOldData('active'));
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(Request $request)
    {
        // sanitize first
        $request = $this->__sanitize($request);
        // validation
        $this->__validate($request);

        // hash password
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = $this->repository->create($input);
        $user->roles()->sync([$request->role]);

        if ($request->has('profile_image')) {
            $file = $request->profile_image;
            if (!empty($file)) {
                $this->__manageImageFile($file, $user, 'profile_image');
            }
        }

        Flash::success(__('common::messages.saved', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.users.show', ['user' => $user->id]));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
        // fetch mode
        $mode = \Illuminate\Support\Facades\Request::get('mode', null);
        if ($mode == 'trash-restore') {
            if (!$user = User::withTrashed()->find($id)) {
                return redirect($this->listRoute);
            }
            if ($user->trashed()) {
                return view('sys::users.show-trash')
                    ->with('user', $user)
                    ->with('mode', $mode);
            } else {
                // redirect to normal show page
                return redirect(route('sys.users.show', ['user' => $user->id]));
            }
        } else {

            if (!$user = $this->findModel($id)) {
                return redirect($this->listRoute);
            }
        }
        if (!$this->__checkUserRole($user->role_name)) {
            abort(403);
        }

        return view('sys::users.show')->with('user', $user)->with('mode', $mode);
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $id = $id ?? auth()->user()->id;
        if (!$user = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        if (!$this->__checkUserRole($user->role_name)) {
            abort(403);
        }
        $roleList = Role::getFilteredRoleList(getOldData('role', $user->roles()->first()?->id));
        $activeText = ($user->active == 1 ? 'on' : 'off');

        return view('sys::users.edit')
            ->with('user', $user)
            ->with('roleList', $roleList)
            ->with('id', $user->id)
            ->with('active', getOldData('active', $user->active))
            ->with('activeText', $activeText);
    }

    /**
     * Update the specified User in storage.
     */
    public function update($id, Request $request)
    {
        if (!$user = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        if (!$this->__checkUserRole($user->role_name)) {
            abort(403);
        }
        // sanitize first
        $request = $this->__sanitize($request, $user->id);
        $this->__validate($request, $user->id);

        $user = $this->repository->update($request->all(), $id);
        if (!empty($request->role)) {
            $user->roles()->sync([$request->role]);
        }

        // manage images
        if ($request->has('profile_image')) {
            $file = $request->profile_image;
            $profileImagePre = $request->profile_image_pre;
            if (!empty($file) && $profileImagePre != $file) {
                $this->__manageImageFile($file, $user, 'profile_image');
                // delete old image
                $this->__deleteImageFile($profileImagePre);
            }
        }

        Flash::success(__('common::messages.updated', ['model' => __($this->langFile . '.singular')]))->important();

        return redirect(route('sys.users.show', $id));
    }

    public function imageEdit($id, $field)
    {
        if (!$user = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        if (!$this->__checkUserRole($user->role_name)) {
            abort(403);
        }

        return view('sys::users.image_edit')
            ->with('user', $user)
            ->with('field', $field)
            ->with('id', $user->id);
    }

    public function imageUpdate($id, $field, Request $request)
    {
        if (!$user = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        if (!$this->__checkUserRole($user->role_name)) {
            abort(403);
        }
        $this->__validateProfileImage($request, $field);
        $user = $this->repository->update($request->all(), $id);

        // manage images
        if ($request->has($field)) {
            $file = $request->$field;
            $fieldPreName = $field . '_pre';
            $fieldPre = $request->$fieldPreName;
            if (!empty($file) && $fieldPre != $file) {
                $this->__manageImageFile($file, $user, $field);
                // delete old image
                $this->__deleteImageFile($fieldPre);
            }
        }

        Flash::success(__('common::messages.updated_image', ['image' => __($this->langFile . '.fields.profile_image')]))->important();

        return redirect(route('sys.users.index'));
    }

    /**
     * Show the form for Change Password the specified User.
     *
     * @param  int  $id
     * @return Response
     */
    public function changePassword($id)
    {
        if (($id == 1 && $id != auth()->id()) || !$user = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        if (!$this->__checkUserRole($user->role_name)) {
            abort(403);
        }

        return view('sys::users.change_password')->with('user', $user);
    }

    /**
     * Update Password.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword($id, Request $request)
    {
        if (($id == 1 && $id != auth()->id()) || !$user = $this->findModel($id)) {
            return redirect($this->listRoute);
        }
        if (!$this->__checkUserRole($user->role_name)) {
            abort(403);
        }

        // validation
        $this->__validateChangePassword($request, $user);

        $user->update([
            'password' => Hash::make($request->new_password)]
        );

        Flash::success(__($this->langFile . '.messages.change_password_success'))->important();

        return redirect(route('sys.users.show', [$user->id]));
    }

    // toggle active
    public function toggleActive(Request $request)
    {
        $selection = $request->id;
        if (!empty($selection)) {
            if (is_array($selection)) {
                foreach ($selection as $id) {
                    $this->__setActive($id);
                }

                return response()->json([
                    'msgType' => 'success',
                    'msg' => __('common::messages.active_toggle', ['model' => __($this->langFile . '.plural')]),
                ]);
            } else {
                if ($this->__setActive($selection)) {
                    return response()->json([
                        'msgType' => 'success',
                        'msg' => __('common::messages.active_toggle', ['model' => __($this->langFile . '.singular')]),
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Toggle active failed']);
    }

    private function __setActive($id)
    {
        $model = $this->repository->find($id);
        if (!empty($model)) {
            $active = ($model->active == 2) ? 1 : 2;
            $model->update([
                'active' => $active,
            ]);

            return true;
        }

        return false;
    }

    // validation
    private function __validate($request, $id = null)
    {
        $attributes = __($this->langFile . '.fields');
        $messages = __('common::validation');
        $rulesBasic = User::$rules;
        $rulesBasic['role'] = 'required|exists:roles,id';
        if (!empty($id)) {
            if ($id == auth()->user()->id) {
                unset($rulesBasic['role']);
            }
            $rulesBasic['email'] .= ',' . $id . ',id';
        }

        if ($request->isMethod('post') && empty($id)) {
            $rulesEx = [
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
            ];
        } else {
            $rulesEx = [];
        }
        $rulesBasic = $rulesBasic + $rulesEx;
        $this->validate($request, $rulesBasic, $messages, $attributes);
    }

    // validation
    private function __validateProfileImage($request, $field)
    {
        $attributes = __('sys::models/users.fields');
        $rules = [$field => 'nullable|string|max:100'];
        $this->validate($request, $rules, [], $attributes);
    }

    // validation Change Password
    private function __validateChangePassword($request, $user = null)
    {
        $attributes = __($this->langFile . '.fields');
        $attributes['new_password_confirmation'] = __($this->langFile . '.fields.password_confirm');
        $rulesBasic = [
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ];
        if ($user && auth()->id() == $user->id) {
            $rulesEx = [
                'password' => [
                    'required', 'min:8',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            return $fail(__('common::messages.is_incorrect', ['field' => __($this->langFile . '.fields.current_password')]));
                        }
                    },
                ],
            ];
        } else {
            $rulesEx = [];
        }
        $rulesBasic = $rulesBasic + $rulesEx;
        $this->validate($request, $rulesBasic, [], $attributes);
    }

    // sanitize inputs
    private function __sanitize($request)
    {
        $active = ($request->get('active') == 'on') ? 1 : 2;
        $request->merge([
            'active' => $active,
        ]);

        return $request;
    }

    private function __checkUserRole($role = null)
    {
        $roles = Role::getFilteredRoleList('', false);
        $rolesArr = Arr::pluck($roles, 'text');

        return isLoggedInUserMasterRole() || in_array($role, $rolesArr);
    }
}
