<?php

/* Meta boxes setup. */
add_action( 'admin_menu', 'themeist_theme_admin_meta_boxes_setup' );

/**
 * Sets up the meta boxes and save_post actions.
 *
 * @since 0.1.0
 */
function themeist_theme_admin_meta_boxes_setup() {

	/* Adds meta boxes. */
	add_action( 'add_meta_boxes', 'themeist_themes_add_meta_boxes' );


	/* Saves metadata when the post is saved. */
	add_action( 'save_post', 'themeist_themes_save_theme_info_meta_box', 10, 2 );
}


/**
 * Adds custom meta boxes.
 *
 * @since 0.1.0
 */
function themeist_themes_add_meta_boxes() {

	/* Theme info. */
	add_meta_box( 'themeist-theme-info', 'Theme Info', 'themeist_themes_display_theme_info_meta_box', 'themes', 'normal', 'default' );

}


/**
 * Displays the theme info meta box.
 *
 * @since 0.1.0
 */
function themeist_themes_display_theme_info_meta_box( $object, $box ) { ?>
	<input type="hidden" name="themeist-theme-info-meta-box" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />

	<p>
		<label for="theme-version">Version</label>
		<br />
		<input type="text" name="theme-version" id="theme-version" value="<?php echo esc_attr( get_post_meta( $object->ID, 'theme_version_number', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>
	<p>
		<label for="theme-demo-link">Demo URL</label>
		<br />
		<input type="text" name="theme-demo-link" id="theme-demo-link" value="<?php echo esc_attr( get_post_meta( $object->ID, 'theme_demo_url', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>
	<p>
		<label for="theme-buy-link">Buy Button URL</label>
		<br />
		<input type="text" name="theme-buy-link" id="theme-buy-link" value="<?php echo esc_attr( get_post_meta( $object->ID, 'theme_buy_url', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>
	<p>
		<label for="theme-price">Price</label>
		<br />
		<input type="text" name="theme-price" id="theme-price" value="<?php echo esc_attr( get_post_meta( $object->ID, 'theme_price_number', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>
<?php }


/**
 * Saves the theme info meta box.
 *
 * @since 0.1.0
 */
function themeist_themes_save_theme_info_meta_box( $post_id, $post ) {

	if ( !isset( $_POST['themeist-theme-info-meta-box'] ) || !wp_verify_nonce( $_POST['themeist-theme-info-meta-box'], basename( __FILE__ ) ) )
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );

	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	$meta = array(
		'theme_buy_url' => esc_attr( strip_tags( $_POST['theme-buy-link'] ) ),
		'theme_demo_url' => esc_attr( strip_tags( $_POST['theme-demo-link'] ) ),
		'theme_version_number' => strip_tags( $_POST['theme-version'] ),
		'theme_price_number' => strip_tags( $_POST['theme-price'] ),
	);

	foreach ( $meta as $meta_key => $new_meta_value ) {

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( !empty( $new_meta_value ) && empty( $meta_value ) )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value !== $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' === $new_meta_value && !empty( $meta_value ) )
			delete_post_meta( $post_id, $meta_key, $meta_value );
	}

}