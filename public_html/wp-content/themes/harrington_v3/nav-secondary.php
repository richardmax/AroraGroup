<nav class="span2 secondaryNav">
    <?php 
	
	$defaults = array(
		'theme_location'  => '',
		'menu'            => 'Side Menu',
		'container'       => 'div',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'nav nav-list',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
	);

	wp_nav_menu($defaults); ?>
</nav>


