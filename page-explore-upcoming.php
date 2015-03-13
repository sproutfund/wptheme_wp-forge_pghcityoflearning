<?php
get_header();

get_template_part( 'nav' );

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

while ( have_posts() ) : the_post();
	the_content();
endwhile; // end of the loop.

add_filter( 'the_content', 'wpautop' );
add_filter( 'the_excerpt', 'wpautop' );


global $wp_query;
$page    = get_queried_object();
$page_id = $page->ID;

// set portfolio default options
$portfolio_args = array();
$portfolio_args['wp_query'] = array(
	'post_type' => 'opportunity',
	'order'     => 'ASC',
	'orderby'   => 'date',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'meta_query'     => array( array(
		'key' => '_thumbnail_id'
	)),
	'tax_query'      => array( array(
		'taxonomy' => 'webdisplay',
		'field' => 'slug',
		'terms' => get_option( 'sprout_webdisplay' )
	))
);

// make adjustments for opportunity posts
if( $portfolio_args['wp_query']['post_type'] === 'opportunity' ) {
	$portfolio_args['wp_query']['post_status'] = array( 'publish', 'future' );
	if( function_exists('get_postids_by_date_relative') ) {
		$portfolio_args['wp_query']['post__in'] = get_postids_by_date_relative('future');
	}
}

// if the portfolio is of a post_type, get custom stickies
if ( class_exists( 'Post_Type_Spotlight' ) &&  isset($portfolio_args['wp_query']['post_type']) ) {
	$featured_posts = new WP_Query( array(
		'post_type' => $portfolio_args['wp_query']['post_type'],
		'meta_query' => array(
			array(
				'key' => '_pts_featured_post'
			)
		)
	) );
	foreach ($featured_posts as $featured_post) {
		array_push($portfolio_args['wp_query']['post__in'], $featured_post->ID);
	}
}

$wp_query = new WP_Query($portfolio_args['wp_query']);

$portfolio_post_ids = array();
$portfolio_post_letters_raw = array();
foreach($wp_query->posts as $post) {
	$portfolio_post_ids[] = $post->ID;
	$portfolio_post_letters_raw[] = substr($post->post_name, 0, 1);
}
$portfolio_post_letters = array_unique($portfolio_post_letters_raw, SORT_STRING);

$portfolio_args['results']['request_str' ] = $wp_query->request;
$portfolio_args['results']['post_ids'    ] = $portfolio_post_ids;
$portfolio_args['results']['post_letters'] = $portfolio_post_letters;

if ( have_posts() ) {
	$items = array();
	while ( have_posts() ) {
		the_post();
		$thumb = get_the_post_thumbnail( $post->ID, 'medium', array('class'=>'logo',) );
		$link  = get_the_permalink();
		$title = get_the_title();
		$description = get_the_excerpt();
		$item = "";
		$item .= '<div class="panel search-result" data-equalizer-watch>'; 
		$item .= $thumb;
		$item .= '<p class="link">';
		$item .= '<a data-tooltip class="has-tip" href="'.$link.'" title="'.$description.'">'.$title.'</a>';
		$item .= '</p>';
		
		$has_datetime     = false;
		$datetime_info = get_datetime_info($post->ID);
		if (!empty($datetime_info)) $has_datetime = true; 
		if( $has_datetime ) {
			if( !empty($datetime_info[0]['print']) ) {
				if( $datetime_info[0]['print']['dates'] != '' ) {
					$dateinfo = $datetime_info[0]['print']['dates'];
				}
			}
			$item .= '<p class="date">'.$dateinfo.'</p>';
		}
		
		$item .= '<p class="description">'.get_the_excerpt().'</p>';
		
		$item .= '</div>';
		
		$items[] = $item;
	}
	
	wp_reset_query();
	
	$itemscount = count($items);
	$resultsHtml .= '<!--Item Count is '.$itemscount.'-->';
	$i = 1;
	$resultsHtml .= '<div class="row" data-equalizer>';
	foreach ( $items as $item ) {
		if ($i == $itemscount) {
			$resultsHtml .= '<div class="columns large-3 medium-3 end">';
			$resultsHtml .= $item;
			$resultsHtml .= '</div>';
			$resultsHtml .= '</div>';
			break;
		} else {
			$resultsHtml .= '<div class="columns large-3 medium-3">';
			$resultsHtml .= $item;
			$resultsHtml .= '</div>';
			if ( ($i % 4) == 0 ) {
				$resultsHtml .= '</div>';
				$resultsHtml .= '<div class="row" data-equalizer>';
			}
			$i++;
		}
	}
} else {
	//no results
	$resultsHtml  = '<div id="empty-results">';
	$resultsHtml .= '<p class="text-center">No items were found that fit your search criteria. Edit your search to keep trying to find opportunities that interest you.</p>';
	$resultsHtml .= '</div>';
}
?>
<div id="content"><?php echo $resultsHtml; ?></div>
<?php get_footer(); ?>