<?php

add_filter('the_content', 'append_booklinks');
add_shortcode('booklinks', 'booklinks_shortcode');

function append_booklinks($content, $id = 0, $doing_shortcode = false) {
	$links = array();
	global $post;
	if ( $doing_shortcode || get_post_type($post) == 'book' ) {
		if ( !isset($id) || !$id )
			$id = $post->ID;
		$isbn = get_post_meta( $id, 'book_isbn', true );
		$stores = get_post_meta( $id, 'book_stores', true );
		$options = get_option( 'mybooks_stores' );
		foreach ( $stores as $store => $on ) {
			if ( $on && !empty( $options[$store]['link'] ) ) {
				$link = str_replace( array( '_ISBN_', '_CODE_' ), array( $isbn, $options[$store]['code'] ), $options[$store]['link'] );
				$links[] = '<a href='. $link . '>' . $options[$store]['name'] . '</a>';
			}
		}
	}
	
	$links = '<div class="booklinks">' . implode(' | ', $links) . '</div>';
	
	return $links . $content;
}

// [booklinks id="n"] or [booklinks name="my-book-title"] or [booklinks slug="my-book-title"]
function booklinks_shortcode($atts, $content = NULL) {
	
	extract( shortcode_atts( array(), $atts ) );
	
	if ( !isset( $id ) ) {
		if ( !isset( $name ) && isset( $slug ) )
			$name = $slug;
			
		if ( isset( $name ) ) {
			
			$posts = get_posts( array(
				'name' => $name,
				'post_type' => 'book',
				'post_status' => 'publish',
				'posts_per_page' => 1
			) );
			
			if ( $posts )
				$id = $posts[0]->ID;
		}
	}
	
	if ( isset( $id ) && $id )
		return append_booklinks( $content, $id, true );
	
	return $content;
}