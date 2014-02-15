<?
add_filter('the_content', 'append_booklinks');
add_shortcode('booklinks', 'booklinks_shortcode');

function append_booklinks($content, $doing_shortcode = false) {
	$links = array();
	global $post;
	if ( $doing_shortcode || get_post_type($post) == 'book' ) {
		$isbn = get_post_meta( $post->ID, 'book_isbn', true );
		$stores = get_post_meta( $post->ID, 'book_stores', true );
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

// [booklinks]
function booklinks_shortcode($atts, $content = NULL) {
	extract(shortcode_atts(array(), $atts));
	return append_booklinks($content, true);
}