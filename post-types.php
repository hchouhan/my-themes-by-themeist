<?php

add_action( 'init', 'themeist_register_theme_post_types' );

function themeist_register_theme_post_types() {

	/* Plugin post type. */
	register_post_type( 'themes',
		array(
			'public' => 		true,
			'publicly_queryable' =>	true,
			'show_in_nav_menus' =>	true,
			'show_in_admin_bar' => 	true,
			'exclude_from_search' =>	false,
			'hierarchical' => 		false,
			'show_ui' => true,

			'has_archive' =>		 'themes',
			'query_var' => 		'themes',
			'capability_type' => 	'post',
			'menu_position' => 		5,

			'exclude_from_search' => false,
			'menu_position' => 5,
			'labels' => array(
				'name' => __( 'Themes' ),
				'singular_name' => __( 'Theme' ),
				'menu_name' => 		'My Themes',
				'add_new' => __( 'Add New' ),
				'add_new_item' => __( 'Add New Theme' ),
				'edit' => __( 'Edit Theme' ),
				'edit_item' => __( 'Edit Theme' ),
				'new_item' => __( 'New Theme' ),
				'view' => __( 'View Theme' ),
				'view_item' => __( 'View Theme' ),
				'search_items' => __( 'Search Theme' ),
				'not_found' => __( 'No Themes found' ),
				'not_found_in_trash' => __( 'No Themes found in Trash' ),
				'parent_item_colon' =>	null,
				'all_items' =>		'All Themes'
			),

			// this sets where the Themes section lives and contains a tag to insert the Platform in URL below
			// this can be any depth e.g. themes/%theme_platform%
			'rewrite' => array(
				'slug' => 			'themes/%theme_platform%',
				'with_front' => 		false,
				'feeds' =>		true,
			),

			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'comments'
			)

		)
	);
}


	add_filter('post_type_link', 'themes_permalink_filter_function', 1, 3);
	function themes_permalink_filter_function( $post_link, $id = 0, $leavename = FALSE ) {
		if ( strpos('%theme_platform%', $post_link) === 'FALSE' ) {
		  return $post_link;
		}
		$post = get_post($id);
		if ( !is_object($post) || $post->post_type != 'themes' ) {
		  return $post_link;
		}
		// this calls the term to be added to the URL
		$terms = wp_get_object_terms($post->ID, 'theme_platform');
		if ( !$terms ) {
		  return str_replace('themes/%theme_platform%/', '', $post_link);
		}
		return str_replace('%theme_platform%', $terms[0]->slug, $post_link);
	}

?>