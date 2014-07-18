<?php
 /**
 * class Bootstrap_Walker_Nav_Menu()
 *
 * Extending Walker_Nav_Menu to modify class assigned to submenu ul element
 *
 * @author Rachel Baker
 * @author Mike Bijon (updates & PHP strict standards only)
 *
 **/
class Bootstrapwp_Walker_Nav_Menu extends Walker_Nav_Menu {


    /**
     * Opening tag for menu list before anything is added
     *
     *
     * @param array reference       &$output    Reference to class' $output
     * @param int                   $depth      Depth of menu (if nested)
     * @param array                 $args       Class args, unused here
     *
     * @return string $indent
     * @return array by-reference   &$output
     *
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {

        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }

    /**
     * @see Walker::end_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of page. Used for padding.
     */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
	
	// start function restore_lost_capabilities
	// function restore_lost_capabilities() {
    // 
	// 	global $wp_roles;
    // 
	// 	$caps_to_restore = [
	// 		'NextGEN Gallery overview',
	// 		'NextGEN Use TinyMCE',
	// 		'NextGEN Upload images',
	// 		'NextGEN Manage gallery',
	// 		'NextGEN Manage others gallery',
	// 		'NextGEN Manage tags',
	// 		'NextGEN Edit album',
	// 		'NextGEN Change style',
	// 		'NextGEN Change options',        
	// 		'NextGEN Attach Interface'
	// 		];    
    // 
	// 	$role = $wp_roles->get_role('administrator');    
	// 	foreach($caps_to_restore as $cap) {
	// 		if (!$role->has_cap($cap)) {
	// 			$role->add_cap($cap, true);
	// 		}
	// 	}
    // 
	// }
    // 
	// add_action('admin_init', 'restore_lost_capabilities');
	// end fucntion restore_lost_capabilities
}