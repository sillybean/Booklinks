<?php
/*
Plugin Name: Bookstore Links
Plugin URI: http://stephanieleary.com
Description: Creates a books section for your site with links to bookstores. 
The list of stores is configurable and supports affiliate codes. 
Store links can be turned on per book (so you can link only to ebook stores for an ebook, for example). 
Similar to MyBooks for Authors, by Out:think Group.
Version: 1.0b
Author: Stephanie Leary
Author URI: http://stephanieleary.com

    Copyright 2013 - Stephanie Leary (steph@sillybean.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
function mybooks_plugin_activate() {
	register_book_init();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mybooks_plugin_activate' );

function mybooks_plugin_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mybooks_plugin_deactivate' );

function mybooks_plugin_uninstall() {
	delete_option('booklinks_stores'); 
	flush_rewrite_rules();
}
register_uninstall_hook( __FILE__, 'mybooks_plugin_uninstall' );

// JS
add_action('admin_init', 'booklinks_scripts');
function booklinks_scripts() {
	wp_enqueue_script( 'bookslinks-admin', plugins_url( 'js/bookslinks-admin.js', __FILE__ , 'jquery'));
}

// initializes the post type
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

include	'booklinks-options.php';
include	'booklinks-widget.php';
include	'booklinks-content.php';
include	'booklinks-meta.php';