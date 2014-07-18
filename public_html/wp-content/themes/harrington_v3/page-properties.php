<?php get_header(); ?>
			
		<nav class=" span2 secondaryNav">
			<ul class="nav nav-list">
				<li class="active">
					<a href="../properties">All Buildings</a>
				</li>		
			</ul> 
			<?php wp_nav_menu(array('menu' => 'Side Menu')); ?>
		</nav>
		
		<section class="span10">
			<hgroup class="clearfix">
				<h1 class="span3" >All Buildings</h1>
				<div class="prop-filter" style="display:none">
                
                
                
                
                
              
                
                
                
                
                
                
                
                
                
                
                
                
                
					<div class="span4 room-type">
						<label for="room-type">Room Type</label>
						<select class="multiselect" multiple="multiple" id="" name="room-type">
							<option value="Studio">Studio</option>
							<option value="1">1 Bed</option>
							<option value="2">2 Bed</option>
							<option value="3">3 Bed</option>
							<option value="4">4 Bed</option>
							<option value="5">5 Bed</option>
						</select>
						<div class="btn-group">
							<!-- <button type="button" class="multiselect dropdown-toggle btn btn-default" data-toggle="dropdown" title="None selected" style="width: auto;">None selected <b class="caret"></b></button> -->
							<ul class="multiselect-container dropdown-menu">
								<li><a href="javascript:void(0);"><label class="checkbox"><input type="checkbox" value="Studio"> Studio</label></a></li>
								<li><a href="javascript:void(0);"><label class="checkbox"><input type="checkbox" value="1"> 1 Bed</label></a></li>
								<li><a href="javascript:void(0);"><label class="checkbox"><input type="checkbox" value="2"> 2 Bed</label></a></li>
								<li><a href="javascript:void(0);"><label class="checkbox"><input type="checkbox" value="3"> 3 Bed</label></a></li>
								<li><a href="javascript:void(0);"><label class="checkbox"><input type="checkbox" value="4"> 4 Bed</label></a></li>
								<li><a href="javascript:void(0);"><label class="checkbox"><input type="checkbox" value="5"> 5 Bed</label></a></li>
							</ul>
						</div>
					</div>
					<div class="span5 room-type" >
						<label for="stay-length">Length of Stay</label>
						<select class="multiselect" multiple="multiple" name="stay-length">
							<option value="2">Short Stay</option>
							<option value="3">Long Stay (28 days +)</option>
						</select>
						<div class="btn-group">
							<!-- <button type="button" class="multiselect dropdown-toggle btn btn-default" data-toggle="dropdown" title="None selected" style="width: auto;">None selected <b class="caret"></b></button> -->
							<ul class="multiselect-container dropdown-menu">
								<li><a href="javascript:void(0);"><label class="checkbox"><input type="checkbox" value="1"> Short stay</label></a></li>
								<li><a href="javascript:void(0);"><label class="checkbox"><input type="checkbox" value="2"> Long Stay (28 days +)</label></a></li>
							</ul>
						</div>
					</div>	
				</div>
			</hgroup>
			<ul class="clearfix thumbnails">
			<?php /*
				global $post;
				$query = new WP_Query(array('post_type'=>'apartments','ordeby'=>'meta_value','meta_keyname'=>'category','order'=>'ASC', 'meta_key' => 'overview', 'meta_value' => 'ticked', 'meta_compare' => '!='));
				function echo_first_image( $postID,$postTitle,$pT ) {
					$args = array(
						'numberposts' => 1,
						'order' => 'ASC',
						'post_mime_type' => 'image',
						'post_parent' => $postID,
						'post_status' => null,
						'post_type' => 'attachment',
					);

					$attachments = get_children( $args );
					
					if ( $attachments ) {
						foreach ( $attachments as $attachment ) {

							$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' )  ? wp_get_attachment_image_src( $attachment->ID, 'thumbnail' ) : wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );

							echo "<a class=\"thumbnail\" href=\"/properties/".$postTitle."\"> <img src=\"". wp_get_attachment_thumb_url( $attachment->ID ) ."\"><figcaption>$pT</figcaption></a>";
						}
					}
				}
				if ($query->have_posts()) while ($query->have_posts()) : $query->the_post();
				
				
					$cat = get_the_category($postID);
						foreach($cat as $category) {
							$this_category = $category->name;
						}
			$beds = get_post_meta($post->ID,'beds',true);
			$stay = get_post_meta($post->ID,'stay',true);
		
			?>		
				<li  class="<?php if (get_post_meta($post->ID,'available',true) == 'ticked') echo "avail";?> props" data-beds="<?php if ($beds != 'Studio') {echo $beds.' '.'Bed';}else{echo $beds;}?>" data-stay="<?php echo $stay; ?>">
						<a class="thumbnail" href="/properties/<?php echo  strtolower(str_replace(' ','-',$this_category)).'/'.urlencode($post->post_name);?>"><?php the_post_thumbnail('thumbnail');//echo_first_image($post->ID,$post->post_name,$post->post_title); ?>
						<figcaption><?php the_title();?></figcaption>
						</a>
				</li>
			<?php endwhile; */
			
			// wp_nav_menu2(array('menu' => 'Property page menu','walker' => new Propertypage_Walker));
			?>	
			</ul> 
			<ul id="gal" class="thumbnails nav">
			<?php 
			// wp_nav_menu3(array('menu' => 'Property page menu','walker' => new Propertypage_Walker));
			
			
			wp_nav_menu(array('menu' => 'Property page menu'));
			?>	
			<ul>
		</section>
			</script> 
					<!-- JS files moved from other pages into the footer - WG - 2014-02-05 -->	
			<script type="text/javascript" src="http://www.theharrington.com/v4/wp-content/themes/harrington/assets/js/bootstrap-multiselect.js"></script>	
			<script type="text/javascript" src="http://www.theharrington.com/v4/wp-content/themes/harrington/assets/js/properties.js"></script>
		
		<!-- END -->
<?php get_footer(); ?>