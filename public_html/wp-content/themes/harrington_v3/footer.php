		</section> <!-- end content-inner -->
		</section> <!-- end content-wrapper -->
			<footer role="contentinfo" class="row-fluid content-wrapper">
			
				<section class="row-fluid footer-inner ">
		          <div id="widget-footer" class="clearfix row-fluid">
		            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer1') ) : ?>
		            <?php endif; ?>
		            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer2') ) : ?>
		            <?php endif; ?>
		            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer3') ) : ?>
		            <?php endif; ?>
		          </div>		
					<ul class="footer-menu">
						<li>
							<a href="http://www.theharrington.com/terms-and-conditions/" >Terms and Conditions</a>
						</li>
						<li>
							<span>Copyright @ The Harrington 2014</span>
						</li>
					</ul>
					<!-- <p class="attribution">&copy; <?php bloginfo('name'); ?></p> -->
				
				</section> <!-- end #inner-footer -->
				
			</footer> <!-- end footer -->
		
		</div> <!-- end wrapper -->
		

		
		<!--[if lt IE 7 ]>
  			<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
  			<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
		<![endif]-->
		
		<?php wp_footer(); // js scripts are inserted using this function ?>
	</body>

</html>