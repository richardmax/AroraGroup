<?php acf_form_head(); ?>
<?php get_header(); /**
 * Template Name: Create Post
 */ ?>
 
	<div id="primary">
		<div id="content" role="main">
 
			
 <?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
 
				<?php acf_form(array(
					'post_id'	=> 'new',
					'field_groups'	=> array( 123 ),
					'submit_value'	=> 'Create a new event'
				)); ?>
 
			<?php endwhile; ?>
 
			
 
		</div><!-- #content -->
	</div><!-- #primary -->
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>