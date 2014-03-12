=== Booklinks ===
Contributors: sillybean
Tags: books, authors, books, bookstores
Requires at least: 3.1
Tested up to: 3.8
Stable tag: 1.0
License: GPLv2

Booklinks lets you create a bookstore with links to bookstores you specify. The list of stores is configurable and supports affiliate codes. Store links can be turned on per book (so you can link only to ebook stores for an ebook, for example). 

== Description ==

Allows you to create books (a custom post type) with links to bookstores. On the plugin's option page, you can specify the list of bookstores you wish to use. The default list includes Amazon, B&N, Indiebound, iBooks, and Audible. You may remove any of these and add your own bookstores. On each individual book, you'll be able to toggle the display of these stores (so that you don't include a link to Audible when no audiobook is available, for example).

Bookstore links are automatically appended to the content of book posts. Elsewhere, you may use the [booklinks id="n"] shortcode, where id is the ID of the book post whose books you wish to display. You may also use slugs with either the slug or name attribute:

[booklinks id="n"]
[booklinks name="my-book-title"]
[booklinks slug="my-book-title"]

A rewrite of MyBooks for Authors by Out:think Group.

== Installation ==

1. Upload the plugin through the Plugins > Add New screen, or unzip 'booklinks' to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add new books by going to Books > Add New in your admin menu

== Changelog ==

= 1.0 =
* Rewrite of MyBooks for Authors. Removed built-in bookstore list and made it totally configurable. Rewrote all settings for data sanitization and validation. 