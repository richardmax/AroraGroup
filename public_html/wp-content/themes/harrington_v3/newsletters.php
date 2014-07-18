<?php
	/**
	 * Template Name: Newsletters
	 */
	 get_header();
?>

	<?php get_template_part('nav-secondary') ?>
    
       
  <nav class="span4">
		 <?php get_template_part('menu-filter-newsletters') ?>
     
</nav>
    
    
   
     
    <article class="span6 contentarea"> 

	

    
        <?php	
			$args = array(
				'post_type'    => 'newsletters',
				'post_status'  => 'publish',
			); 
                
			// the query
			$the_query = new WP_Query( $args );
	 		
		if ( $the_query->have_posts() ) : ?>

  			<!-- pagination here -->
  			<!-- the loop -->
  			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
  
			<?php 
				$classes2use = "filter-class-view ";
				$classes2use .= get_the_title(); 
            ?>
  
  			<li <?php post_class($classes2use); ?>>

   			<?php the_post_thumbnail('thumbnail'); ?>
		
				<div class='content-holder'>
    				<h2><?php the_title(); ?></h2>
                    <?php the_content(); ?>
                </div>
    		</li>
  		<?php endwhile; ?>
  
      <!-- end of the loop -->
      <!-- pagination here -->

	<?php wp_reset_postdata(); ?>
    
    <?php else:  ?>
      	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
    <?php endif; ?>
		
 

	<?php bootstrapwp_content_nav('nav-below');?>
    </article>
        
<?php get_footer(); ?>