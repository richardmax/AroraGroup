<?php
/**
 * Template Name: 3 Columns
 */
	get_header();
?>
<?php get_template_part('nav-secondary') ?>

<nav class="span4">
	<h1 class="title">Special offers</h1>
    <ul class="thumbnails nav">    
        
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
					  'post_type' => 'page',
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
						  'post_type'    => 'page',
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
          
     </ul>	
     
 
 	
</nav>
    
<article class="span6 contentarea">
    
    <?php
        while (have_posts()) : the_post(); ?>
            <h2><?php the_title(); ?></h2><?php
            the_content(); ?>
			
			 <div class="acf-map">
                    <?php 
                        $location = get_field('map', 58);
                        if( !empty($location) ): ?>
                            <div class="acf-map">
                                <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                            </div>
                    <?php endif; ?>
                </div>
                
                <?php get_template_part('map')  ?>
			
    <?php    endwhile; 
    ?>
    
</article>
    			


<?php get_footer(); ?>