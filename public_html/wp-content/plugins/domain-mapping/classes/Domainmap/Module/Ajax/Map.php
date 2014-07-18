<?php

// +----------------------------------------------------------------------+
// | Copyright Incsub (http://incsub.com/)                                |
// | Based on an original by Donncha (http://ocaoimh.ie/)                 |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License, version 2, as  |
// | published by the Free Software Foundation.                           |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               |
// | MA 02110-1301 USA                                                    |
// +----------------------------------------------------------------------+

/**
 * The module responsible for handling AJAX requests sent at domain mapping page.
 *
 * @category Domainmap
 * @package Module
 * @subpackage Ajax
 *
 * @since 4.0.0
 */
class Domainmap_Module_Ajax_Map extends Domainmap_Module_Ajax {

	const NAME = __CLASS__;

	/**
	 * Constructor.
	 *
	 * @since 4.0.0
	 *
	 * @access public
	 * @param Domainmap_Plugin $plugin The instance of the Domainap_Plugin class.
	 */
	public function __construct( Domainmap_Plugin $plugin ) {
		parent::__construct( $plugin );

		// add ajax actions
		$this->_add_ajax_action( Domainmap_Plugin::ACTION_MAP_DOMAIN, 'map_domain' );
		$this->_add_ajax_action( Domainmap_Plugin::ACTION_UNMAP_DOMAIN, 'unmap_domain' );
		$this->_add_ajax_action( Domainmap_Plugin::ACTION_HEALTH_CHECK, 'check_health_status', true, true );
		$this->_add_ajax_action( Domainmap_Plugin::ACTION_HEARTBEAT_CHECK, 'check_heartbeat', true, true );
		$this->_add_ajax_action( Domainmap_Plugin::ACTION_SELECT_PRIMARY_DOMAIN, 'select_primary_domain' );
		$this->_add_ajax_action( Domainmap_Plugin::ACTION_DESELECT_PRIMARY_DOMAIN, 'deselect_primary_domain' );
		$this->_add_ajax_action( Domainmap_Plugin::ACTION_CHANGE_FRONTEND_REDIRECT, 'change_frontend_mapping' );

		// add wpengine compatibility
		if ( !has_action( 'domainmapping_added_domain' ) ) {
			$this->_add_action( 'domainmapping_added_domain', 'add_domain_to_wpengine' );
		}

		if ( !has_action( 'domainmapping_deleted_domain' ) ) {
			$this->_add_action( 'domainmapping_deleted_domain', 'remove_domain_from_wpengine' );
		}
	}

	/**
	 * Returns count of mapped domains for current blog.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 * @return int The count of already mapped domains.
	 */
	private function _get_domains_count() {
		return $this->_wpdb->get_var( 'SELECT COUNT(*) FROM ' . DOMAINMAP_TABLE_MAP . ' WHERE blog_id = ' . intval( $this->_wpdb->blogid ) );
	}

	/**
	 * Locates WPEngine API and loads it.
	 *
	 * @since 4.0.4
	 *
	 * @access private
	 * @return boolean TRUE if WPE_API has been located, otherwise FALSE.
	 */
	private function _locate_wpengine_api() {
		// if WPE_API doesn't exist, then try to locate it
		if ( !class_exists( 'WPE_API' ) ) {
			// if WPEngine is not defined, then return
			if ( !defined( 'WPE_PLUGIN_DIR' ) || !is_readable( WPE_PLUGIN_DIR . '/class-wpeapi.php' ) ) {
				return false;
			}

			include_once WPE_PLUGIN_DIR . '/class-wpeapi.php';
			// chec whether class has been loaded
			if ( !class_exists( 'WPE_API' ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Adds domain to WPEngine domains list when this domain is mapped to a blog.
	 *
	 * @since 4.0.4
	 * @action domainmapping_added_domain
	 *
	 * @access public
	 * @param string $domain The domain name to add.
	 */
	public function add_domain_to_wpengine( $domain ) {
		// return if we can't locate WPEngine API class
		if ( !$this->_locate_wpengine_api() ) {
			return;
		}

		// add domain to WPEngine
		$api = new WPE_API();

		// set the method and domain
		$api->set_arg( 'method', 'domain' );
		$api->set_arg( 'domain', $domain );

		// do the api request
		$api->get();
	}

	/**
	 * Removes domain from WPEngine domains list when this domain is unmapped
	 * from a blog.
	 *
	 * @since 4.0.4
	 * @action domainmapping_deleted_domain
	 *
	 * @access public
	 * @param string $domain The domain name to remove.
	 */
	public function remove_domain_from_wpengine( $domain ) {
		// return if we can't locate WPEngine API class
		if ( !$this->_locate_wpengine_api() ) {
			return;
		}

		// add domain to WPEngine
		$api = new WPE_API();

		// set the method and domain
		$api->set_arg( 'method', 'domain-remove' );
		$api->set_arg( 'domain', $domain );

		// do the api request
		$api->get();
	}

	/**
	 * Maps new domain.
	 *
	 * @since 4.0.0
	 *
	 * @access public
	 */
	public function map_domain() {
		self::_check_premissions( Domainmap_Plugin::ACTION_MAP_DOMAIN );

		$message = $hide_form = false;
		$domain = strtolower( trim( filter_input( INPUT_POST, 'domain' ) ) );
		if ( self::_validate_domain_name( $domain ) ) {

			// check if mapped domains are 0 or multi domains are enabled
			$count = $this->_get_domains_count();
			$allowmulti = defined( 'DOMAINMAPPING_ALLOWMULTI' );
			if ( $count == 0 || $allowmulti ) {

				// check if domain has not been mapped
				$blog = $this->_wpdb->get_row( $this->_wpdb->prepare( "SELECT blog_id FROM {$this->_wpdb->blogs} WHERE domain = %s AND path = '/'", $domain ) );
				$map = $this->_wpdb->get_row( $this->_wpdb->prepare( "SELECT blog_id FROM " . DOMAINMAP_TABLE_MAP . " WHERE domain = %s", $domain ) );

				if ( is_null( $blog ) && is_null( $map ) ) {
					$this->_wpdb->insert( DOMAINMAP_TABLE_MAP, array(
						'blog_id' => $this->_wpdb->blogid,
						'domain'  => $domain,
						'active'  => 1,
					), array( '%d', '%s', '%d' ) );

					if ( $this->_plugin->get_option( 'map_verifydomain', true ) == false || $this->_validate_health_status( $domain ) ) {
						// fire the action when a new domain is added
						do_action( 'domainmapping_added_domain', $domain, $this->_wpdb->blogid );

						// send success response
						ob_start();
						$row = array( 'domain' => $domain, 'is_primary' => 0 );
						Domainmap_Render_Site_Map::render_mapping_row( (object)$row );
						wp_send_json_success( array(
							'html'      => ob_get_clean(),
							'hide_form' => !$allowmulti,
						) );
					} else {
						$this->_wpdb->delete( DOMAINMAP_TABLE_MAP, array( 'domain' => $domain ), array( '%s' ) );
						$message = sprintf(
							'<b>%s</b><br><small>%s</small>',
							__( 'Domain name is unavailable to access.', 'domainmap' ),
							__( "We can’t access your new domain. Mapping a new domains can take as little as 15 minutes to resolve but in some cases can take up to 72 hours, so please wait if you just bought it. If it is an existing domain and has already been fully propagated, check your DNS records are configured correctly.", 'domainmap' )
						);
					}
				} else {
					$message = __( 'Domain is already mapped.', 'domainmap' );
				}
			} else {
				$message = __( 'Multiple domains are not allowed.', 'domainmap' );
				$hide_form = true;
			}
		} else {
			$message = __( 'Domain name is invalid.', 'domainmap' );
		}

		wp_send_json_error( array(
			'message'   => $message,
			'hide_form' => $hide_form,
		) );
	}

	/**
	 * Unmaps domain.
	 *
	 * @since 4.0.0
	 * @uses check_admin_referer() To avoid security exploits.
	 * @uses current_user_can() To check user permissions.
	 *
	 * @access public
	 */
	public function unmap_domain() {
		self::_check_premissions( Domainmap_Plugin::ACTION_UNMAP_DOMAIN );

		$show_form = false;
		$domain = strtolower( trim( filter_input( INPUT_GET, 'domain' ) ) );
		if ( self::_validate_domain_name( $domain ) ) {
			$this->_wpdb->delete( DOMAINMAP_TABLE_MAP, array( 'domain' => $domain ), array( '%s' ) );
			delete_transient( "domainmapping-{$domain}-health" );

			// check if we need to show form
			$show_form = $this->_get_domains_count() == 0 || defined( 'DOMAINMAPPING_ALLOWMULTI' );

			// fire the action when a domain is removed
			do_action( 'domainmapping_deleted_domain', $domain, $this->_wpdb->blogid );
		}

		wp_send_json_success( array( 'show_form' => $show_form ) );
	}

	/**
	 * Validates health status of a domain.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 * @param string $domain The domain name to validate.
	 * @return boolean TRUE if the domain name works, otherwise FALSE.
	 */
	private function _validate_health_status( $domain ) {
		$check = sha1( time() );

		switch_to_blog( 1 );
		$ajax_url = admin_url( 'admin-ajax.php' );
		$ajax_url = str_replace( parse_url( $ajax_url, PHP_URL_HOST ), $domain, $ajax_url );
		restore_current_blog();

		$response = wp_remote_request( add_query_arg( array(
			'action' => Domainmap_Plugin::ACTION_HEARTBEAT_CHECK,
			'check'  => $check,
		), $ajax_url ), array( 'sslverify' => false ) );

		return !is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) == 200 && preg_replace('/\W*/', '', wp_remote_retrieve_body( $response ) ) == $check ? 1 : 0;
	}

	/**
	 * Checks domain health status.
	 *
	 * @since 4.0.0
	 *
	 * @access public
	 */
	public function check_health_status() {
		self::_check_premissions( Domainmap_Plugin::ACTION_HEALTH_CHECK );

		$domain = strtolower( trim( filter_input( INPUT_GET, 'domain' ) ) );
		if ( !self::_validate_domain_name( $domain ) ) {
			wp_send_json_error();
		}

		$valid = $this->_validate_health_status( $domain );
		set_site_transient( "domainmapping-{$domain}-health", $valid, $valid ? WEEK_IN_SECONDS : 10 * MINUTE_IN_SECONDS );

		ob_start();
		Domainmap_Render_Site_Map::render_health_column( $domain );
		wp_send_json_success( array( 'html' => ob_get_clean() ) );
	}

	/**
	 * Checks heartbeat of the domain.
	 *
	 * @since 4.0.0
	 *
	 * @access public
	 */
	public function check_heartbeat() {
		echo filter_input( INPUT_GET, 'check' );
		exit;
	}

	/**
	 * Selects primary domain for current blog.
	 *
	 * @since 4.0.3
	 *
	 * @access public
	 */
	public function select_primary_domain() {
		self::_check_premissions( Domainmap_Plugin::ACTION_SELECT_PRIMARY_DOMAIN );

		if ( defined( 'DOMAINMAPPING_ALLOWMULTI' ) && filter_var( DOMAINMAPPING_ALLOWMULTI, FILTER_VALIDATE_BOOLEAN ) ) {
			// unset all domains
			$this->_wpdb->update(
				DOMAINMAP_TABLE_MAP,
				array( 'is_primary' => 0 ),
				array( 'blog_id' => $this->_wpdb->blogid, 'is_primary' => 1 ),
				array( '%d' ),
				array( '%d', '%d' )
			);

			// set primary domain
			$domain = filter_input( INPUT_GET, 'domain' );
			$this->_wpdb->update(
				DOMAINMAP_TABLE_MAP,
				array( 'is_primary' => 1 ),
				array( 'blog_id' => $this->_wpdb->blogid, 'domain' => $domain ),
				array( '%d' ),
				array( '%d', '%s' )
			);
		}

		wp_send_json_success();
		exit;
	}

	/**
	 * Deselects primary domain for current blog.
	 *
	 * @since 4.0.3
	 *
	 * @access public
	 */
	public function deselect_primary_domain() {
		self::_check_premissions( Domainmap_Plugin::ACTION_DESELECT_PRIMARY_DOMAIN );

		if ( defined( 'DOMAINMAPPING_ALLOWMULTI' ) && filter_var( DOMAINMAPPING_ALLOWMULTI, FILTER_VALIDATE_BOOLEAN ) ) {
			// deselect primary domains
			$this->_wpdb->update(
				DOMAINMAP_TABLE_MAP,
				array( 'is_primary' => 0 ),
				array( 'blog_id' => $this->_wpdb->blogid, 'is_primary' => 1, 'domain' => filter_input( INPUT_GET, 'domain' ) ),
				array( '%d' ),
				array( '%d', '%d', '%s' )
			);
		}

		wp_send_json_success();
		exit;
	}

	/**
	 * Changes frotn end mapping for current blog.
	 *
	 * @since 4.1.2
	 *
	 * @access public
	 */
	public function change_frontend_mapping() {
		self::_check_premissions( Domainmap_Plugin::ACTION_CHANGE_FRONTEND_REDIRECT );

		$mapping = strtolower( filter_input( INPUT_POST, 'mapping' ) );
		if ( !in_array( $mapping, array( 'user', 'mapped', 'original' ) ) ) {
			wp_send_json_error();
		}

		update_option( 'domainmap_frontend_mapping', $mapping );
		wp_send_json_success();
		exit;
	}

}