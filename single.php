<?php get_header(); ?>
<?php get_template_part( 'nav' ); ?>
<?php while ( have_posts() ) : the_post(); ?>

<header id="header" style="min-height:0;"></header>

<div id="content">

	<div class="row clearfix">
		<div class="columns large-8 medium-8">
			<div>
				<h2 class="pagetitle"><?php the_title(); ?></h2>
				<p class="workshop-description">
					<?php
						$content = get_the_content();
						while( has_sub_field('post_content_alt') ) {
							switch ( get_option('sprout_webdisplay') ) {
								case 'remakelearning' :
									if( get_row_layout() == 'learning_practitioner' ) {
										$content = get_sub_field('post_content');
										break 2;
									}
									break;
								case 'hivepgh' :
								case 'sparkpgh' :
								case 'col' :
									if( get_row_layout() == 'learning_consumer' ) {
										$content = get_sub_field('post_content');
										break 2;
									}
									break;
							}
						}
						$content = apply_filters('the_content', $content);
						$content = str_replace(']]>', ']]&gt;', $content);
						echo $content;
					?>
				</p>
			</div>
		</div>
		<div class="columns large-4 medium-4">
			<div class="panel">
				<?php echo get_the_post_thumbnail( $post->ID, 'medium', array('class'=>'program-logo',) ); ?>
			</div>
		</div>
	</div>
	
	<?php
		global $wpdb;
		$post_ids = $wpdb->get_col( 'SELECT parent_post_id FROM sproutmaster_posts_connections WHERE connection_type = "issuers" AND child_post_id = '.$post->ID.' ORDER BY parent_post_id DESC', 0 );
		if( $post_ids ) :
	?>
		<div class="row">
			<div class="columns">
				<h3 style="margin-bottom: 1rem;">Badges You Can Earn</h3>
				<ul class="large-block-grid-6 medium-block-grid-6 small-block-grid-3">
					<?php
						foreach($post_ids as $post_id) :
							$post = get_post( intval( $post_id ) );
							setup_postdata( $post );
					?>
					<li>
						<a href="<?php the_permalink() ?>">
							<?php echo get_the_post_thumbnail( $post->ID, 'medium', array('class'=>'badge-mini',) ); ?>
						</a>
						<h5><a href="<?php the_permalink() ?>" data-tooltip class="has-tip" title="<?php echo the_field('description_short'); ?>"><?php echo the_title(); ?></a></h5>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	<? endif; ?>

</div><!--end #content-->

<?php endwhile; // end of the loop. ?>
	
<?php get_footer(); ?>