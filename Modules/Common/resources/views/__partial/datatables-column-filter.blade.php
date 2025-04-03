@push('page_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        // Convert roleList to JavaScript
        var roleList = @json(getRoleListData());
        var menuParentList = @json(getMenuParentListData());
        var newsCategoryList = @json(getNewsCategoryListData());
        var postCategoryList = @json(getPostCategoryListData());
        var faqCategoryList = @json(getFaqCategoryListData());
        var blogCategoryList = @json(getBlogCategoryListData());

        function setColumnFilter(table) {
            table.columns('.filter_text').every(function() {
                var filterElement =
                    '<input type="text" class="form-control form-control-sm" placeholder="" aria-controls="dataTableBuilder" />';
                initializeFilterInput(table, this, filterElement);
            });
            table.columns('.filter_active').every(function() {
                var filterElement = create2ArrDropDownList(
                    "{{ __('common::general.active') }}",
                    "{{ __('common::general.inactive') }}"
                );
                initializeFilterInput(table, this, filterElement);
            });
            table.columns('.filter_reserved').every(function() {
                var filterElement = create2ArrDropDownList(
                    "{{ __('common::general.reserved') }}",
                    "{{ __('common::general.nonreserved') }}"
                );
                initializeFilterInput(table, this, filterElement);
            });
            table.columns('.filter_publish').every(function() {
                var filterElement = create2ArrDropDownList(
                    "{{ __('common::general.published') }}",
                    "{{ __('common::general.unpublished') }}"
                );
                initializeFilterInput(table, this, filterElement);
            });
            table.columns('.filter_page_type').every(function() {
                var filterElement = create2ArrDropDownList(
                    "{{ __('cmsadmin::models/pages.type_static') }}",
                    "{{ __('cmsadmin::models/pages.type_dynamic') }}"
                );
                initializeFilterInput(table, this, filterElement);
            });
            table.columns('.filter_roles').every(function() {
                var roleJson = roleList;
                var jsonList = {
                    "Data": roleJson
                };
                var filterElement = createDropDownList(jsonList)
                initializeFilterInput(table, this, filterElement);
            });
            table.columns('.filter_news').every(function() {
                var categoryJson = newsCategoryList;
                var jsonList = {
                    "Data": categoryJson
                };
                var filterElement = createDropDownList(jsonList)
                initializeFilterInput(table, this, filterElement);
            });

            table.columns('.filter_post').every(function() {
                var categoryJson = postCategoryList;
                var jsonList = {
                    "Data": categoryJson
                };
                var filterElement = createDropDownList(jsonList)
                initializeFilterInput(table, this, filterElement);
            });

            table.columns('.filter_faqs').every(function() {
                var categoryJson = faqCategoryList;
                var jsonList = {
                    "Data": categoryJson
                };
                var filterElement = createDropDownList(jsonList)
                initializeFilterInput(table, this, filterElement);
            });
            table.columns('.filter_blog').every(function() {
                var categoryJson = blogCategoryList;
                var jsonList = {
                    "Data": categoryJson
                };
                var filterElement = createDropDownList(jsonList)
                initializeFilterInput(table, this, filterElement);
            });
            table.columns('.filter_menu').every(function() {
                var categoryJson = menuParentList;
                var jsonList = {
                    "Data": categoryJson
                };
                var filterElement = createDropDownList(jsonList)
                initializeFilterInput(table, this, filterElement);
            });
        }

        function initializeFilterInput(table, column, filterElement) {
            var header = $(column.header()).html();
            var input = $(filterElement)
                .appendTo($(column.header())
                    .empty()
                    .append('<div>' + header + '</div>'));
            LaravelDataTables.dataTableBuilder.on('buttons-action', function(e, buttonApi, dataTable, node, config) {
                if (node.hasClass('buttons-reset')) {
                    input.val('');
                    column.search('').draw();
                }
            });
            // Restoring state
            input.val(column.search());
            if ($(input).is('select')) {
                // Dropdown-specific behavior
                input.on('change clear', function() {
                    setTimeout(() => {
                        const selectedValue = this.value;
                        const columnIndex = $(this).parent().index();

                        if (selectedValue !== undefined && selectedValue !== null && selectedValue !==
                            '') {
                            // Filter for roles
                            if ($(this).closest('.filter_roles').length > 0) {
                                table.column(columnIndex + ':visible')
                                    .search(`${selectedValue}`, true, false)
                                    .draw();
                            } else {
                                table.column(columnIndex + ':visible')
                                    .search(`^${selectedValue}$`, true, false)
                                    .draw();
                            }
                        } else {
                            // Clear filter
                            table.column(columnIndex + ':visible')
                                .search('')
                                .draw();
                        }
                    }, 1);
                });
            } else {
                // Input-specific behavior
                input.on('keyup', function(e) {
                    var ignore = [9, 13, 16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 91, 92, 112,
                        113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 144, 145
                    ];
                    if (ignore.indexOf(e.which) != -1) return;

                    var searchValue = this.value ? this.value : '';
                    table.column($(this).parent().index() + ':visible')
                        .search(searchValue)
                        .draw();
                });

                input.on('keypress', function(e) {
                    if (e.which == 13) {
                        e.preventDefault();
                        return false;
                    }
                });
            }

            // Prevent click from sorting column
            input.on('click', function(e) {
                e.stopPropagation();
            });

            input.parent().attr('tabindex', -1);
        }

        function create2ArrDropDownList($label1, $label2) {
            var jsonList = {
                "Data": [{
                        "index": "1",
                        "label": $label1,
                    },
                    {
                        "index": "2",
                        "label": $label2,
                    },
                ]
            };
            return createDropDownList(jsonList);
        }

        function createDropDownList(jsonList) {
            var listItems = "<select class='form-control form-control-sm'>";
            listItems += "<option value></option>";
            for (var i = 0; i < jsonList.Data.length; i++) {
                listItems += "<option value='" + jsonList.Data[i].index + "'>" + jsonList.Data[i].label + "</option>";
            }
            listItems += "</select>";
            return listItems;
        }
    </script>
@endpush
