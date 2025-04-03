<?php

namespace Modules\Sys\app\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Common\app\Http\Controllers\BackendController;
use Modules\Sys\app\Models\User;
use Modules\Sys\app\Repositories\UserRepository;

class ProfileController extends BackendController
{
    public $redirectRoute;

    public function __construct(UserRepository $userRepo)
    {
        $this->langFile = 'sys::models/users';
        $this->repository = $userRepo;
        // image related
        $this->imageFilePath = storage_path(USER_FILE_PATH);
        $this->imageDimensions = json_decode(USER_FILE_DIMENSIONS, true);
        // define route for redirect
        $this->redirectRoute = route('sys.profile.index');
    }

    /**
     * Display a listing of the User.
     */
    public function index()
    {
        // get loggedin user
        $user = auth()->user();
        if (!$user) {
            Flash::error(__('sys::models/users.messages.profile_access_error'))->important();

            return redirect($this->redirectRoute);
        }

        return view('sys::profile.index')
            ->with('id', $user->id)
            ->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     */
    public function update(Request $request)
    {
        // get loggedin user
        $user = auth()->user();
        if (!$user) {
            Flash::error(__('sys::models/users.messages.profile_access_error'))->important();

            return redirect($this->redirectRoute);
        }

        // sanitize first
        $request = $this->__sanitize($request, $user->id);

        // preserve active field data
        $request->merge([
            'active' => $user->active,
        ]);
        $this->__validate($request, $user->id);

        $user = $this->repository->update($request->all(), $user->id);

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

        Flash::success(__('sys::models/users.messages.profile_update_success'))->important();

        return redirect($this->redirectRoute);
    }

    /**
     * Show the form for Change Password the specified User.
     *
     * @return Response
     */
    public function changePassword()
    {
        // get loggedin user
        $user = auth()->user();
        if (!$user) {
            Flash::error(__('sys::models/users.messages.profile_access_error'))->important();

            return redirect($this->redirectRoute);
        }

        return view('sys::profile.change_password')
            ->with('id', $user->id)
            ->with('user', $user);
    }

    /**
     * Update Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        // get loggedin user
        $user = auth()->user();
        if (!$user) {
            Flash::error(__('sys::models/users.messages.profile_access_error'))->important();

            return redirect($this->redirectRoute);
        }

        // validation
        $this->__validateChangePassword($request, $user);

        $user->update(
            [
                'password' => Hash::make($request->new_password),
            ]
        );

        Flash::success(__('sys::models/users.messages.change_password_success'))->important();

        return redirect($this->redirectRoute);
    }

    // validation
    private function __validate($request, $id = null)
    {
        $attributes = __('sys::models/users.fields');
        $rulesBasic = User::$rules;
        $rulesBasic['role'] = 'required|exists:roles,id';
        if (!empty($id)) {
            if ($id == auth()->user()->id) {
                unset($rulesBasic['role']);
            }
            $rulesBasic['email'] .= ',' . $id . ',id';
        }

        $rulesBasic = $rulesBasic;
        $this->validate($request, $rulesBasic, [], $attributes);
    }

    // validation Change Password
    private function __validateChangePassword($request, $user = null)
    {
        $attributes = __('sys::models/users.fields');
        $rulesBasic = [
            'new_password' => 'required|string|min:8|confirmed',
            'password' => [
                'required',
                'min:8',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        return $fail(__('common::messages.is_incorrect', ['field' => __('sys::models/users.fields.current_password')]));
                    }
                },
            ],
        ];
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
}
