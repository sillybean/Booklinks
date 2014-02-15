<?php
add_action('admin_menu', 'booklinks_create_menu');

function booklinks_create_menu() {
	$pg = add_options_page(__('Bookstore Settings'), __('Bookstore Settings'), 'administrator', 'booklinks_settings', 'booklinks_settings_page');
	add_action( 'admin_print_scripts-'.$pg, 'booklinks_admin_scripts' );
	add_action( 'admin_init', 'booklinks_register_settings' );
}

function booklinks_register_settings() {
	register_setting( 'booklinks_stores_group', 'booklinks_stores', 'booklinks_validate_settings' );
}

function booklinks_validate_settings( $input ) {
	$output = array();
	if (is_array( $input )) foreach ( $input as $key => $array ) {
		if ( !empty( $array['name'] ) ) {
			$newkey = sanitize_key( $array['name'] );
			$output[$newkey] = $array;
			unset( $input[$key] );
		}
	}
	// DEBUG
	/*
	echo '<pre>';	
	var_dump($output);
	echo '</pre>';
	exit;
	/**/
	return $output;
}

function booklinks_settings_page() {
?>
<div class="wrap">
<h2><?php _e('Bookstore Links'); ?></h2>

<form method="post" action="options.php"><pre>
    <?php settings_fields( 'booklinks_stores_group' );
 	$options = get_option('booklinks_stores'); 
	if (!isset($options) || empty($options))
		$options = array(
			'amazon' => array('name' => __('Amazon'), 'link' => 'http://www.amazon.com/gp/search?keywords=_ISBN_&index=books&linkCode=qs&tag=_CODE_'), 
			'barnesnoble' => array('name' => __('Barnes & Noble'), 'link' => 'http://search.barnesandnoble.com/booksearch/isbninquiry.asp?ean=_ISBN_'), 
			'indiebound' => array('name' => __('IndieBound'), 'link' => 'http://www.indiebound.org/book/_ISBN_?aff=_CODE_'), 
			'ibooks' => array('name' => __('iBooks'), 'link' => 'http://itunes.apple.com/lookup?isbn=_ISBN_&partnerId=_CODE_'), 
			'audible' => array('name' => __('Audible'), 'link' => ''), 
		);
//	print_r($options); ?>
</pre>   
<p><?php _e('Enter <kbd>_ISBN_</kbd> and <kbd>_CODE_</kbd> in place of the ISBN and affiliate code in the link format field. These will be replaced with your book\'s ISBN and your affiliate code when the bookstore links are generated.'); ?></p>
 <table class="widefat wp-list-table" id="mybooks_options">
		<thead>
		<tr valign="top" class="bookstore">
			<th><?php _e('Bookstore Name'); ?></th>
			<th><?php _e('Link Format'); ?></th>
			<th><?php _e('Affiliate Code (optional)'); ?></th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($options as $id => $store) :
				$name = isset($store['name']) ? $store['name'] : ''; 
				$code = isset($store['code']) ? $store['code'] : ''; 
				$link = isset($store['link']) ? $store['link'] : ''; 
		?>
		<tr valign="top" class="bookstore">
			<td>
				<label class="hidden" for="booklinks_stores[<?php echo esc_attr($id); ?>][name]"><?php _e('Store Name'); ?></label>
				<input name="booklinks_stores[<?php echo esc_attr($id); ?>][name]"
						value="<?php echo esc_attr($name); ?>" class="large-text">
			</td>
			<td>
				<label class="hidden" for="booklinks_stores[<?php echo esc_attr($id); ?>][link]"><?php _e('Link Format'); ?></label>
				<input name="booklinks_stores[<?php echo esc_attr($id); ?>][link]"
				 		value="<?php echo esc_url($link); ?>" class="large-text">
			</td>
			<td>
				<label class="hidden" for="booklinks_stores[<?php echo esc_attr($id); ?>][code]"><?php _e('Affiliate Code'); ?></label>
				<input name="booklinks_stores[<?php echo esc_attr($id); ?>][code]"
				 		value="<?php echo esc_attr($code); ?>" class="large-text">
			</td>
			<td>
				<span class="trash"><a href="#" class="delRow" title="Remove <?php esc_attr_e($name); ?>"><?php _e('delete'); ?></a></span>
			</td>
		</tr>
		<?php endforeach; ?>
		
		<tr valign="top" class="bookstore hidden">
			<td>
				<label class="hidden" for="booklinks_stores[newstore][name]"><?php _e('Store Name'); ?></label>
				<input name="booklinks_stores[newstore][name]"
						value="" class="name large-text">
			</td>
			<td>
				<label class="hidden" for="booklinks_stores[newstore][link]"><?php _e('Link Format'); ?></label>
				<input name="booklinks_stores[newstore][link]"
				 		value="" class="link large-text">
			</td>
			<td>
				<label class="hidden" for="booklinks_stores[newstore][code]"><?php _e('Affiliate Code'); ?></label>
				<input name="booklinks_stores[newstore][code]"
				 		value="" class="code large-text">
			</td>
			<td>
				<span class="trash"><a href="#" class="delRow" title="Remove store"><?php _e('delete'); ?></a></span>
			</td>
		</tr>
		</tbody>
		<tfoot>
			<tr>
				<th><a href="#" class="button-secondary cloneTableRows"><?php _e('Add a Store'); ?></a></th>
				<th colspan="3"></th>
			</tr>
		</tfoot>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
</form>
</div>
<?php } ?>