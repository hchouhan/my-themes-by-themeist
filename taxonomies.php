<?php

add_action( 'init', 'themeist_register_theme_taxonomies' );

function themeist_register_theme_taxonomies() {

	/* Theme Platform taxonomy. */
	register_taxonomy(
		'theme_platform',
		array( 
			'themes' 
		),
		array(
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'hierarchical' => false,
			'query_var' => true,

			'labels' => array(
				'name' => __( 'Theme Platform' ),
				'singular_name' => __( 'Theme Platform' ),
				'search_items' => __( 'Search Theme Platform' ),
				'popular_items' => __( 'Popular Theme Platform' ),
				'all_items' => __( 'All Theme Platforms' ),
				'parent_item' => __( 'Parent Theme Platform' ),
				'parent_item_colon' => __( 'Parent Theme Platform:' ),
				'edit_item' => __( 'Edit Theme Platform' ),
				'update_item' => __( 'Update Theme Platform' ),
				'add_new_item' => __( 'Add New Theme Platform' ),
				'new_item_name' => __( 'New Theme Platform' ),
			),

			// this sets the taxonomy view URL (must have category base i.e. /platform)
			// this can be any depth e.g. themeist.co/themes/platform
			'rewrite' => array(
				'with_front' => 		false,
				'slug' => 			'themes'
			),
		)
	);	

}


?>