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

get_footer(); 
?>