<?
add_action('widgets_init', 'booklinks_widget_init');

function booklinks_widget_init() {
	register_widget( 'Booklinks_Widget' );
} 

class Booklinks_Widget extends WP_Widget {
	
	function Books_Widget() {
		$widget_ops = array(
			'classname' => 'booklinks_widget',
			'description' => __('Booklinks Widget', 'booklinks_widget') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'booklinks-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'booklinks-widget', __('Books Widget', 'Options'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		$booksnum = $instance['booklinks_num'];
		$orderby = $instance['booklinks_orderby'];
		$slide = $instance['booklinks_slide'];
		$timeout = $instance['booklinks_timeout'];
		$title = apply_filters('widget_title', $instance['title'] );
		$link = $instance['booklinks_link'];
		/* Before widget (defined by themes). */
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;
		
		// Settings from the widget

	$books = get_posts( array(
		'posts_per_page' => $booksnum,
		'orderby' => $orderby,
		'order' => 'ASC',
		'post_type' => 'book',
		'post_status' => 'publish',
	));
	
	if ($books) { 
		if ($slide == 'true') { ?>
			<script type="text/javascript" charset="utf-8">
				jQuery(document).ready(function() {
					jQuery('.book-wrapper').cycle({
						timeout: <?php echo $timeout * 1000; ?>,
						fx: 'scrollUp' // choose your transition type, ex: fade, scrollUp, shuffle, etc...
					});
				});
			</script>
			<? } ?>
	<div class="book-wrapper">
		<?php
		foreach ($books as $book) {
			$id = $book->ID;
			$title = array('title' => apply_filters('the_title', $book->post_title ));
			$subtitle = get_post_meta($id, 'book_subtitle', true);
			$isbn = get_post_meta( $id, 'book_isbn', true );
			$stores = get_post_meta( $id, 'book_stores', true );
			$options = get_option( 'mybooks_stores' );
		?>
		<div class="books-text">
			<?php if (has_post_thumbnail($id))
				echo '<a href="'.get_permalink($id).'">'.get_the_post_thumbnail( $id, 'book-thumb', $title).'</a>';
			?>
			<p class="booktitle"><?php echo $title; ?></p>
			<p class="subtitle">
				<?php if (!empty($subtitle)): ?>		
					<?php echo $subtitle; ?><br>
				<?php endif; ?>					
			</p>
			<?php
			
			foreach ( $stores as $store => $on ) {
				if ( $on && !empty( $options[$store]['link'] ) ) {
					$link = str_replace( array( '_ISBN_', '_CODE_' ), array( $isbn, $options[$store]['code'] ), $options[$store]['link'] );
					$links[] = '<a href='. $link . '>' . $options[$store]['name'] . '</a>';
				}
			}
			
			echo '<div class="booklinks">' . implode(' | ', $links) . '</div>';
			?>
		</div>
	</div><!--END book-wrapper-->
	<?php } // endif
		
		echo $after_widget;
	}


	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['booklinks_timeout'] = int($new_instance['booklinks_timeout']);
		$instance['booklinks_num'] = int($new_instance['booklinks_num']);
		$instance['booklinks_orderby'] = $new_instance['booklinks_orderby'];
		if (!in_array($instance['booklinks_orderby'], array('menu_order','rand','modified')) )
			$instance['booklinks_orderby'] = 'menu_order';
		$instance['booklinks_slide'] = int($new_instance['booklinks_slide']);
		$instance['booklinks_link'] = int($new_instance['booklinks_link']);
		$instance['booklinks_title'] = sanitize_text_field( $new_instance['booklinks_title'] );
		return $instance;
	}


	function form( $instance ) { 
		
		$defaults = array(
			'booklinks_timeout' => '12',
			'booklinks_num' => '-1',
			'booklinks_orderby' => 'menu_order',
			'booklinks_slide' => '1',
			'booklinks_title' => __('Books'),
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'booklinks_title' ); ?>"><?php _e('Title:'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'booklinks_title' ); ?>" name="<?php echo $this->get_field_name( 'booklinks_title' ); ?>" value="<?php echo $instance['booklinks_title']; ?>" class="widefat" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'booklinks_num'); ?>"><?php _e('Number of Books to display'); ?></label><br>
			<input type="text" name="<?php echo $this->get_field_name( 'booklinks_num'); ?>" 
				value="<?php echo $instance['booklinks_num']; ?>" id="<?php echo $this->get_field_id( 'booklinks_num'); ?>">
		</p>
		<p><label for="<?php echo $this->get_field_id( 'booklinks_orderby' ); ?>"><?php _e('In which order do you want the books to display?'); ?></label><br>
			<select name="<?php echo $this->get_field_name( 'booklinks_orderby'); ?>" id="<?php echo $this->get_field_id( 'booklinks_orderby'); ?>">
				<option <?php selected('menu_order', $instance['booklinks_orderby']); ?> value="menu_order"><?php _e('Menu Order'); ?></option>
				<option <?php selected('rand', $instance['booklinks_orderby']); ?> value="rand"><?php _e('Random'); ?></option>
				<option <?php selected('modified', $instance['booklinks_orderby']); ?> value="modified"><?php _e('Last Modified'); ?></option>				
			</select>
		</p>
		<p>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'booklinks_slide'); ?>" value="1" <?php checked('1', $instance['booklinks_link']); ?> id="<?php echo $this->get_field_id( 'booklinks_slide'); ?>">
			<label for="<?php echo $this->get_field_id( 'booklinks_slide'); ?>"><?php _e('Rotate books with animation <small>(If selected, books widget will be an animated slideshow)</small>'); ?></label>
		</p>
		
		<p>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'booklinks_link'); ?>" value="1" <?php checked('1', $instance['booklinks_link']); ?> id="<?php echo $this->get_field_id( 'booklinks_link'); ?>">
			<label for="<?php echo $this->get_field_id( 'booklinks_link'); ?>"><?php _e('Link books to overview pages <small>(If selected, book images will always link to book page, instead of linking to Amazon by default)</small>'); ?></label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'booklinks_timeout' ); ?>"><?php _e('Seconds between transitions <small>(If above option is checked)</small>'); ?></label>
			<br><input type="text" name="<?php echo $this->get_field_name( 'booklinks_timeout' ); ?>" value="<?php echo $instance['booklinks_timeout']; ?>" id="<?php echo $this->get_field_id( 'booklinks_timeout' ); ?>">
		</p>

	<?php
	}
}