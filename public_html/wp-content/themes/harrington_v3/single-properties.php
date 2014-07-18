<?php 
	get_header();
	if( $post->post_parent != 0 ) {
		// child page
		$is_child = true;
	
	} else {
		// top level page
		$is_child = false;
	}
?>

<?php get_template_part('nav-secondary') ?>
            
<nav class="propertyNav span4 available">
    <h1><?php the_title(); ?></h1>
    <ul id="gal" class="thumbnails">
    
    
    
    
    
    
    
    <?php	
	  	
		  $related_post_type = get_field('related_post_type');
		  
	  	  if($related_post_type == 'children' || $related_post_type == 'siblings'){
			  if($related_post_type == 'children'){
				  
				  //$pageid = get_page_by_title( 'Special Offer Overview' )->ID; 
				  $pageid = get_the_ID();
				  
			  }else if($related_post_type == 'siblings'){
				  
				  $mydirectparentarray = get_post_ancestors($post);
				  $mydirectparent = $mydirectparentarray[0];
				  $pageid = $mydirectparent; 
				  
			  }
			  
			  if($pageid){
			  
				  $pageids = array();
				  $childargs = array(
					  'hierarchical' => 1,
					  'child_of' => $pageid,
					  'parent' => -1,
					  'post_type' => 'properties',
					  'post_status' => 'publish',
				  ); 
				  
				  $pages = get_pages($childargs); 
				  
				  //print_r(array_values($pages));
				  
				  
				  if($pages){
					  
					  foreach($pages as $page){
					  	$pageids[] = $page->ID;
					  }
					  
					  $args = array(
						  'child_of'     => '',
						  'depth'        => 0,
						  'echo'         => 1,
						  'include'      => $pageids,
						  'post_type'    => 'properties',
						  'post_status'  => 'publish',
						  'sort_column'  => 'menu_order',
						  'title_li'     => __(''), 
						  'walker'       => new Thumbnail_walker(),
					   );
					   
					   if($pageids){
						   wp_list_pages($args);
					   }
					  
				  }else{
					   echo "no children";
				}
			
			  }else{
				  echo "no parent";
			  }
			  
		  }else{
			  echo "no related posts requested";
		  }
	  	 
     	?>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
		<!-- ?php
            
			if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
                echo '<li class="props  active"><a href="" class="thumbnail">';
                the_post_thumbnail('thumbnail');
                echo '<figcaption>' . get_the_title() . '</figcaption></a></li>';
            }
        
            $args = array(
                'child_of'     => $post->ID,
                'echo'         => 0,
                'post_type'    => 'properties',
                'post_status'  => 'publish',
                'sort_column'  => 'menu_order',
                'title_li'     => ''
            );
			
            $children = get_pages($args); 
            echo '<pre>' . print_r( $children, true ) . '</pre>';
            
            foreach ($children as $child) {
                echo '<li class="props"><a href="?p=' . ($child -> ID) . '" class="thumbnail">';
                echo get_the_post_thumbnail($child -> ID, 'thumbnail' );
                echo '<figcaption>' . $child -> post_title . '</figcaption></a></li>';
            }
            
        ? -->
	</ul>
</nav> 
                       
<article class="span6 prop contentarea">
    <section class="clearfix propImages row-fluid imagesToClone">
		<?php
            if( $is_child == true ) { ?>
                
                <div class='titlepaddding'></div>
        <?php }else{ ?>
			
			<div class='titlepaddding'></div>
            
		<?php } ?>
		
    	<ul class="thumbnails">
			<?php
                    $gallery = get_post_gallery( get_the_ID(), false );
					//echo '<pre>' . print_r(  $gallery, true ) . '</pre>';
					$galleryimages_ids = str_getcsv($gallery['ids']);

					foreach( $galleryimages_ids AS $img_id ) { ?>
                        <li class="imgs">
                        	<?php 
								$image_thumb_url = wp_get_attachment_thumb_url( $img_id );
								$image_url = wp_get_attachment_url( $img_id );
								$post_object = get_post( $img_id );        
								$caption = $post_object->post_excerpt;
							?>
                            <a rel="1" href="<?php echo $image_url; ?>" class="thumbnail noselect ngg-fancybox fancybox">
                                <img data-bigger="class=&quot;size-thumbnail" wp-image-64"="" alt="<?php echo $caption; ?>" src="<?php echo $image_thumb_url; ?>" width="150" height="150">
                                <figcaption><?php echo $caption; ?></figcaption>
                            </a>
                        </li>	
                    <?php } ?>
        </ul>
     
        <nav class="propContentNav">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#desc" class="tabs" data-toggle="tab">Description</a></li>
                <li class=""><a href="#spec" class="tabs" data-toggle="tab">Specifications</a></li>
                <li class=""><a href="#brochure" class="tabs" data-toggle="tab">Brochure</a></li>
                <?php
                    if( $is_child == true ) { ?>
                        <li class=""><a href="#floorplans" class="tabs" data-toggle="tab">Floorplans</a></li>
                <?php 
                    } else { ?>
                        <li class=""><a href="#map" class="tabs" data-toggle="tab">Map</a></li>
                <?php } ?>  
            </ul>
        </nav>
        
        <div class="tab-content">
            <div class="tab-pane active" id="desc">				
                <?php the_field('description'); ?>    
            </div>
            <div class="fade tab-pane" id="spec">
                <?php the_field('specifications'); ?>
            </div>
            <div class="fade tab-pane" id="brochure">
                <?php 	
					$brochure_download = get_field('brochure_download');
					if( !empty($brochure_download)){ ?>
							<a href="<?php echo $brochure_download; ?>" >Download Brochure</a>
				<?php } ?>
            </div>
             
            <?php if( $is_child == true ) { ?>
                <div class="fade tab-pane" id="floorplans">
                
                	<?php 
						$floorplan_image = get_field('floorplan_image');
						if( !empty($floorplan_image)){ ?>
							<img src="<?php echo $floorplan_image; ?>" alt="Floorplan" />
                    		<br>
					<?php }  
						$floorplan_download = get_field('floorplan_download');
						if( !empty($floorplan_download)){ ?>
							<a href="<?php echo $floorplan_download; ?>" >Download Floorplan</a>
					<?php } ?>
					 
                </div>
            <?php 
                } else { ?>
                <div class="fade tab-pane" id="map">
                    <div class="acf-map">
                    <?php 
                        $location = get_field('map');
						
						echo "yeah ha:" . $location['lat'];
						
                        if( !empty($location) ): ?>
                            <div class="acf-map">
                                <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                            </div>
                    <?php endif; ?>
                </div>
                
                <?php get_template_part('map') ?>
                
           </div>
   <?php } ?>
</div>
</section>		
</article>
<?php get_footer(); ?>