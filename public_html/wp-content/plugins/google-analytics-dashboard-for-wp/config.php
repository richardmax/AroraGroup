<?php
/**
 * Author: Alin Marcu
 * Author URI: http://deconf.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
if (! class_exists ( 'GADASH_Config' )) {
	class GADASH_Config {
		public $options;
		public $country_codes;
		public $plugin_path, $plugin_url;
		public $allowed_html;
		
		public function __construct() {
			$this->getPluginPath ();
			
			// get plugin options
			$this->get_plugin_options ();
			
			// init security
			$allowed_html = array(
					'a' => array (
							'href' => array (),
							'title' => array (),
					),
					'br' => array (),
					'em' => array (),
					'strong' => array () 
			);
		}

		public function set_plugin_options($network_settings = false) {
			//Handle Network Mode
			if (is_multisite()){
				if ($this->options['ga_dash_network'] && (is_network_admin()) && $network_settings){
					$network_options['ga_dash_token'] = $this->options['ga_dash_token'];
					$network_options['ga_dash_refresh_token'] = $this->options['ga_dash_refresh_token'];					
					$network_options['ga_dash_apikey'] = $this->options['ga_dash_apikey'];
					$network_options['ga_dash_clientid'] = $this->options['ga_dash_clientid'];
					$network_options['ga_dash_clientsecret'] = $this->options['ga_dash_clientsecret'];
					$network_options['ga_dash_userapi'] = $this->options['ga_dash_userapi'];
					$network_options['ga_dash_network'] = $this->options['ga_dash_network'];
					$network_options['ga_dash_profile_list'] = $this->options['ga_dash_profile_list'];
					if (isset($this->options['ga_dash_tableid_network'])){
						$network_options['ga_dash_tableid_network'] = $this->options['ga_dash_tableid_network'];
					}	
					update_site_option ( 'gadash_network_options', json_encode ( $network_options ) );
				}
			}			
			
			if (current_user_can ( 'manage_options' )){
				update_option ( 'gadash_options', json_encode ( $this->options ) );
			}	
		}
		
		public function getPluginPath() {
			/*
			 * Set Plugin Path
			 */
			$this->plugin_path = dirname ( __FILE__ );
			$this->plugin_url = plugins_url ( "", __FILE__ );
		}

		private function get_plugin_options() {
			/*
			 * Get plugin options
			 */
			global $blog_id;
			/*
			 * Include Tools
			*/
			include_once ($this->plugin_path . '/tools/tools.php');
			$tools = new GADASH_Tools ();
			
			if (!get_option ( 'gadash_options' )){
				GADASH_Install::install();
			}
			$this->options = ( array ) json_decode ( get_option ( 'gadash_options' ) );
			
			//Maintain Compatibility
			$this->maintain_compatibility();
			
			//Handle Network Mode
			if (is_multisite()){
				$get_network_options = get_site_option('gadash_network_options');
				$network_options = ( array ) json_decode ( $get_network_options);
				if (isset($network_options['ga_dash_network']) && ($network_options['ga_dash_network'])){
					if (!$get_network_options){
						$network_options['ga_dash_token'] = '';
						$network_options['ga_dash_refresh_token'] = '';
						$network_options['ga_dash_apikey'] = '';
						$network_options['ga_dash_clientid'] = '';
						$network_options['ga_dash_clientsecret'] = '';
						$network_options['ga_dash_userapi'] = '';
					}else{
						$network_options = ( array ) json_decode ( $get_network_options);
					}
					
					if (!is_network_admin()){
						$network_options['ga_dash_profile_list']=array(0 => $tools->get_selected_profile ( $network_options ['ga_dash_profile_list'], $network_options ['ga_dash_tableid_network']->$blog_id ));
						$network_options['ga_dash_tableid_jail']=$network_options['ga_dash_profile_list'][0][1];
					}
					
					$this->options = array_merge($this->options, $network_options);
					
					//print_r($this->options);
					
				}
			}
		}
		
		private function maintain_compatibility(){
			
			if (!isset($this->options['ga_enhanced_links'])){
				$this->options['ga_enhanced_links'] = 0;
			}
			
			if (!isset($this->options['ga_enhanced_network'])){
				$this->options['ga_dash_network'] = 0;
			}			
			
			if (!isset($this->options['ga_dash_remarketing'])){
				$this->options ['ga_dash_remarketing'] = 0;
			}
			
			if (!is_array($this->options['ga_dash_access_front']) OR empty($this->options['ga_dash_access_front'])){
				$this->options['ga_dash_access_front'] = array();
				$this->options['ga_dash_access_front'][] = 'administrator';
			}

			if (!is_array($this->options['ga_dash_access_back']) OR empty($this->options['ga_dash_access_back'])){
				$this->options['ga_dash_access_back'] = array();
				$this->options['ga_dash_access_back'][] = 'administrator';
			}
			
			if (!is_array($this->options['ga_track_exclude'])){
				$this->options['ga_track_exclude'] = array();
			}			
			
		}
		
	}
}

if (!isset($GLOBALS ['GADASH_Config'])){
	$GLOBALS ['GADASH_Config'] = new GADASH_Config ();
}

	
