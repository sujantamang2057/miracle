<div class="row">
    <div class="col-sm-4">
        <!-- Name Field -->
        <div class="form-group required {{ validationClass($errors->has('name')) }}">
            {!! Form::label('name', __('sys::models/roles.fields.name') . ':') !!}
            {!! Form::text('name', null, [
                'class' => 'form-control',
                'required',
                'maxlength' => 100,
                'disabled' => getActionName() == 'edit',
            ]) !!}
            {{ validationMessage($errors->first('name')) }}
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-sm-4 card">
        <div class="card-header h5">{{ __('sys::models/roles.text.allowed_permissions') }}</div>
        <div class="card-body">
            <div class="d-flex mb-3">
                <input type="text" id="allowed-search" class="form-control" placeholder="{{ __('sys::models/roles.text.search') }}" />
            </div>
            <div id="allowed-perms">
                <div id="allowed"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="d-flex flex-column w-25 mx-auto mt-5">
            <button id="moveAllToRestricted" type="button" class="btn btn-default my-2"
                title="{{ __('sys::models/permissions.text.move_all_to_restricted') }}">
                <i class="fas fa-angle-double-right"></i>
            </button>
            <button id="moveToRestricted" type="button" class="btn btn-default my-2"
                title="{{ __('sys::models/permissions.text.move_selected_to_restricted') }}">
                <i class="fas fa-angle-right"></i>
            </button>
            <button id="moveToAllowed" type="button" class="btn btn-default my-2"
                title="{{ __('sys::models/permissions.text.move_selected_to_allowed') }}">
                <i class="fas fa-angle-left"></i>
            </button>
            <button id="moveAllToAllowed" type="button" class="btn btn-default my-2"
                title="{{ __('sys::models/permissions.text.move_all_to_allowed') }}">
                <i class="fas fa-angle-double-left"></i>
            </button>
        </div>
    </div>
    <div class="col-sm-4 card">
        <div class="card-header h5">{{ __('sys::models/roles.text.restricted_permissions') }}</div>
        <div class="card-body">
            <div class="d-flex mb-3">
                <input type="text" id="restricted-search" class="form-control" placeholder="{{ __('sys::models/roles.text.search') }}" />
            </div>
            <div id="restricted-perms">
                <div id="restricted"></div>
            </div>
        </div>
    </div>
</div>
@php
    function convertArray($inputArray)
    {
        $outputArray = [];
        $parentMap = [];

        foreach ($inputArray as $item) {
            if (strpos($item['text'], '.*') !== false) {
                // Map parent item
                $parentMap[$item['text']] = $item['id'];
                $outputArray[] = [
                    'id' => $item['id'],
                    'text' => $item['text'],
                    'parent' => '#',
                ];
            } else {
                // Find the corresponding parent based on the prefix
                $prefix = substr($item['text'], 0, strrpos($item['text'], '.')) . '.*';
                $outputArray[] = [
                    'id' => $item['id'],
                    'text' => $item['text'],
                    'parent' => $parentMap[$prefix] ?? '#',
                ];
            }
        }

        return $outputArray;
    }
    $allowedPermissions = collect(
        Arr::where($permissions, function ($value, $key) {
            return Arr::has($value, 'selected');
        }),
    )
        ->values()
        ->all();
    $allowedPermissions = convertArray($allowedPermissions);

    $restrictedPermissions = collect(
        Arr::where($permissions, function ($value, $key) {
            return !Arr::has($value, 'selected');
        }),
    )
        ->values()
        ->all();
    $restrictedPermissions = convertArray($restrictedPermissions);
@endphp
@push('third_party_stylesheets')
    <link rel="stylesheet" href="{{ asset('theme/common/thirdParty/jstree/jstree.min.css') }}">
@endpush
@push('third_party_scripts')
    <script src="{{ asset('theme/common/thirdParty/jstree/jstree.min.js') }}"></script>
@endpush
@push('page_scripts')
    <style>
        #allowed-perms,
        #restricted-perms {
            height: 400px;
            overflow: hidden;
        }
    </style>
@endpush
@push('page_scripts')
    <script>
        $('body').on('submit', '#role-form', function(e) {
            e.preventDefault();
            var form = $(this).clone().removeAttr('id').addClass('d-none');
            // Get the data from allowed
            var allowedData = $('#allowed').jstree(true).get_json('#', {
                flat: false
            });
            allowedData.forEach(function(currentItem, index, arr) {
                form.append('<input name="permissions[]" value="' + currentItem.id + '">');
                if (currentItem.children && currentItem.children.length) {
                    currentItem.children.forEach(function(childItem, childIndex, childs) {
                        form.append('<input name="permissions[]" value="' + childItem.id + '">');
                    });
                }
            });
            $('body').append(form);
            form.unbind("submit").submit();
        });

        $('#allowed-search, #restricted-search').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });

        var permissions = {!! json_encode($permissions) !!};
        var allowedPermissions = {!! json_encode($allowedPermissions) !!};
        var restrictedPermissions = {!! json_encode($restrictedPermissions) !!};
        $(function() {
            // Initialize the jsTree
            $('#allowed').jstree({
                'core': {
                    data: allowedPermissions,
                    'check_callback': true,
                    'themes': {
                        'name': 'default',
                        'dots': true,
                        'icons': false
                    }
                },
                'checkbox': {
                    'keep_selected_style': false
                },
                'search': {
                    'show_only_matches': true
                },
                'plugins': ["wholerow", "checkbox", "search"] // Optional plugins
            });

            // Handling events
            // Move selected nodes from restricted to allowed
            $('#moveToRestricted').click(function() {
                var selectedNodes = $('#allowed').jstree("get_selected", true);
                var nodesToMove = new Set();
                var nodesToRecreateInAllowed = new Set();

                // Function to recursively add parent nodes to the set if any child is selected
                function addParentsToSet(node) {
                    if (node && node.parent && node.parent != '#') {
                        var parentNode = $('#allowed').jstree('get_node', node.parent);
                        if (parentNode) {
                            nodesToMove.add(parentNode);
                            addParentsToSet(parentNode);
                        }
                    }
                }

                // Add selected nodes and their parents to the set
                selectedNodes.forEach(function(node) {
                    addParentsToSet(node);
                });

                // Move nodes from the set to 'restricted' tree
                nodesToMove.forEach(function(node) {
                    if ($('#allowed').jstree().get_node(node.id)) {
                        var nodePath = getNodePath(node, 'allowed');
                        moveNodeWithoutUnselectedChildren(node, nodePath, $('#restricted')
                            .jstree());

                        // Check for unselected children and prepare to recreate them in 'allowed'
                        node.children.forEach(function(childId) {
                            var childNode = $('#allowed').jstree('get_node', childId);
                            if (childNode && !$('#allowed').jstree('is_selected',
                                    childNode)) {
                                nodesToRecreateInAllowed.add(
                                    childNode); // Siblings that are not selected
                            }
                        });

                        // Remove the parent node from the original tree
                        $('#allowed').jstree().delete_node(node);
                    }
                });

                // Recreate the siblings (unselected children) in the 'allowed' tree without the parent
                nodesToRecreateInAllowed.forEach(function(childNode) {
                    recreateNodeAsRootInAllowed(childNode);
                });

                // Ensure all selected nodes are moved by handling the remaining selected nodes
                selectedNodes.forEach(function(node) {
                    if ($('#allowed').jstree().get_node(node.id)) {
                        $('#allowed').jstree().delete_node(
                            node); // Ensure the node is removed after moving
                    }
                });
            });

            $('#moveAllToRestricted').click(function() {
                moveAllData('allowed', 'restricted');
            });

            // Search input handler
            $('#allowed-search').keyup(function() {
                var searchString = $(this).val();
                $('#allowed').jstree('search', searchString);
            });

            // Initialize the jsTree
            $('#restricted').jstree({
                'core': {
                    data: restrictedPermissions,
                    'check_callback': true,
                    'themes': {
                        'name': 'default',
                        'dots': true,
                        'icons': false
                    }
                },
                'checkbox': {
                    'keep_selected_style': false
                },
                'search': {
                    'show_only_matches': true
                },
                'plugins': ["wholerow", "checkbox", "search"] // Optional plugins
            });

            // Handling events
            // Move selected nodes from restricted to allowed
            $('#moveToAllowed').click(function() {
                var selectedNodes = $('#restricted').jstree("get_selected", true);
                selectedNodes.forEach(function(node) {
                    // Find the node's path in the tree
                    var nodePath = getNodePath(node, 'restricted');

                    // Recursively move the node and its children
                    moveNodeWithHierarchy(node, nodePath, $('#allowed').jstree());

                    // Remove the node from the original tree
                    $('#restricted').jstree().delete_node(node);
                });
            });

            $('#moveAllToAllowed').click(function() {
                moveAllData('restricted', 'allowed');
            });

            // Search input handler
            $('#restricted-search').keyup(function() {
                var searchString = $(this).val();
                $('#restricted').jstree('search', searchString);
            });

            function moveAllData(fromTreeId, toTreeId) {
                var fromTree = $('#' + fromTreeId).jstree(true);
                var toTree = $('#' + toTreeId).jstree(true);

                // Get all nodes from fromTree as JSON
                var fromTreeData = fromTree.get_json('#', {
                    flat: false
                });

                // Create nodes in toTree
                fromTreeData.forEach(function(node) {
                    toTree.create_node('#', node); // Add node to Tree 1
                });

                // Clear all nodes from fromTree
                fromTree.delete_node(fromTree.get_node('#').children);
            }

            function moveNodeWithHierarchy(node, nodePath, targetTree) {
                var parentPath = nodePath.slice(0, -1).join('/');
                var parentNode = targetTree.get_node(parentPath);
                var startNode = parentNode ? parentNode : '#';

                targetTree.create_node(startNode, {
                    id: node.id,
                    text: node.text,
                    children: []
                });
            }

            // Function to move the node to the target tree without unselected children
            function moveNodeWithoutUnselectedChildren(node, nodePath, targetTree) {
                // Create the node in the target tree
                targetTree.create_node(node.parent === '#' ? '#' : node.parent, {
                    id: node.id,
                    text: node.text,
                    children: []
                });

                // Recursively create selected children in the target tree
                node.children.forEach(function(childId) {
                    var childNode = $('#allowed').jstree('get_node', childId);

                    // Check if the child is selected, move only if selected
                    if (childNode && $('#allowed').jstree('is_selected', childNode)) {
                        var childPath = getNodePath(childNode, 'allowed');
                        moveNodeWithoutUnselectedChildren(childNode, childPath, targetTree);
                    }
                });
            }

            // Function to recreate a node as a root in the 'allowed' tree if it was a sibling of a moved node
            function recreateNodeAsRootInAllowed(childNode) {
                $('#allowed').jstree().create_node('#', { // Create as root in the 'allowed' tree
                    id: childNode.id,
                    text: childNode.text,
                    children: []
                });

                // Recursively handle any children of the child node (if any)
                childNode.children.forEach(function(grandChildId) {
                    var grandChildNode = $('#allowed').jstree('get_node', grandChildId);
                    if (grandChildNode) {
                        recreateNodeAsRootInAllowed(grandChildNode);
                    }
                });
            }

            function getNodePath(node, nodeId) {
                var path = [];
                while (node) {
                    path.unshift(node.id);
                    node = $('#' + nodeId).jstree('get_node', node.parent);
                }
                return path;
            }

            $('#allowed-perms, #restricted-perms').overlayScrollbars({
                overflowBehavior: {
                    y: "scroll"
                },
            });
        });
    </script>
@endpush
