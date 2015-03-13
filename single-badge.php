<?php get_header(); ?>
<?php get_template_part( 'nav' ); ?>
<?php while ( have_posts() ) : the_post(); ?>

<?php
	global $wpdb;
	$earners = $wpdb->get_results( 'SELECT * FROM badgelab_issued WHERE badge_id = '.$post->ID.' ORDER BY name_firstnick, name_lastinitial', ARRAY_A );
	$earner_count = count($earners);
?>


<header id="header" style="min-height:0;"></header>

<div id="content">

	<div class="row clearfix">
		<div class="columns large-8 medium-8">
			<h2 class="pagetitle"><?php the_title(); ?></h2>
			<p class="workshop-description">
				<?php 
					if( get_field('description_earner') ) {
						the_field('description_earner');
					} else {
						the_field('description_short');
					}
				?>
			</p>

			<div class="row">
				<div class="columns">
					<h3>Who Can Help Me Earn This Badge?</h3>
					<?php if( get_field('issuers') ) : ?>
						<ul class="no-bullet">
							<?php
								while( has_sub_field('issuers') ) {
									$issuer = "";
									$this_id = 0;
									$this_name = '';
									$this_link = '';
									switch ( get_row_layout() ) {
										case 'network_member' :
										case 'opportunity' :
											$this_obj  = get_sub_field('member_obj');
											$this_id   = $this_obj->ID;
											$this_name = get_the_title($this_id);
											$this_link = get_permalink($this_id);
											break;
										case 'external_contact' :
											$this_name = get_sub_field('external_name');
											$this_link = get_sub_field('url');
											break;
									}
									if( !empty($this_link) ) { 
										$issuer = "<a href=\"$this_link\">$this_name</a>";
									} else {
										$issuer = $this_name;
									}
									echo "<li>$issuer</li>";
								}
							?>
						</ul>
					<?php endif ?>
				</div>
			</div>
			<div class="row">
				<div class="columns">
					<?php if( get_field('criteria') ) : ?>
					<h3>How Is This Badge Earned?</h3>
					<ul>
						<?php
							while( has_sub_field('criteria') ) {
								$req_opt     = get_sub_field('type');
								$description = get_sub_field('description');
								if( $req_opt == 'optional' ) {
									echo "<li>$description (<span style=\"font-style:italic;\">optional</span>)</li>";
								} else {
									echo "<li>$description</li>";
								}
							}
						?>
					</ul>
					<?php endif; ?>
				</div>
			</div>
			<?php if( $earner_count > 0 ) : ?>
				<div class="row">
					<div class="columns">
						<h3><?php echo number_format($earner_count, 0, ".", ","); ?> People Have Earned This Badge!</p>
						<style type="text/css">
							@media only screen { 
								.newspaper {
									-webkit-column-count: 2; 
									-moz-column-count: 2; 
									column-count: 2; 
								}
							}
							@media only screen and (min-width: 40.063em) { 
								.newspaper {
									-webkit-column-count: 4; 
									-moz-column-count: 4; 
									column-count: 4; 
								}
							}
						</style>
						<ul class="no-bullet newspaper">
							<?php
								$anonymous_count = 0;
								$names = "";
								foreach( $earners as $earner ) {
									if( $earner['is_anon'] == 1 ) {
										$anonymous_count += 1;
									} else {
										$name = trim($earner['name_firstnick'].' '.$earner['name_lastinitial']);
										$names .= "<li>$name</li>";
									}
								}
								echo $names;
								if( $anonymous_count == 1 ) {
									echo "<li>Anonymous</li>";
								} elseif( $anonymous_count > 1 ) {
									echo "<li>Anonymous ($anonymous_count)</li>";
								}
							?>
						</ul>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<div class="columns large-4 medium-4">
			<div class="panel">
				<?php echo get_the_post_thumbnail( $post->ID, 'medium', array('class'=>'program-logo',) ); ?>
				<h4 class="text-center" style="margin-top: 1rem;"><?php the_title(); ?></h4>
				<?php if( get_field('time_estimate') ) : ?>
				<p class="text-center">
					<?php echo ucfirst(get_field('type')); ?> Badge<br/>
					<?php
						while( has_sub_field('time_estimate') ) {
							$time_amt  = get_sub_field('time_amt');
							$time_unit = get_row_layout();
							if( $time_amt > 1 ) {
								echo $time_amt.' '.$time_unit.' to earn';
							} else {
								echo $time_amt.' '.substr($time_unit, 0, -1).' to earn';
							}
						}
					?>
				</p>
				<?php endif; ?>
			</div>
		</div>
	</div>

</div><!--end #content-->

<?php endwhile; // end of the loop. ?>
	
<?php get_footer(); ?>