<div id="myCarousel" class="carousel slide">
  <div class="carousel-inner">
    <?php
		$post_object = get_field('carousel_element');
		if( $post_object ): 
			$carousel_id = $post_object->ID;
			$carousel_images = get_field( "carousel_images", $carousel_id ); 
		endif; 
		
		$firstRun = true;
		foreach ($carousel_images as &$carousel_image) {
    		$image_id =  $carousel_image -> ID;
			//echo $image_id;
			
			/*	
				// GET ALL IMAGE DATA 
				$gallery_images[] = wp_prepare_attachment_for_js(  $image_id );
				 echo '<pre>';
				print_r( $gallery_images );
				echo '</pre>';
				// GET ALL EXIF DATRE ETC = get_post_meta
			*/

			$attachment = get_post( $image_id );
			$title = $attachment->post_title;
			$caption = $attachment->post_excerpt;
			$description = $attachment->post_content;
			$alttext = get_post_meta($image_id, '_wp_attachment_image_alt', true);
			$image_attributes = wp_get_attachment_image_src( $image_id, 'carousel' );
	?>
    <div class="item <?php if($firstRun == true){echo 'active';} ?>"> <img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" alt="">
      <!-- div class="container">
            <div class="carousel-caption">
              <h1>Title: <?php echo $title; ?></h1>
              <h2>Caption: <?php echo $caption; ?></h2>
              <h3>Alt Text: <?php echo $alttext; ?></h3>
              <p class="lead"><?php echo $description; ?></p>
            </div>
          </div --> 
    </div>
    <?php $firstRun = false; } ?>
  </div>
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
</div>