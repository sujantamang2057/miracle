// variable
var addButtons = [
    {
        name: 'customAccordion',
        type: 'menu',
    },
    {
        name: 'customButton',
        type: 'menu',
    },
    {
        name: 'customDecoration',
        type: 'split',
    },
    {
        name: 'customHeading',
        type: 'split',
    },
    {
        name: 'customList',
        type: 'menu',
    },
    {
        name: 'customTable',
        type: 'menu',
    },
    {
        name: 'customUnderConstruction',
        type: 'menu',
    },
];

// functions
var customAccordion = function (editor) {
    return {
        text: trans('tinyMce.custom_accordion'),
        fetch: function (callback) {
            var items = [
                {
                    type: 'menuitem',
                    text: trans('tinyMce.accordion_box'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<div class="accordion-box ks-ac_box"><p class="ac_button">' +
                                trans('tinyMce.accordion_button') +
                                '</p><div class="ac_content" style="display: none;">' +
                                trans('tinyMce.change_data') +
                                '</div></div>&nbsp;'
                        );
                    },
                },
            ];
            callback(items);
        },
    };
};

// Custom-Decoration
var decorationBrokenLineThin = function (editor, data) {
    var content = editor.selection.getContent();
    var showContent = content ? content : '';
    var $html = '<hr class="decoration-broken-line" />';
    $html += content;
    return $html;
};
var decorationDashedThick = function (editor, data) {
    var content = editor.selection.getContent();
    var showContent = content ? content : '';
    var $html = '<hr class="decoration-dashed />';
    $html += content;
    return $html;
};

var customDecoration = function (editor) {
    return {
        text: trans('tinyMce.decoration'),
        onAction: function () {},
        onItemAction: function (buttonApi, value) {
            editor.insertContent(value);
        },
        fetch: function (callback) {
            var items = [
                {
                    type: 'choiceitem',
                    text: trans('tinyMce.decoration_broken_line_thin'),
                    value: decorationBrokenLineThin(editor),
                },
                {
                    type: 'choiceitem',
                    text: trans('tinyMce.decoration_dashed_thick'),
                    value: decorationDashedThick(editor),
                },
            ];
            callback(items);
        },
    };
};

// Custom-Heading
var headingOne = function (editor) {
    var content = editor.selection.getContent();
    var showContent = content ? content : trans('tinyMce.heading_one');
    var $html = '<h1 class="first_heading">';
    $html += showContent;
    $html += '</h1></br>';
    return $html;
};
var headingTwo = function (editor) {
    var content = editor.selection.getContent();
    var showContent = content ? content : trans('tinyMce.heading_two');
    var $html = '<h2 class="second_heading">';
    $html += showContent;
    $html += '</h2></br>';
    return $html;
};
var headingThree = function (editor) {
    var content = editor.selection.getContent();
    var showContent = content ? content : trans('tinyMce.heading_three');
    var $html = '<h3 class="=third_heading">';
    $html += showContent;
    $html += '</h3></br>';
    return $html;
};
var headingFour = function (editor) {
    var content = editor.selection.getContent();
    var showContent = content ? content : trans('tinyMce.heading_four');
    var $html = '<h4 class="fourth_heading">';
    $html += showContent;
    $html += '</h4></br>';
    return $html;
};
var headingFive = function (editor) {
    var content = editor.selection.getContent();
    var showContent = content ? content : trans('tinyMce.heading_five');
    var $html = '<h5 class="fifth_heading">';
    $html += showContent;
    $html += '</h5></br>';
    return $html;
};

var customHeading = function (editor) {
    return {
        text: trans('tinyMce.heading'),
        onAction: function () {},
        onItemAction: function (buttonApi, value) {
            editor.insertContent(value);
        },
        fetch: function (callback) {
            var items = [
                {
                    type: 'choiceitem',
                    text: trans('tinyMce.heading_one'),
                    value: headingOne(editor),
                },
                {
                    type: 'choiceitem',
                    text: trans('tinyMce.heading_two'),
                    value: headingTwo(editor),
                },
                {
                    type: 'choiceitem',
                    text: trans('tinyMce.heading_three'),
                    value: headingThree(editor),
                },
                {
                    type: 'choiceitem',
                    text: trans('tinyMce.heading_four'),
                    value: headingFour(editor),
                },
                {
                    type: 'choiceitem',
                    text: trans('tinyMce.heading_five'),
                    value: headingFive(editor),
                },
            ];
            callback(items);
        },
    };
};

var customButton = function (editor) {
    return {
        text: trans('tinyMce.custom_button'),
        fetch: function (callback) {
            var items = [
                {
                    type: 'menuitem',
                    text: trans('tinyMce.button_large'),
                    onAction: function () {
                        var content = editor.selection.getContent();
                        var showContent = content
                            ? content
                            : trans('tinyMce.button_large_text');
                        editor.insertContent(
                            '&nbsp;<a class="button-large" href="#">' +
                                showContent +
                                '</a>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.button_small'),
                    onAction: function () {
                        var content = editor.selection.getContent();
                        var showContent = content
                            ? content
                            : trans('tinyMce.button_small_text');
                        editor.insertContent(
                            '&nbsp;<a class="button-small" href="#">' +
                                showContent +
                                '</a>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.button_multiple_side'),
                    onAction: function () {
                        var content = editor.selection.getContent();
                        var showContent = content
                            ? content
                            : trans('tinyMce.custom_button_text');
                        editor.insertContent(
                            '&nbsp;<ul class="button-multiple-side"><li><a href="#">' +
                                showContent +
                                '</li></a></ul>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.button_two_row'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ul class="button-two-rows"><li><a href="#">' +
                                trans('tinyMce.custom_button_1_text') +
                                '</li></a><li><a href="#">' +
                                trans('tinyMce.custom_button_2_text') +
                                '</li></a></ul>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.button_three_row'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ul class="button-three-rows"><li><a href="#">' +
                                trans('tinyMce.custom_button_1_text') +
                                '</li></a><li><a href="#">' +
                                trans('tinyMce.custom_button_2_text') +
                                '</li></a><li><a>' +
                                trans('tinyMce.custom_button_3_text') +
                                '</li></a></ul>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.button_four_row'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ul class="button-four-rows"><li><a href="#">' +
                                trans('tinyMce.custom_button_1_text') +
                                '</li></a><li><a href="#">' +
                                trans('tinyMce.custom_button_2_text') +
                                '</li></a><li><a>' +
                                trans('tinyMce.custom_button_3_text') +
                                '</li></a><li><a>' +
                                trans('tinyMce.custom_button_4_text') +
                                '</li></a></ul>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.button_two_horz'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ul class="btn-two-hor"><li><a href="#">' +
                                trans('tinyMce.horizontal_button_1_text') +
                                '</li></a><li><a href="#">' +
                                trans('tinyMce.horizontal_button_2_text') +
                                '</li></a></ul>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.button_two_horz_list'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ul class="btn-two-horz-list"><li><a href="#">' +
                                trans('tinyMce.horizontal_button_list_1_text') +
                                '</li></a><li><a href="#">' +
                                trans('tinyMce.horizontal_button_list_2_text') +
                                ' </li></a></ul>&nbsp;'
                        );
                    },
                },
            ];
            callback(items);
        },
    };
};

var customList = function (editor) {
    return {
        text: trans('tinyMce.custom_list'),
        fetch: function (callback) {
            var items = [
                {
                    type: 'menuitem',
                    text: trans('tinyMce.unorder_list_normal'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ul class="unorder-list-normal"><li>' +
                                trans('tinyMce.unorder_list_normal') +
                                '</li><li>' +
                                trans('tinyMce.unorder_list_normal') +
                                '</li><li>' +
                                trans('tinyMce.unorder_list_normal') +
                                '</li></ul>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.unorder_list_side'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ul class="unorder-list-side"><li>' +
                                trans('tinyMce.unorder_list_side') +
                                '</li><li>' +
                                trans('tinyMce.unorder_list_side') +
                                '</li><li>' +
                                trans('tinyMce.unorder_list_side') +
                                '</li></ul>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.order_list_normal'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ol class="order-list"><li>' +
                                trans('tinyMce.order_list_normal') +
                                '</li><li>' +
                                trans('tinyMce.order_list_normal') +
                                '</li><li>' +
                                trans('tinyMce.order_list_normal') +
                                '</li></ol>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.order_list_side'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ol class="order-list-side"><li>' +
                                trans('tinyMce.order_list_side') +
                                '</li><li>' +
                                trans('tinyMce.order_list_side') +
                                '</li><li>' +
                                trans('tinyMce.order_list_side') +
                                '</li></ol>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.order_list_circle'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ol class="order-list-circle"><li>' +
                                trans('tinyMce.order_list_circle') +
                                '</li><li>' +
                                trans('tinyMce.order_list_circle') +
                                '</li><li>' +
                                trans('tinyMce.order_list_circle') +
                                '</li></ol>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.order_list_roman'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ol class="order-list-roman"><li>' +
                                trans('tinyMce.order_list_roman') +
                                '</li><li>' +
                                trans('tinyMce.order_list_roman') +
                                '</li><li>' +
                                trans('tinyMce.order_list_roman') +
                                '</li></ol>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.order_list_single_bracket'),
                    onAction: function () {
                        editor.insertContent(
                            '&nbsp;<ol class="order-list-single-brac"><li>' +
                                trans('tinyMce.order_list_single_bracket') +
                                '</li><li>' +
                                trans('tinyMce.order_list_single_bracket') +
                                '</li><li>' +
                                trans('tinyMce.order_list_single_bracket') +
                                '</li></ol>&nbsp;'
                        );
                    },
                },
            ];
            callback(items);
        },
    };
};

var customTable = function (editor) {
    return {
        text: trans('tinyMce.tbl'),
        fetch: function (callback) {
            var items = [
                {
                    type: 'menuitem',
                    text: trans('tinyMce.tbl_normal'),
                    onAction: function () {
                        var content = editor.selection.getContent();
                        var showContent = content ? content : '';
                        editor.insertContent(
                            '&nbsp;<table class="table-normal"><tbody><tr><th>' +
                                trans('tinyMce.tbl_heading_text') +
                                '</th><th>' +
                                trans('tinyMce.tbl_heading_text') +
                                '</th><th>' +
                                trans('tinyMce.tbl_heading_text') +
                                '</th></tr><tr><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td></tr></tbody></table>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.tbl_small_screen_scroll'),
                    onAction: function () {
                        var content = editor.selection.getContent();
                        var showContent = content ? content : '';
                        editor.insertContent(
                            '&nbsp;<div class="table_scroll"><table class="table-normal"><tbody><tr><th>' +
                                trans('tinyMce.tbl_heading_text') +
                                '</th><th>' +
                                trans('tinyMce.tbl_heading_text') +
                                '</th><th>' +
                                trans('tinyMce.tbl_heading_text') +
                                '</th></tr><tr><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td></tr><tr><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td></tr></tbody></table></div>&nbsp;'
                        );
                    },
                },
                {
                    type: 'menuitem',
                    text: trans('tinyMce.tbl_vertical'),
                    onAction: function () {
                        var content = editor.selection.getContent();
                        var showContent = content ? content : '';
                        editor.insertContent(
                            '&nbsp;<table class="table-normal"><tbody><tr><th>' +
                                trans('tinyMce.tbl_heading_text') +
                                '</th><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td></tr><tr><th>' +
                                trans('tinyMce.tbl_heading_text') +
                                '</th><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td></tr><tr><th>' +
                                trans('tinyMce.tbl_heading_text') +
                                '</th><td>' +
                                trans('tinyMce.tbl_data_text') +
                                '</td></tr></tbody></table>&nbsp;'
                        );
                    },
                },
            ];
            callback(items);
        },
    };
};

var customUnderConstruction = function (editor) {
    return {
        text: trans('tinyMce.under_construction'),
        fetch: function (callback) {
            var items = [
                {
                    type: 'menuitem',
                    text: trans('tinyMce.design_1'),
                    onAction: function () {
                        var content = editor.selection.getContent();
                        var showContent = content ? content : '';
                        editor.insertContent(
                            '&nbsp;<p class="under-construction">' +
                                trans('tinyMce.under_construction') +
                                '</p>&nbsp;'
                        );
                    },
                },
            ];
            callback(items);
        },
    };
};

// export {
//     addButtons,
//     customAccordion,
//     customButton,
//     customDecoration,
//     customHeading,
//     customList,
//     customTable,
//     customUnderConstruction,
// };
