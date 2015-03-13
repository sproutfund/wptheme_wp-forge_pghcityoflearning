<?php
	get_header();
	get_template_part( 'nav' );
?>
<header id="header" style="background-image: url(http://cloudfront.sproutfund.org/sites/pghcityoflearning.org/img/banner/cmp_[1440px].jpg);">

			<div class="row">
					<div class="columns large-8 large-centered medium-9 medium-centered text-center">
							<div class="panel radius">
									<h2 class="pagetitle">Badges You Can Earn</h2>
									<p>Explore all of the badges available through Pittsburgh City of Learning.</p>
							</div>
					</div>
			</div>

</header>
<div id="content">
	<?php if ( have_posts() ) : ?>
		<div class="row">
			<div class="columns large-12 medium-12">
				<ul class="medium-block-grid-6 small-block-grid-3">
					<?php while ( have_posts() ) : the_post(); ?>
						<li>
							<a href="<?php the_permalink() ?>">
								<?php echo get_the_post_thumbnail( $post->ID, 'medium', array('class'=>'badge-mini',) ); ?>
							</a>
							<h5><a href="<?php the_permalink() ?>" data-tooltip class="has-tip" title="<?php echo the_field('description_short'); ?>"><?php echo the_title(); ?></a></h5>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
		</div>
		<?php if ( ($wp_query->max_num_pages > 1) && function_exists('page_navi') ) : ?>
			<div class="row">
				<div class="columns medium-4 medium-centered">
					<div style="text-align: center;">
						<div style="display: inline-block;">
							<?php page_navi(); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php get_footer(); ?>