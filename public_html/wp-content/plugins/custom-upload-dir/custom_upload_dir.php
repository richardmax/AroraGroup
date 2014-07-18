<?php
/*
 Plugin Name: Custom Upload Dir
 Plugin URI: http://wordpress.org/extend/plugins/custom-upload-dir/
 Description: Keeps your uploaded files organized in smart folder structures.
 Version: 3.4
 Author: Ulf Benjaminsson
 Author URI: http://www.ulfben.com/
 License: GPL2
 Text Domain: cud
 Domain Path: /lang
*/
add_action('plugins_loaded', 'cud_init');
register_activation_hook(__FILE__, 'cud_install_options');	
register_uninstall_hook(__FILE__, 'cud_delete_options');	
global $cud_filetype, $cud_rpc_id;
$cud_file_ext = ''; //file extension
function cud_init() {
	if(!defined('ULFBEN_DONATE_URL')){
		define('ULFBEN_DONATE_URL', 'http://www.amazon.com/gp/registry/wishlist/2QB6SQ5XX2U0N/105-3209188-5640446?reveal=unpurchased&filter=all&sort=priority&layout=standard&x=21&y=17');
	}
	load_plugin_textdomain('cud', false, dirname(plugin_basename(__FILE__ )).'/lang/');		 
	define('CUD_BASENAME', plugin_basename(__FILE__));
	add_filter('wp_handle_upload_prefilter', 'cud_pre_upload');	
	add_action('xmlrpc_call', 'cud_xmlrpc_call'); //to hook into upload_dir on remote client uploads.	
	add_filter('wp_handle_upload', 'cud_post_upload');	
	add_action('admin_init', 'cud_register_settings');
	add_action('admin_menu', 'cud_register_menu_item');
	add_filter('plugin_row_meta', 'cud_set_plugin_meta', 2, 10);	
}
function cud_register_menu_item() {		
	add_options_page('Custom Upload Dir Settings', 'Custom Upload Dir', 'manage_options', __FILE__, 'cud_option_page');
}
function cud_register_settings(){
	register_setting('cud-settings-group', 'custom_upload_dir', 'cud_sanitize_settings');
}
function cud_add_admin_footer(){ //shows some plugin info in the footer of the config screen.
	$plugin_data = get_plugin_data(__FILE__);
	printf('%1$s by %2$s (who <a href="'.ULFBEN_DONATE_URL.'">appreciates books</a>) :)<br />', $plugin_data['Title'].' '.$plugin_data['Version'], $plugin_data['Author']);		
}
function cud_set_plugin_meta($links, $file) { // Add a link to this plugin's settings page
	if($file == CUD_BASENAME) {
		return array_merge($links, array(sprintf( '<a href="%s/wp-admin/options-general.php?page=%s">%s</a>', get_option('siteurl'), CUD_BASENAME, __('Settings', 'cud'))));
	}
	return $links; 
}
function cud_delete_options(){	
	delete_option('custom_upload_dir');
}
function cud_install_options(){
	$defaults = array(
		'custom_upload_dir' => '',
		'all_parents' => true, 	//get parents even if post belongs only to leaf cat
		'flatten_hierarchy' => false, 	//don't split taxonomy hierarchies into subfolders (folders will be like: "categoryA-categoryB")
		'only_leaf_nodes' => false, //even if post belongs to entire tree, use only leaf node
		'only_base_nodes' => false,
		'test_ids'	=> '-1',
		'template' => ''
	);	
	$old = get_option('custom_upload_dir');
	if(is_array($old)){
		if(!empty($old['template'])){ //update deprecated placeholders to match permalink tags
			$newtags = array('%post_id%','%postname%','%author%','%monthnum%');
			$oldtags = array('%ID%', '%post_name%','%post_author%','%month%');
			$old['template'] = str_replace($oldtags, $newtags, $old['template']);
		}	
		$defaults = array_merge($defaults, $old);	
	}	
	if(is_array($defaults['test_ids'])){
		$defaults['test_ids'] = implode(',', $defaults['test_ids']);
	}
	update_option('custom_upload_dir', $defaults);
}

function cud_xmlrpc_call($call){
	if($call !== 'metaWeblog.newMediaObject'){return;}		
	global $wp_xmlrpc_server, $cud_rpc_id; //class-wp-xmlrpc-server.php	
	$data = $wp_xmlrpc_server->message->params[3];
	//$name = sanitize_file_name($data['name']);
	//$type = $data['type'];
	if(!empty($data['post_id'])){	//sometimes, an RPC call will include a post_id. If you're lucky. :/		
		$cud_rpc_id = (int) $data['post_id'];
	}else{
		$cud_rpc_id = '';
	}
	cud_pre_upload($data);//Q&D to avoid duplication of code. $data is not an element of $_FILES, but has "name" which is all we currently use.
}


//* @param array $file Reference to a single element of $_FILES. Call the function once for each uploaded file.
function cud_pre_upload($file){	
	global $cud_file_ext;	
	$cud_file_ext = '';
	if(!empty($file['name'])){
		$wp_filetype = wp_check_filetype($file['name']); 
		$cud_file_ext = (!empty($wp_filetype['ext'])) ? $wp_filetype['ext'] : '';
	}
	add_filter('upload_dir', 'cud_custom_upload_dir');
	return $file;
}

function cud_post_upload($fileinfo){	
	remove_filter('upload_dir', 'cud_custom_upload_dir');
	return $fileinfo;
}

/*
$path
	[path] - base directory and sub directory or full path to upload directory.
	[url] - base url and sub directory or absolute URL to upload directory.
	[subdir] - sub directory if uploads use year/month folders option is on.
	[basedir] - path without subdir.
	[baseurl] - URL path without subdir.
	[error] - set to false.

	[path] => C:\path\to\wordpress\wp-content\uploads\2010\05 
	[url] => http://example.com/wp-content/uploads/2010/05 
	[subdir] => /2010/05 
	[basedir] => C:\path\to\wordpress\wp-content\uploads 
	[baseurl] => http://example.com/wp-content/uploads 
	[error] => 
	//since WP 2.7, this filter is called for each attachment displayed in the gallery-view.
*/
/*
NOTE: since WP 3.3 this filter is only run between wp_handle_upload_prefilter and wp_handle_upload - thus I removed the
checks for redundant calls when viewing the media library. 
*/
function cud_custom_upload_dir($path){		
	if(!empty($path['error'])) { return $path; } //error; do nothing.	
	$customdir = cud_generate_dir();
	$path['path'] 	 = str_replace($path['subdir'], '', $path['path']); //remove default subdir (year/month)
	$path['url']	 = str_replace($path['subdir'], '', $path['url']);		
	$path['subdir']  = $customdir;
	$path['path'] 	.= $customdir; 
	$path['url'] 	.= $customdir;	
	return $path;
}

function cud_generate_dir(){	
	global $post, $post_id, $current_user, $cud_file_ext, $cud_rpc_id;	
	$post_id = (!empty($post_id) ? $post_id : (!empty($_REQUEST['post_id']) ? $_REQUEST['post_id'] : (!empty($cud_rpc_id) ? $cud_rpc_id : '')));
	
	if(empty($post) || (!empty($post) && is_numeric($post_id) && $post_id != $post->ID)){ 
		$post = get_post($post_id);
	}
	$customdir = get_option('custom_upload_dir');
	$customdir = $customdir['template'];
	
	//defaults
	$user_id = $post_type = $post_name = $author = '';
	$time = (!empty($_SERVER['REQUEST_TIME'])) ? $_SERVER['REQUEST_TIME'] : (time() + (get_option('gmt_offset')*3600));	
	$user_id = (is_user_logged_in() && !empty($current_user)) ? $current_user->ID : '';		
	if(empty($user_id)){	
		$current_user = wp_get_current_user();
		if($current_user instanceof WP_User){
			$user_id = $current_user->ID;
		}
	}
	if(!empty($post)){ 		
		$post_id = $post->ID;
		$time = ($post->post_date == '0000-00-00 00:00:00') ? $time : strtotime($post->post_date);	
		$post_type = $post->post_type;
		$author = $post->post_author; 
		$post_name = (!empty($post->post_name)) ? $post->post_name : (!empty($post->post_title) ? sanitize_title($post->post_title) : $post_id);	
	}else{		
		$post_id = '';
	}	
	
	$date = explode(" ", date('Y m d H i s', $time));			
	$tags = array('%post_id%','%postname%','%post_type%','%year%','%monthnum%','%month%', '%day%','%hour%','%minute%','%second%', '%file_ext%');
	$replace = array($post_id, $post_name, $post_type, $date[0], $date[1], $date[1], $date[2], $date[3], $date[4], $date[5], $cud_file_ext);
	
	$customdir = str_replace($tags,	$replace, $customdir); //do all cheap replacements in one go.
	$customdir = str_replace('%permalink%', 	cud_get_permalink($post_id),$customdir);
	$customdir = str_replace('%author%', 		cud_get_user_name($author), $customdir);
	$customdir = str_replace('%current_user%', 	cud_get_user_name($user_id),$customdir);
	$customdir = str_replace('%parent_name%', 	cud_get_parent_slug($post),	$customdir);	
	$customdir = str_replace('%post_category%', cud_get_category_name($post_id), 	$customdir);/*DEPRECATED SINCE 3.0.3*/
	$customdir = str_replace('%post_categories%',cud_get_categories_name($post_id), $customdir);/*DEPRECATED SINCE 3.0.3*/
			
	//Now, let's deal with taxonomies (category, tags, custom)	
	//hopefully this also catches the permalink tag "%tag%". I've only tested with the 'post_tags'
	$matches = array();
	if(preg_match_all('/%(.*?)%/s', $customdir, $matches) == true){
		for($i = 0; $i < count($matches[0]); $i++){
			//$prepend = $use_taxonomy_name ? $matches[1][$i].'/' : '';
			$customdir = str_replace($matches[0][$i], cud_get_taxonomies($post_id, $matches[1][$i]), $customdir);
		}
	}	
	$customdir = cud_leadingslashit($customdir); //for good measure.
	$customdir = untrailingslashit($customdir);	
	while(strpos($customdir, '//') !== false){
		$customdir = str_replace('//', '/', $customdir); //avoid duplicate slashes.
	}	
	return $customdir;
}
//ripped wp_upload_dir to generate a basepath preview on the admin page.
function cud_wp_upload_dir($time = null){	
	$upload_path = trim( get_option( 'upload_path' ) );
	$basedir = '';
	if ( empty( $upload_path ) || 'wp-content/uploads' == $upload_path ) {
		$basedir = WP_CONTENT_DIR . '/uploads';
	} elseif ( 0 !== strpos( $upload_path, ABSPATH ) ) {
		// $basedir is absolute, $upload_path is (maybe) relative to ABSPATH
		$basedir = path_join( ABSPATH, $upload_path );
	} else {
		$basedir = $upload_path;
	}	
	// Obey the value of UPLOADS. This happens as long as ms-files rewriting is disabled.
	// We also sometimes obey UPLOADS when rewriting is enabled -- see the next block.
	if ( defined( 'UPLOADS' ) && ! ( is_multisite() && get_site_option( 'ms_files_rewriting' ) ) ) {
		$basedir = ABSPATH . UPLOADS;		
	}
	// If multisite (and if not the main site in a post-MU network)
	if ( is_multisite() && ! ( is_main_site() && defined( 'MULTISITE' ) ) ) {
		if ( ! get_site_option( 'ms_files_rewriting' ) ) {
			// If ms-files rewriting is disabled (networks created post-3.5), it is fairly straightforward:
			// Append sites/%d if we're not on the main site (for post-MU networks). (The extra directory
			// prevents a four-digit ID from conflicting with a year-based directory for the main site.
			// But if a MU-era network has disabled ms-files rewriting manually, they don't need the extra
			// directory, as they never had wp-content/uploads for the main site.)
			if ( defined( 'MULTISITE' ) )
				$ms_dir = '/sites/' . get_current_blog_id();
			else
				$ms_dir = '/' . get_current_blog_id();

			$basedir .= $ms_dir;
		} elseif ( defined( 'UPLOADS' ) && ! ms_is_switched() ) {
			// Handle the old-form ms-files.php rewriting if the network still has that enabled.
			// When ms-files rewriting is enabled, then we only listen to UPLOADS when:
			//   1) we are not on the main site in a post-MU network,
			//      as wp-content/uploads is used there, and
			//   2) we are not switched, as ms_upload_constants() hardcodes
			//      these constants to reflect the original blog ID.
			//
			// Rather than UPLOADS, we actually use BLOGUPLOADDIR if it is set, as it is absolute.
			// (And it will be set, see ms_upload_constants().) Otherwise, UPLOADS can be used, as
			// as it is relative to ABSPATH. For the final piece: when UPLOADS is used with ms-files
			// rewriting in multisite, the resulting URL is /files. (#WP22702 for background.)

			if ( defined( 'BLOGUPLOADDIR' ) )
				$basedir = untrailingslashit( BLOGUPLOADDIR );
			else
				$basedir = ABSPATH . UPLOADS;			
		}
	}	
	return $basedir;
}


function cud_get_permalink($post_id){
	$url = get_permalink($post_id);
	return ($url) ? str_replace(get_option('siteurl'), '', $url) : '';
}
function cud_get_parent_slug($post){
	if(empty($post)){return '';}
	$parent_slug = '';
	if($post->post_parent) {
		$parent_slug = basename(get_permalink($post->post_parent));
	} elseif (get_post_type_object($post->post_type)->rewrite["slug"]) {
		$parent_slug = (get_post_type_object($post->post_type)->rewrite["slug"]);
	} 
	return $parent_slug;
}
function cud_get_taxonomies($post_id, $taxonomy, $count = -1){ //deals with categories, tags or whatever else is in the terms table.
	if($post_id === ''){return '';}
	$terms = wp_get_object_terms($post_id, $taxonomy, array('orderby' => 'slug', 'order' => 'ASC', 'fields' => 'all')); 
	if(!is_array($terms)){return '';}
	$options = get_option('custom_upload_dir');	
	if($options['all_parents'] && !$options['only_leaf_nodes']){
		$terms = cud_get_parents($terms, $taxonomy);	
	}
	if($count > 0){
		$terms = array_slice($terms, 0, $count);
	}	
	$levels = array(0 => $terms);
	if(is_taxonomy_hierarchical($taxonomy)){
		$levels = cud_sort_by_levels($terms, $taxonomy);		
		if($options['only_leaf_nodes']){
			$levels = cud_find_leafs($levels);
		}else if($options['only_base_nodes']){
			$levels = array_slice($levels, 0, 1); //get rid of all levels beyond the first.
		}			
	}
	return cud_build_term_path($levels, $taxonomy, $options['flatten_hierarchy']);
}
function cud_build_term_path($levels, $taxonomy, $flatten_hierarchy){
	$path = '';	
	foreach($levels as $level => $terms){	
		$path .= implode('-', array_unique(wp_list_pluck($terms, 'slug'))) . '/';			
	}	
	$path = untrailingslashit($path);	
	if($flatten_hierarchy){
		$path = str_replace('/', '-', $path);
	}
	return $path;
}
function cud_find_leafs($levels /* array(0 => array(terms), [...], n => array(terms)) */){ 
	$parents = array(); 
	$leafs = array(); 
	$levels = array_reverse($levels);
	foreach($levels as $level => $terms){
		foreach($terms as $term){
			if(!in_array($term->term_id, $parents)){
				if(empty($leafs[$level])){
					$leafs[$level] = array();
				}
				$leafs[$level][] = $term;	
			}
			$parents[] = $term->parent;
		}
	}
	return array_reverse($leafs);
}
function cud_get_parents($terms, $taxonomy){	
	$hierarcy = array();
	foreach($terms as $term){
		$parent_ids = get_ancestors($term->term_id, $taxonomy);
		$parents = array();
		if(is_array($parent_ids)){
			foreach($parent_ids as $id){
				$parents[] = get_term($id, $taxonomy);
			}				
		}
		$parents[] = $term;
		$hierarcy = array_merge($hierarcy, $parents);
	}
	return $hierarcy;
}
function cud_sort_by_levels($terms, $taxonomy){
	$levels = array();
	foreach($terms as $term){
		$level = count(get_ancestors($term->term_id, $taxonomy));
		if(empty($levels[$level])){
			$levels[$level] = array();
		}
		$levels[$level][] = $term; 
	}		
	ksort($levels);
	return $levels;
}
function cud_get_term_parents($term_id, $taxonomy) { /*UNUSED*/
	$parent_ids = &get_ancestors($term_id, $taxonomy);
	if(!is_array($parent_ids)){	return '';}	
	$terms = wp_get_object_terms($parent_ids, $taxonomy); //let's hope get_objects returns them in the same order as IDs are given.
	if(!is_array($terms)){return '';}
	$terms = wp_list_pluck($terms, 'slug'); /*slug is the same as category_nicename*/
	return implode('/', $terms);
}
function cud_get_user_name($user_id){
	if(!is_numeric($user_id)) {return '';}
	$user = get_userdata($user_id);
	if(!$user){return '';}
	return sanitize_title($user->user_nicename);//$user->display_name
}
function cud_get_category_name($post_id){ /*DEPRECATED SINCE 3.0.3*/
	if($post_id === ''){return '';}
	$categories = get_the_category($post_id);
	if(!$categories){return '';}
	//usort($categories, '_usort_terms_by_ID'); // order by ID	to match WordPress usage in permalinks
	return $categories[0]->category_nicename;
}
function cud_get_categories_name($post_id){ /*DEPRECATED SINCE 3.0.3*/
	if($post_id === ''){return '';}
	$categories = get_the_category($post_id);
	if(!$categories){return '';}	
	$cats = array();
	foreach($categories as $cat){
		$cats[] = $cat->category_nicename;
	}	
	return implode('-', $cats);
}
function cud_leadingslashit($s){	
	return ($s && $s[0] !== '/') ? '/'.$s : $s;
}
function cud_sanitize_settings($options){
	if(empty($options)){return;}
	if(!isset($_REQUEST['submit'])){	
		return;
	}	
	update_option('uploads_use_yearmonth_folders', isset($options['wp_use_yearmonth']));
	$clean_options = array();
	$clean_options['test_ids'] = $options['test_ids'];
	$options['template'] = cud_leadingslashit($options['template']);
	if(get_option('uploads_use_yearmonth_folders') && stripos($options['template'], '/%year%/%monthnum%') !== 0){
		$options['template'] = '/%year%/%monthnum%'.$options['template'];
	}	
	$clean_options['template'] = preg_replace('/[^a-z0-9-%\/-\_]/','-',$options['template']); //allow only alphanumeric, '%', '_' and '/'	
	while(strpos($clean_options['template'], '//') !== false){
		$clean_options['template'] = str_replace('//', '/', $clean_options['template']); //avoid duplicate slashes.
	}
	$clean_options['only_leaf_nodes'] = !empty($options['only_leaf_nodes']);
	$clean_options['only_base_nodes'] = (!empty($options['only_base_nodes'])) && !$clean_options['only_leaf_nodes'];
	$clean_options['flatten_hierarchy'] = !empty($options['flatten_hierarchy']);
	$clean_options['all_parents'] = (!empty($options['all_parents'])) && !$clean_options['only_leaf_nodes'];	
	//print_r($options);
	return $clean_options;
}

function cud_option_page() {
	if(!function_exists('current_user_can') || !current_user_can('manage_options') ){
		die(__('Cheatin&#8217; uh?'));
	}
	add_action('in_admin_footer', 'cud_add_admin_footer');
	$options = get_option('custom_upload_dir');
	if(!isset($options['all_parents'])){
		cud_install_options(); //for some reason the installations sometimes fail this step
	}
	$options = get_option('custom_upload_dir');
	
	echo '<script type=\'text/javascript\'>		
		jQuery(document).ready(function(){
			var path = jQuery("#template");
			jQuery("div.updated").delay(2000).slideUp("slow");
			jQuery(".template").click(function(){								
				var sep = (path.val().charAt(path.val().length-1) !== "/") ? "/" : ""; 
				path.val(path.val()+sep+jQuery(this).html());									
			});
		});	</script>';
	$placeholder = array(
		'file_ext'			=> __('The file extension', 'cud'),
		'post_id' 			=> __('The post ID', 'cud'),
		'author' 			=> __('The post author', 'cud'),
		//'post_category' 	=> __('The post\'s first category', 'cud'),
		//'post_categories'	=> __('All the post\'s categories', 'cud'),
		//'post_status' 		=> __('The post status (publish|draft|private|static|object|attachment|inherit|future)', 'cud'),
		'postname' 		=> __('The post\'s URL slug', 'cud'),		
		'parent_name' 		=> __('The parent URL slug', 'cud'),
		'post_type' 		=> __('(post|page|attachment)', 'cud'),
		'year'				=> __('The post\'s year (YYYY)', 'cud'),
		'monthnum'			=> __('The post\'s month (MM)', 'cud'),
		'day'				=> __('The post\'s day (DD)', 'cud'),
		'permalink'			=> __('Match your blog\'s permalink structure', 'cud'),
		'current_user'		=> __('The currently logged in user', 'cud'),
		'category'			=> __('The post\'s categories (see: Taxonomies)', 'cud'),
		'post_tag'			=> __('The post\'s tags (see: Taxonomies)', 'cud'),		
	);		
?>
<div class="wrap">
<?php include_once(plugin_dir_path(__FILE__).'about.php'); ?>
<div id="about" style="clear: both;margin-top: 10px;">
	<h3><?php esc_html_e('Taxonomies:');?></h3>
	<p>A taxonomy is a way to group things together; <code>category</code> and <code>post_tag</code> are built in taxanomies, 
	but you can use <a href="http://codex.wordpress.org/Taxonomies">your own</a> too.<br /><br />
	Some taxonomies are hierarchical (think: categories) and some are not (tags). 
	This is how Custom Upload Dir handles the different scenarios:</pp>		
	
	<p>Hierarchies are turned into folders<br />
	<code>/operating-systems<span style="color:red;">/</span>linux<span style="color:red;">/</span>debian</code></p>
	
	<p>'Flat filesystem' ignores hierarchies:<br />
	<code>/operating-systems<span style="color:red;">-</span>linux<span style="color:red;">-</span>debian</code></p>
	
	<p title="<?php esc_attr_e('Only use the leaf node of the hierarchy.');?>">'Ignore parents': <code>/debian</code></p>
	
	<p>'Ignore children': <code>/operating-systems</code></p>
	
	<p>Same-level terms are sorted (alphabet) in folder names:<br />
	<code>/operating-systems/linux/<span style="color:red;">debian-redhat-ubuntu</span></code></p>
</div> 
<form method="post" action="options.php">
<table>
	<?php settings_fields('cud-settings-group'); ?>
	<tr>
		<td><h2><?php esc_html_e('Customize upload directory:');?></h2></td>
	</tr>
	<tr>
		<td class="field_info" title="<?php esc_attr_e('Add placeholders to create a template for the filestructure you\'d like. Separate folders with \'/\'.');?>" ><label for="template"><?php esc_html_e('Build a path template:');?></label></td>
		<td><input id="template" name='custom_upload_dir[template]' type='text' class='code' value='<?php esc_attr_e($options['template']); ?>' size='60' title="<?php esc_attr_e('Add placeholders to create a template for the filestructure you\'d like. Separate folders with \'/\'.');?>"/></td>
	</tr>	
	<tr>
		<td class="field_info" valign="top" title="<?php esc_attr_e('These are replaced with specific values when you upload something.');?>">Use any of these placeholders:</td>
		<td valign="top">
			<ul>
			<?php 
			foreach($placeholder as $key => $description){
				printf('<li><code class="template">%%%1$s%%</code> => %2$s</li>', esc_html($key), esc_html($description));
			}				
			?>			
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>		
	<tr>
		<td valign="top" class="field_info">&nbsp;</td>
		<td valign="top"><input name='custom_upload_dir[wp_use_yearmonth]' type='checkbox' id='wp_use_yearmonth' <?php checked(get_option('uploads_use_yearmonth_folders'), 1); ?> /><label for='wp_use_yearmonth' title="<?php esc_attr_e('These are built-in settings found at WordPress \'Media\' screen. Feel free to edit them here too.');?>"><?php esc_html_e('Organize my uploads into month- and year-based folders','cud'); ?></label></td>			
	</tr>
	<tr><td>&nbsp;</td></tr>	
	<tr>
		<td valign="top" class="field_info">Settings for taxonomy hierarchies:</td>
		<td valign="top"><input name='custom_upload_dir[all_parents]' type='checkbox' id='all_parents' value="1" <?php checked($options['all_parents'], 1); ?> />
			<label for='all_parents' title="<?php esc_attr_e('Get entire hierarchy even if post belongs only to leaf.');?>">
				<?php esc_html_e('Always get all parents','cud'); ?>
			</label>
		</td>					
	</tr>
	<tr>
		<td valign="top" class="field_info">&nbsp;</td>
		<td valign="top"><input name='custom_upload_dir[flatten_hierarchy]' type='checkbox' id='flatten_hierarchy' value="1" <?php checked($options['flatten_hierarchy'], 1); ?> />
			<label for='flatten_hierarchy' title="<?php esc_attr_e('Don\'t split taxonomy hierarchies into subfolders (folders will be like: "categoryA-categoryB").');?>">
				<?php esc_html_e('Flat filesystem (no subfolders for children)','cud'); ?>
			</label>
		</td>			
	</tr>
	<tr>
		<td valign="top" class="field_info">&nbsp;</td>
		<td valign="top"><input name='custom_upload_dir[only_leaf_nodes]' type='checkbox' id='only_leaf_nodes' value="1" <?php checked($options['only_leaf_nodes'], 1); ?> />
			<label for='only_leaf_nodes' title="<?php esc_attr_e('Even if post belongs to entire tree, use only leaf node.');?>">
				<?php esc_html_e('Ignore parents (use only leaf node of hierarcy)','cud'); ?>
			</label>
		</td>			
	</tr>
	<tr>
		<td valign="top" class="field_info">&nbsp;</td>
		<td valign="top"><input name='custom_upload_dir[only_base_nodes]' type='checkbox' id='only_base_nodes' value="1" <?php checked($options['only_base_nodes'], 1); ?> />
			<label for='only_base_nodes' title="<?php esc_attr_e('Even if post belongs to multiple levels of a hierarchy, use only the top most ancestor.');?>">
				<?php esc_html_e('Ignore children (use only the root of hierarchy)','cud'); ?>
			</label>
		</td>			
	</tr>			
	<tr>	
		<td valign="top" colspan="2" style="text-align:center;font-weight:bold;"><br><font color="orange"><?php esc_html_e('Try to always name and save your post before uploading attachments.');?></font></td>
	</tr>
	<tr>
		<td><h3><?php esc_html_e('Test it:');?></h3></td>
	</tr>
	<tr>		
		<?php			
			$test_ids = explode(',', $options['test_ids']);
			foreach($test_ids as $post_id){			
				$_REQUEST['post_id'] = $post_id;				
				$post_title = get_the_title($post_id);
				$url = get_permalink($post_id);
				if($post_title && $url){
					echo "<tr><td class=\"field_info\"><strong>Post {$post_id}, \"<a href='{$url}'>{$post_title}</a>\"</strong> would upload to:</td><td><code>".cud_wp_upload_dir().cud_generate_dir().'</code></td></tr>';
				}
			}		
		?>
		<td class="field_info"><label for="test_ids" title="<?php esc_attr_e('A comma separated list of post IDs that you\'d like to test.');?>"><?php esc_html_e('Enter some post IDs to test the path generated:');?></label></td>
		<td><input id="test_ids" name='custom_upload_dir[test_ids]' type='text' class='code' value='<?php esc_attr_e($options['test_ids']); ?>' size='30' title="<?php esc_attr_e('A comma separated list of post IDs that you\'d like to test.');?>"/></td>		
	</tr>	
 	<tr><td><p class="submit"><input type="submit" name="submit" value="<?php esc_html_e('Update Settings &raquo;','cud') ?>" /></p></td></tr>
</table>

</div>
</form>
<?php
}
?>