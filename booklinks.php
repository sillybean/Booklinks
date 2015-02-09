<?php
/*
Plugin Name: Bookstore Links
Plugin URI: http://stephanieleary.com
Description: Creates a books section for your site with links to bookstores. 
Version: 1.0
Author: Stephanie Leary
Author URI: http://stephanieleary.com
License: GPL2
*/


// create the post type every time WordPress runs
add_action( 'init', 'register_book_init' );

function register_book_init() {
register_post_type('book',
array(	
	'label' => 'Books',
	'public' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'show_in_nav_menus' => true,
	'has_archive' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'rewrite' => array('slug' => 'books'),
	'query_var' => true,
	'supports' => array(
		'title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes',),
	'labels' => array (
		'name' => 'Books',
		'singular_name' => 'Book',
		'menu_name' => 'Books',
		'add_new' => 'Add Book',
		'add_new_item' => 'Add New Book',
		'edit' => 'Edit',
		'edit_item' => 'Edit Book',
		'new_item' => 'New Book',
		'view' => 'View Book',
		'view_item' => 'View Book',
		'search_items' => 'Search Books',
		'not_found' => 'No Books Found',
		'not_found_in_trash' => 'No Books Found in Trash',
		'parent' => 'Parent Book'
	),
) );
} // end register book


// When the plugin is activated, create the post type and recreate the permalink rules (to add 'book')
function mybooks_plugin_activate() {
	register_book_init();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mybooks_plugin_activate' );

// When the plugin is deactivated, reset the permalink rules (to remove 'book')
function mybooks_plugin_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mybooks_plugin_deactivate' );

// When the plugin is deleted, remove the setting from the options db table and reset the permalink rules
function mybooks_plugin_uninstall() {
	delete_option('booklinks_stores'); 
	flush_rewrite_rules();
}
register_uninstall_hook( __FILE__, 'mybooks_plugin_uninstall' );


include	'booklinks-options.php';   // settings screen
include	'booklinks-content.php';   // filters to add links to 'book' posts
include	'booklinks-meta.php';      // custom fields
include	'booklinks-widget.php';    // bookstore links widget
