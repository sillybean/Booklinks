<?
add_action('add_meta_boxes', 'booklinks_meta_box');
add_action('save_post', 'booklinks_save_postdata');

function booklinks_meta_box() {
    add_meta_box( 'booklinks_sectionid', __( 'Book Details', 'booklinks_textdomain' ), 'booklinks_inner_custom_box','book', 'normal', 'high');
}

function booklinks_inner_custom_box() {
	wp_nonce_field( plugin_basename(__FILE__), 'booklinks_noncename' );

	$id = get_the_id();
	$book_subtitle = get_post_meta( $id, 'book_subtitle', true );
	$book_isbn = get_post_meta( $id, 'book_isbn', true );
	$store_links = get_post_meta( $id, 'book_stores', true );
	if ( empty($store_links) ) $store_links = array();
	$stores = get_option( 'booklinks_stores' );
	?>
	<p>
		<label for="book_subtitle"><?php _e('Subtitle'); ?></label><br>
		<input class="widefat" type="text" name="book_subtitle" value="<?php echo esc_attr($book_subtitle); ?>" id="book_subtitle">
	</p>

	<p>
		<label for="book_isbn"><?php _e('ISBN'); ?></label><br>
		<input class="widefat" type="text" name="book_isbn" value="<?php echo esc_attr($book_isbn); ?>" id="book_isbn">
	</p>
	<p id="bookstore_links">
		<?php _e('Show store links:'); ?><br />
	<?php if (is_array($stores)) foreach ($stores as $key => $store) : ?>
		<label for="book_stores[<?php echo esc_attr($key); ?>]">
		<input type="checkbox" name="book_stores[<?php echo esc_attr($key); ?>]" value="1" id="book_stores[<?php echo esc_attr($key); ?>]" 
			<?php if (isset($store_links[$key])) checked($store_links[$key], "1"); ?> /> <?php echo esc_html($store['name']); ?></label>&nbsp;&nbsp;
	<?php endforeach; ?>
	</p>
<?php
}

function booklinks_save_postdata( $post_id ) {
	if ( !isset($_POST['booklinks_noncename']) || !wp_verify_nonce( $_POST['booklinks_noncename'], plugin_basename(__FILE__) ) )
	    return $post_id;

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	    return $post_id;

	if ( !current_user_can( 'edit_post', $post_id ) )
		return $post_id;

	if ( !empty( $_POST['book_subtitle'] ) ) {
		$book_subtitle = esc_html( $_POST['book_subtitle'] );
		update_post_meta( $post_id, 'book_subtitle', $book_subtitle );
	}
	
	if ( !empty( $_POST['book_isbn'] ) ) {
		$isbn = str_replace( '-', '', sanitize_text_field( $_POST['book_isbn'] ) );
		if ( booklinks_is_valid_isbn( $isbn ) )
			update_post_meta( $post_id, 'book_isbn', $isbn );	
	}
	
	if ( !empty( $_POST['book_stores'] ) && is_array( $_POST['book_stores'] ) ) {
		update_post_meta( $post_id, 'book_stores', $_POST['book_stores'] );	
	}				
}

// ISBN Validator: http://snipplr.com/view/26569/isbn-validator/
function booklinks_is_valid_isbn($isbn_number) {
	$isbn_digits = array_filter(preg_split('//', $isbn_number, -1, PREG_SPLIT_NO_EMPTY), 'booklinks_is_numeric_or_x');
	$isbn_length = count($isbn_digits);
	$isbn_sum = 0;
 
	if ((10 != $isbn_length) && (13 != $isbn_length))  
		return false;
 
	if (10 == $isbn_length)	{
		foreach(range(1, 9) as $weight) { 
			$isbn_sum += $weight * array_shift($isbn_digits); 
		}
 
		return (10 == ($isbn_mod = ($isbn_sum % 11))) ? ('x' == mb_strtolower(array_shift($isbn_digits), 'UTF-8')) : ($isbn_mod == array_shift($isbn_digits));
	}
 
	if (13 == $isbn_length) {
		foreach(array(1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3) as $weight) { 
			$isbn_sum += $weight * array_shift($isbn_digits); 
		}
 
		return (0 == ($isbn_mod = ($isbn_sum % 10))) ? (0 == array_shift($isbn_digits)) : ($isbn_mod == (10 - array_shift($isbn_digits)));
	}
 
	return false;
}
 
function booklinks_is_numeric_or_x($val) { 
	return ('x' == mb_strtolower($val, 'UTF-8')) ? true : is_numeric($val); 
}