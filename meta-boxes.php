<?php

/* Meta boxes setup. */
add_action( 'admin_menu', 'themeist_plugins_admin_meta_boxes_setup' );

/**
 * Sets up the meta boxes and save_post actions.
 *
 * @since 0.1.0
 */
function themeist_plugins_admin_meta_boxes_setup() {

	/* Adds meta boxes. */
	add_action( 'add_meta_boxes', 'themeist_plugins_add_meta_boxes' );


	/* Saves metadata when the post is saved. */
	add_action( 'save_post', 'themeist_plugins_save_plugin_info_meta_box', 10, 2 );
}


/**
 * Adds custom meta boxes.
 *
 * @since 0.1.0
 */
function themeist_plugins_add_meta_boxes() {

	/* Plugin info. */
	add_meta_box( 'th-plugin-info', 'Plugin Info', 'themeist_plugins_display_plugin_info_meta_box', 'themes', 'normal', 'default' );

}


/**
 * Displays the plugin info meta box.
 *
 * @since 0.1.0
 */
function themeist_plugins_display_plugin_info_meta_box( $object, $box ) { ?>
	<input type="hidden" name="themeist-plugin-info-meta-box" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />

	<p>
		<label for="plugin-version">Version</label>
		<br />
		<input type="text" name="plugin-version" id="plugin-version" value="<?php echo esc_attr( get_post_meta( $object->ID, 'plugin_version_number', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>
	<p>
		<label for="plugin-download-link">Download URL</label>
		<br />
		<input type="text" name="plugin-download-link" id="plugin-download-link" value="<?php echo esc_attr( get_post_meta( $object->ID, 'plugin_download_url', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>
	<p>
		<label for="plugin-repo-link">Repository URL</label>
		<br />
		<input type="text" name="plugin-repo-link" id="plugin-repo-link" value="<?php echo esc_attr( get_post_meta( $object->ID, 'plugin_repo_url', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>
<?php }


/**
 * Saves the plugin info meta box.
 *
 * @since 0.1.0
 */
function themeist_plugins_save_plugin_info_meta_box( $post_id, $post ) {

	if ( !isset( $_POST['themeist-plugin-info-meta-box'] ) || !wp_verify_nonce( $_POST['themeist-plugin-info-meta-box'], basename( __FILE__ ) ) )
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );

	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	$meta = array(
		'plugin_download_url' => esc_attr( strip_tags( $_POST['plugin-download-link'] ) ),
		'plugin_repo_url' => esc_url( strip_tags( $_POST['plugin-repo-link'] ) ),
		'plugin_version_number' => strip_tags( $_POST['plugin-version'] ),
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

	/* We need this b/c draft post's post_name don't get set. */
	$post_name = !empty( $post->post_name ) ? $post->post_name : sanitize_title( $post->post_title );

	if ( !term_exists( "relationship-{$post_name}", 'doc_relationship' ) ) {

		$args = array( 'slug' => "relationship-{$post_name}" );

		$parent_term = get_term_by( 'slug', 'relationship-plugins', 'doc_relationship' );

		if ( !empty( $parent_term ) )
			$args['parent'] = $parent_term->term_id;

		wp_insert_term( $post->post_title, 'doc_relationship', $args );
	}
}


?>