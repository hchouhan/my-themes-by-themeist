<?php

add_filter( 'pre_get_posts', 'themeist_themes_pre_get_posts' );


function themeist_themes_pre_get_posts( $query ) {

	if ( is_admin() )
		return $query;

	elseif ( $query->is_main_query() && is_post_type_archive( 'themes' ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'asc' );
	}

/*	elseif ( $query->is_main_query() && is_search() ) {
		$query->set( 'posts_per_page', 25 );
	}*/

	return $query;
}


?>