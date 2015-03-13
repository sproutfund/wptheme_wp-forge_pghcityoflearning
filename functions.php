<?php

// Runs before the posts are fetched
add_filter( 'pre_get_posts' , 'sort_badges_by_name' );
// Function accepting current query
function sort_badges_by_name( $query ) {
	// Check if the query is for an archive
	if( is_post_type_archive('badge') ) :
		// Query was for archive, then set order
		$query->set( 'order' , 'asc' );
		$query->set( 'orderby' , 'name' );
	// Return the query (else there's no more query, oops!)
	endif;
	return $query;
}

/*--------------------------------------------------------------------------------------
*
*	Set theme thumbnail sizes
* 
*-------------------------------------------------------------------------------------*/

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );

	/*
	$responsive_sizes = array(
		'small' => array(
			'portfolio'       => 'thumbnail-180px',
			'collection-hero' => 'thumbnail-360px',
			'single-feature'  => 'thumbnail-360px',),
		'small retina' => array(
			'portfolio'       => 'thumbnail-360px',
			'collection-hero' => 'thumbnail-720px',
			'single-feature'  => 'thumbnail-720px',),
		'medium' => array(
			'portfolio'       => 'thumbnail-180px',
			'collection-hero' => 'thumbnail-360px',
			'single-feature'  => 'thumbnail-600px',),
		'medium retina' => array(
			'portfolio'       => 'thumbnail-360px',
			'collection-hero' => 'thumbnail-720px',
			'single-feature'  => 'thumbnail-1200px',),
		'large' => array(
			'portfolio'       => 'thumbnail-360px',
			'collection-hero' => 'thumbnail-600px',
			'single-feature'  => 'thumbnail-900px',),
		'large retina' => array(
			'portfolio'       => 'thumbnail-720px',
			'collection-hero' => 'thumbnail-1200px',
			'single-feature'  => 'thumbnail-1800px',),
	);
	*/

	//standard Sprout thumbnails; assumes 6x4 landscape uploads
	add_image_size( 'thumbnail-90px',      90,   90, true ); // "thumbnail" / mini-square
	add_image_size( 'thumbnail-120px',    120,   80, true ); // front-page-more-blog-posts
	add_image_size( 'thumbnail-180px',    180,  120, true ); // small portfolio
	add_image_size( 'thumbnail-240px',    240,  160, true ); // 
	add_image_size( 'thumbnail-360px',    360,  240, true ); // small collection-hero, small single-feature, small retina portfolio
	add_image_size( 'thumbnail-480px',    480,  320, true ); // front-page-opportunities
	add_image_size( 'thumbnail-600px',    600,  400, true ); // collection hero 
	add_image_size( 'thumbnail-720px',    720,  480, true ); // small retina collection-hero
	add_image_size( 'thumbnail-900px',    900,  600, true ); // "large" & single feature image
	add_image_size( 'thumbnail-1200px',  1200,  800, true ); // 2x collection hero
	add_image_size( 'thumbnail-1800px',  1800, 1200, true ); // 2x "large"& single feature image

}

/*--------------------------------------------------------------------------------------
*
*	This theme's Sprout Directory contact info to print in sidebar 
* 
*-------------------------------------------------------------------------------------*/

function sprout_print_sidebar_contactinfo($args, $echo = true) {
	$type = $args['type'];
	$data = isset($args['data']) ? $args['data'] : '';
	$text = isset($args['text']) ? $args['text'] : '';
	$href = isset($args['href']) ? $args['href'] : '';
	$title = isset($args['title']) ? $args['title'] : '';
	if( !isset($args['icon']) ) { 
		if( $type == 'email_address' ) { $icon = 'envelope'; }
		if( $type == 'phone_number' )  { $icon = 'phone'; }
		if( $type == 'website' )       { $icon = 'external-link'; }
	} else {
		$icon = $args['icon'];
	}
	$contact_print  = '<li class="icon-'.$icon.'">';
	switch( $type ) {
		case 'text' :
		case 'phone_number' :
			if( $data != '' ) {
				$contact_print .= $data;
			} elseif( $text != '' ) {
				$contact_print .= $text;
			}
			break;
		case 'email_address' :
			$link = '<a href="mailto:'.$data.'">'.$data.'</a>';
			if( function_exists( 'c2c_obfuscate_email' ) ) {
				$link = c2c_obfuscate_email( $link );
			}
			$contact_print .= $link;
			break;
		case 'website' :
		case 'intlink' :
			if( $href == '' ) { $href = $data; };
			if( $text == '' ) { 
				if( function_exists( 'url_to_domain' ) ) {
					$text = url_to_domain( $href );
				} else {
					$text = $href;
				}
			}
			$contact_print .= '<a href="'.$href.'" title="'.$title.'"';
			if( $type == 'website' ) { $contact_print .= ' target="_blank"'; };
			$contact_print .= '>';
			$contact_print .= $text;
			$contact_print .= '</a>';	
			break;
	}
	$contact_print .= '</li>';
	if($echo) { echo $contact_print; } else { return $contact_print; }
}

?>