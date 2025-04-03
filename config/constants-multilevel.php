<?php


// multi category level
define("FIRST_CATEGORY_LEVEL", 1);
define("SECOND_CATEGORY_LEVEL", 2);
define("THIRD_CATEGORY_LEVEL", 3);

// CATEGORY
define("CATEGORY_TEMPLATE_CHAR_LEVEL_2", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
define("CATEGORY_TEMPLATE_CHAR_LEVEL_3", " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");

// POST
define("LEVEL_FOR_POST", 3);
define("LEVEL_FOR_POST_CATEGORY", 2);


// MAX_LEVEL
define("MAX_LEVEL_FOR_POST_CATEGORY", 3);

// Control PostCategory Drop-Down Selection
/**
 * PostCategory: showable
 */
define("POST_CATEGORY_SHOWABLE", json_encode([
	FIRST_CATEGORY_LEVEL => [
		// 'controller' => 'actions',
		'PostCategoryController' => ['create', 'edit'],
		'PostController' => [ 'create', 'edit'],
	],
	SECOND_CATEGORY_LEVEL => [
		'PostCategoryController' => [ 'create', 'edit'],
		'PostController' => [ 'create', 'edit'],
	],
	THIRD_CATEGORY_LEVEL => [
		'PostCategoryController' => [],
		'PostController' => ['create', 'edit', 'store', 'update'],
	],	
]));

/**
 * PostCategory: selectable
 */
define("POST_CATEGORY_SELECTABLE", json_encode([
    FIRST_CATEGORY_LEVEL => [
		// 'controller' => 'actions',
		'PostCategoryController' => ['create', 'edit'],
		'PostController' => [ 'create', 'edit']
	],
	SECOND_CATEGORY_LEVEL => [
		'PostCategoryController' => [ 'create', 'edit'],
		'PostController' => [ 'create', 'edit']
	],
	THIRD_CATEGORY_LEVEL => [
		'PostCategoryController' => [],
		'PostController' => ['create', 'edit']
	],	
]));
