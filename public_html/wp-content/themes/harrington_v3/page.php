<?php 
	get_header();
?>

<?php get_template_part('nav-secondary') ?>
				
<section class="span10">
	<?php
		while (have_posts()) : the_post();
			//echo $query->post_content;
			the_content();
		endwhile; 
	?>
</section>

<?php get_footer(); ?>