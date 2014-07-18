<?php
/**
 * Template Name: About
 */
	get_header();
?>

<?php get_template_part('nav-secondary') ?>
				
<article class="span10">
  <h1><?php the_title(); ?></h1>
  <div class="tab_wrap">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#first">The Harrington</a></li>
      <li class=""><a data-toggle="tab" href="#second">Our Exclusive Clients</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade active in" id="first">
        	<?php the_field('the_harrington'); ?>
      </div>
      <div class="tab-pane fade" id="second">
        	<?php the_field('our_exclusive_clients'); ?>
      </div>
    </div>
  </div>
 
  <?php get_template_part('carousel') ?>

</article>

<?php get_footer(); ?>