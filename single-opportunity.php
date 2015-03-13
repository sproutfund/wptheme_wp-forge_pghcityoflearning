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
				<ul class="inline-list" id="categorieslist" style="display: none;">
					<?php
						// process categories
						/*
						$categoriesHtml = "";
						$categoriesHtml .= '<!--'.print_r($workshop['categories'], true).'-->';
						$cat_array = array();
						foreach ($workshop['categories'] as $category ) {
							$categoriesHtml .= '<li><a target="_blank" href="/explore/?query=&page=0&cat_ids%5B%5D=' . $category['id'] . '" class="button secondary tiny radius">' . $category['name'] . '</a></li>';
							$cat_array[] = $category["id"];
						}
						$workshop['categoriesHtml'] = $categoriesHtml;
						echo $categoriesHtml;
						*/
					?>
				</ul>
			</div>
		</div>
		<div class="columns large-4 medium-4">
			<div class="panel">
				<?php echo get_the_post_thumbnail( $post->ID, 'medium', array('class'=>'program-logo',) ); ?>
			</div>
		</div>
	</div>
	
	<div class="row clearfix" data-equalizer>
		<div class="columns large-4 medium-4">
			<div class="panel" data-equalizer-watch>
				<h3>Time &amp; Place</h3>
				<?php
					// the below was copied from widgets/opportunity_info.php (date & time section)
					$has_datetime     = false;
					$datetime_info = get_datetime_info($post->ID);
					if (!empty($datetime_info)) $has_datetime = true; 
					if( $has_datetime ) {
						echo '<i class="fa fa-calendar left" style="font-size: 1.5rem; margin-top: .1rem;"></i>';
						echo '<ul class="no-bullet" style="margin-left: 2rem;">';
						echo '<li>';
						if( !empty($datetime_info[0]['print']) ) {
							if( $datetime_info[0]['print']['context'] != '' ) {
								echo $datetime_info[0]['print']['context'];
							}
							if( $datetime_info[0]['print']['dates'] != '' ) {
								echo $datetime_info[0]['print']['dates'];
							}
							echo '</li>';
							echo '</ul>';
							if( $datetime_info[0]['print']['times'] != '' ) {
								echo '<i class="fa fa-clock-o left" style="font-size: 1.5rem; margin-top: .1rem;"></i>';
								echo '<ul class="no-bullet" style="margin-left: 2rem;">';
								echo '<li>';
								echo $datetime_info[0]['print']['times'];
								echo '</li>';
								echo '</ul>';
							}
						}
					}
				?>
				<?php
					// the below was copied from widgets/contact.php (location section)
					$locations = get_locations($post->ID);
					if (!empty($locations)) { $locations = get_location_details($locations); }
					if(!empty($locations)) {
						$location_count = count($locations);
						foreach($locations as $location) {
							echo '<i class="fa fa-map-marker left" style="font-size: 1.5rem; margin-top: .1rem;"></i>';
							echo '<ul class="no-bullet" style="margin-left: 2rem;">';
							echo '<li>';
							if ($location['ID'] != $post->ID) {
								if ($location['part']) { echo $location['part'].', '; }
								$post_title = $location['name'];
								$post_link  = $location['link'];
								echo "<a href=\"$post_link\" style=\"font-weight: bold;\">$post_title</a><br/>";
							}
							echo $location['print']['break'];
							echo '</li>';							
							echo '</ul>';
						}
					}
				?>
			</div>
		</div>
		<div class="columns large-4 medium-4">
			<div class="panel" data-equalizer-watch>
				<?php
					// the below was copied from widgets/registration.php
					$has_registration = false;
					$registration_rows = get_field('registration');
					$registration_row_count = count($registration_rows);
					if ($registration_row_count) $has_registration = true; 
					if( $has_registration ) : 
				?>
				<?php
					$reg_required = '';
					$reg_eligibility = '';
					$reg_website_print = '';
					$reg_email_print = '';
					$reg_phone_print = '';
					$reg_notes_print = '';
					$registration_details = '';
					while(the_flexible_field('registration')) {
						if (get_row_layout() == 'status_requirements_eligibility') {
							$reg_status = get_sub_field('status');
							$reg_required = get_sub_field('registration_required');
							$reg_eligibility = get_sub_field('eligibility_info');
							if ($reg_eligibility != '') {
								$registration_details .= "<li class=\"sidebar_list_item\">$reg_eligibility</li>";
							}
						} elseif (get_row_layout() == 'website') {
							$reg_website = get_sub_field('url');
							$reg_website_descript = (get_sub_field('description') == '') ? clean_website_url(url_to_domain($reg_website)) : get_sub_field('description');
							$reg_website_print .= "<li class=\"icon-external-link\"><a href=\"$reg_website\">$reg_website_descript</a></li>";
						} elseif (get_row_layout() == 'email') {
							$reg_email = trim(get_sub_field('email'));
							$reg_email_descript = (get_sub_field('description') == '') ? 'Email' : get_sub_field('description');
							$reg_email_print .= c2c_obfuscate_email("<li class=\"icon-envelope\"><a href=\"mailto:$reg_email\">$reg_email_descript</a></li>");	
						} elseif (get_row_layout() == 'phone') {
							$reg_phone = clean_phone(get_sub_field('phone'));
							$reg_phone_descript = (get_sub_field('description') == '') ? 'Phone' : get_sub_field('description');
							$reg_email_print .= "<li class=\"sidebar_list_item\">$reg_phone_descript: $reg_phone</li>";
						} elseif (get_row_layout() == 'notes') {
							$reg_notes = get_sub_field('registration_notes');
							$reg_notes_print .= "<li class=\"sidebar_list_item\">$reg_notes</li>";
						}
					}
					if (has_term('funding-available', 'opportunity-type') || has_term('call-competition', 'opportunity-type')) {
						$registration_heading = 'Application ';
					} else {
						$registration_heading = 'Registration ';
					}
					$registration_details .= $reg_website_print.$reg_email_print.$reg_phone_print.$reg_notes_print;
					if (($reg_status == 'open') || ($reg_status == 'door')) {
						if ($reg_required == 'required') {
							$registration_heading .= 'Required';
						} elseif ($reg_required == 'none') {
							$registration_heading .= 'Not Required';
						} else { 
							$registration_heading .= 'Requested';
							if ($reg_status == 'door') {
								$registration_details = '<li class="sidebar_list_item">Registration available at the door</li>'.$registration_details;
							}
						}
					} elseif ($reg_status == 'future') {
						$registration_heading .= 'TBA';
						$registration_details = '<li class="sidebar_list_item">Registration information for this event/opportunity is not yet available</li>'.$registration_details;
					} elseif ($reg_status == 'invite') {
						$registration_heading .= 'by Invitation Only';
						$registration_details = '<li class="sidebar_list_item">Registration for this event is limited to invited guests</li>';
					} elseif ($reg_status == 'closed') {
						$registration_heading .= 'Closed';
						$registration_details = '<li class="sidebar_list_item">This event/opportunity is closed or sold-out</li>';
					}
					if( !empty($registration_details) ) :
				?>
				<h3><?php echo $registration_heading; ?></h3>
				<ul class="no-bullet">
					<?php echo $registration_details; ?>
				</ul>
				<?php endif; endif; ?>

				<?php
					// the below was copied from widgets/for_more_info.php
					$flexible_field_name = 'more_info';
					$rows = get_field($flexible_field_name);
					$row_count = count($rows);
					if($row_count > 0) :
				?>
				<h3>Learn More</h3>
				<ul class="no-bullet">
					<?php
						$more_infos = array();
						while(has_sub_field($flexible_field_name)) {
							$this_id = 0;
							$this_name = '';
							$this_icon = '';
							if (get_row_layout() == 'network_member') {
								$this_obj  = get_sub_field('member_obj');
								$this_id   = $this_obj->ID;
								$this_name = get_the_title($this_id); 
							} elseif (get_row_layout() == 'external_contact') {
								$this_name = get_sub_field('external_name'); 
								$this_icon = 'user';
							} elseif (get_row_layout() == 'website') {
								$this_name = get_sub_field('description');
								$this_icon = 'folder-open';
							}
							$this_email = (get_sub_field('email') != '') ? get_sub_field('email') : '';
							$this_phone = (get_sub_field('phone') != '') ? get_sub_field('phone') : '';
							$this_url   = (get_sub_field('url')   != '') ? get_sub_field('url')   : '';
							if( $this_url == 'http://') { $this_url = ''; }
							$more_infos[] = array(
								'id'			=> $this_id,
								'name'			=> $this_name,
								'email_address' => $this_email,
								'phone_number'  => $this_phone,
								'website'       => $this_url,
								'action'        => get_sub_field('action'),
								'description'   => get_sub_field('description'),
								'icon'			=> $this_icon,
							);
						}
						$i = 1;
						$more_info_count = count($more_infos);
						foreach( $more_infos as $more_info ) {
							$this_icon			= $more_info['icon'];
							$this_name			= $more_info['name'];
							$this_email_address = $more_info['email_address'];
							$this_phone_number  = $more_info['phone_number'];
							$this_website       = $more_info['website'];
							if( $more_info['id'] != 0 ) {
								$contact_details = get_contact_details($more_info['id']);
								if( ($this_email_address == '') && (isset($contact_details['email_address'][0]['data'])) ) {
									$this_email_address = $contact_details['email_address'][0]['data'];
								}
								if( ($this_phone_number == '') && (isset($contact_details['phone_number'][0]['data'])) ) {
									$this_phone_number = $contact_details['phone_number'][0]['data'];
								}
								if( ($this_website == '') && (isset($contact_details['website'][0]['data'])) ) {
									$this_website = $contact_details['website'][0]['data'];
								}
								sprout_print_sidebar_contactinfo(array(
									'type' => 'intlink', 
									'data' => get_permalink($more_info['id']), 
									'text' => $this_name, 
									'icon' => $this_icon,
								));
							} elseif ( $this_name != '' ) {
								sprout_print_sidebar_contactinfo(array(
									'type' => 'text', 
									'text' => $this_name,
									'icon' => $this_icon,
								));
							}
							if( $this_email_address != '' ) {
								sprout_print_sidebar_contactinfo(array(
									'type' => 'email_address', 
									'data' => $this_email_address, 
								));
							}
							if( $this_phone_number != '' ) {
								sprout_print_sidebar_contactinfo(array(
									'type' => 'phone_number', 
									'data' => $this_phone_number, 
								));
							}
							if( $this_website != '' ) {
								sprout_print_sidebar_contactinfo(array(
									'type' => 'website', 
									'href' => $this_website, 
								));
							}
							if ($i < $more_info_count-1) {
								echo '</ul><div class="divider shortcode-divider thick"></div><ul>';
								$i++;
							}
						}
					?>
				</ul>
				<?php endif; ?>
			</div>
		</div>
		<div class="columns large-4 medium-4">
			<div class="panel" data-equalizer-watch>
				<h3>Map</h3>
				<div id="map-canvas" style="padding: 0 !important; margin: 0 !important; height: 300px;"></div>
			</div>
		</div>
	</div>

	<div class="row clearfix" data-equalizer style="display:none;">
		<div class="columns large-4 medium-4">
			<div class="panel" data-equalizer-watch>
				<h3>Badges</h3>
				<ul class="no-bullet">
					<?php
						/**
						 * 
						 * BADGES
						 *
						 * $workshop['badges'] is an array of $badge arrays with the following elements:
						 *
						 * $badge['id'] (numeric)
						 * $badge['name'] (Badge title)
						 * $badge['description'] (BadgeKit: Description for Consumers)
						 * $badge['informal_description'](BadgeKit: Description for Earners)
						 * $badge['blurb'] (BadgeKit: Short Description)
						 * $badge['badge_type'] (skill, knowledge, community, showcase)
						 * $badge['image_url'] (image on BadgeKit.org)
						 * $badge['issue_count'] (# of times badge has been issued)
						 *
						**/
						/*
						$badgesHtml = "";
						$badgesHtml = '<!--'.print_r($workshop['badges'], true).'-->';
						if( $workshop['badges'] != null ) {
							$badges = array();
							foreach ($workshop['badges'] as $badge) {
								$itemHtml  = '<li class="badge">';
								$itemHtml .= '<img class="badge-mini" src="'.$badge['image_url'].'"/>';
								$itemHtml .= '<span data-tooltip class="has-tip" title="'.$badge['informal_description'].'">'.$badge['name'].'</span>';
								$itemHtml .= '</li>';
								$badges[] = $itemHtml;
							}
							shuffle($badges);
							for ( $i = 0; $i < 3; ++$i ) {
								$badgesHtml .= $badges[$i];
							}
							if( count($badges) > 3 ) {
								$badgesHtml .= '<li style="font-style: italic; clear: both; text-align: right; font-size: smaller;">and '.(count($badges)-3).' other badges available</li>';
							}
						} else {
							$badgesHtml = "<p>Coming Soon</p>";
						}
						$workshop['badgesHtml'] = $badgesHtml;
						echo $badgesHtml;
						*/
					?>
				</ul>
			</div>
		</div>
		<div class="columns large-8 medium-8">
			<div class="panel" data-equalizer-watch>
				<h3>Similar or Nearby</h3>
				<ul id="relatedByCategory" class="large-block-grid-3 medium-block-grid-3 small-block-grid-2">
					<?php
						// get related workshops by category ("similiar to this")
						/*$relatedByCategoryItemCountMax = 6;
						$relatedByCategoryHtml = "";
						if( count($cat_array) > 0 ) {
							//$searchterms = COL::search( $sQuery, $aTopics, $iMinAge, $iMaxAge, $bPrice, $aLocations, $iPage, $iPerPage, $latitude, $longitude, $distance );
							if ($workshop["latitude"]!=null && $workshop["longitude"]!=null) {
								$searchResults = COL::search( "", null, null, null, null, null, 0, $relatedByCategoryItemCountMax+1, $workshop["latitude"], $workshop["longitude"], "3km" );
							} else {
								$searchResults = COL::search( "", $cat_array, null, null, null, null, 0, $relatedByCategoryItemCountMax+1, null, null, "30km" );
							}

							if ( $searchResults['hits']['total'] >  0 ) {

								$items = "";
								$count = 0;
								foreach ( $searchResults['hits']['hits'] as $hit ) {

									if ( $count == $relatedByCategoryItemCountMax ) {
										break;
									}
									$sp = $hit['_source'];

									if ( !isset( $sp['logo_url'] ) || strlen( $sp['logo_url'] )==0 ) {
										$logo_url = 'http://cityoflearning-uploads.s3.amazonaws.com/default_logos/';
										if ( $sp['program_type']=='workshop' ) {
											$logo_url .= 'ws_';
										} else {
											$logo_url .= 'ev_';
										}
										if ( $sp["meeting_type"]=='online' ) {
											$logo_url .= 'on.png';
										} else {
											$logo_url .= 'f2f.png';
										}
										$sp['logo_url'] = $logo_url;
									}

									if ( $sp["id"] != $workshop["id"] ) {
										$items_format = '
											<li class="relatedworkshop">
												<img class="logo" src="%s" />
												<a href="/workshop-detail?id=%d" data-tooltip class="has-tip" title="%s">%s</a>
											</li>';
										$items .= 
											sprintf($items_format,
												$sp['logo_url'],
												$sp['id'],
												$sp['description'],
												$sp['name']
											);
										$count += 1;
									}
									
									$relatedByCategoryHtml = $items;

								}
							}
						}
						$workshop['relatedByCategoryHtml'] = $relatedByCategoryHtml;
						echo $relatedByCategoryHtml;*/
					?>
				</ul>
			</div>
		</div>
	</div>

	<div class="row clearfix" style="margin-top:2em;">
			<div class="columns text-center" >
				<p>Did you find what you were looking for? If not, then keep exploring!</p>
				<div class="clear text-center">
					<a href="/explore" class="large button radius center">Explore More</a>
				</div>					
			</div>  
		</div>
	</div>    

</div><!--end #content-->

<?php if(!empty($locations)) : $location = $locations[0]; ?>
<?php
	//print_r($location);
	if(!empty($location['name'])) { $marker_title .= $location['name'].', '; } else { $marker_title = ''; }
?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBZ5b_ROG8eqS9bogFLE1A7R8T3fBbc6Sw">
</script>
<script type="text/javascript" data-cfasync="false">
		
	var geocoder;
	var map;

	function initialize() {
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(40.464875,-79.935422);
		var mapOptions = {
			center: latlng,
			zoom: 12,
			streetViewControl: false
		}
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		
		// Get location and re-center map
		codeAddress('<?php echo $location['print']['comma']; ?>');		

		// Add transit info
		var transitLayer = new google.maps.TransitLayer();
		transitLayer.setMap(map);

		//$('#map-canvas').height($($('#relatedByCategory')).height());
	}
	
	function codeAddress(address) {
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location,
						title:"<?php echo $marker_title.$location['print']['comma']; ?>"
				});
			} else {
				alert('Geocode was not successful for the following reason: ' + status);
			}
		});
	}
	
	google.maps.event.addDomListener(window, 'load', initialize);
</script>

<?php endif;?>

<?php endwhile; // end of the loop. ?>
	
<?php get_footer(); ?>