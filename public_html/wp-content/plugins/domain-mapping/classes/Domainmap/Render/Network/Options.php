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
 * Base class for tabbed paged.
 *
 * @category Domainmap
 * @package Render
 * @subpackage Network
 *
 * @since 4.0.0
 */
class Domainmap_Render_Network_Options extends Domainmap_Render_Network {

	/**
	 * Returns array of mapping variations.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 * @return array The array of mapping variations.
	 */
	private function _get_mapping_options() {
		return array(
			'user'     => __( 'domain entered by the user', 'domainmap' ),
			'mapped'   => __( 'mapped domain', 'domainmap' ),
			'original' => __( 'original domain', 'domainmap' ),
		);
	}

	/**
	 * Renders page header.
	 *
	 * @since 4.0.0
	 *
	 * @access protected
	 */
	protected function _render_header() {
		parent::_render_header();

		 if ( filter_input( INPUT_GET, 'saved', FILTER_VALIDATE_BOOLEAN ) ) :
			echo '<div id="message" class="updated fade">', __( 'Options updated.', 'domainmap' ), '</div>';
		endif;
	}

	/**
	 * Renders messages.
	 *
	 * @since 4.0.2
	 *
	 * @access private
	 */
	private function _render_messages() {
		// sunrise.php notification
		if ( !file_exists( WP_CONTENT_DIR . '/sunrise.php' ) ) {
			echo '<div class="domainmapping-info domainmapping-info-error">';
				printf(
					__( "Please copy the sunrise.php to %s and uncomment the %s setting in the %s file", 'domainmap' ),
					'<b>' . WP_CONTENT_DIR . '/sunrise.php</b>',
					'<code>SUNRISE</code>',
					'<b>' . ABSPATH . 'wp-config.php</b>'
				);
			echo '</div>';
		} else {
			if ( !defined( 'DOMAINMAPPING_SUNRISE_VERSION' ) || version_compare( DOMAINMAPPING_SUNRISE_VERSION, Domainmap_Plugin::SUNRISE, '<' ) ) {
				echo '<div class="domainmapping-info domainmapping-info-error">';
					printf(
						__( 'You use old version of %s file. Please, replace that file with new version which is located by following path: %s.', 'domainmap' ),
						'<b>' . WP_CONTENT_DIR . '/sunrise.php</b>',
						'<b>' . DOMAINMAP_ABSPATH . '/sunrise.php</b>'
					);
				echo '</div>';
			}
		}

		// SUNRISE constant notification
		if ( !defined( 'SUNRISE' ) ) {
			echo '<div class="domainmapping-info domainmapping-info-warning">';
				printf(
					__( "If you've not already added %s then please do so. If you added the constant be sure to uncomment this line: %s in the %s file.", 'domainmap' ),
					"<code>define('SUNRISE', 'on');</code>",
					"<code>//define('SUNRISE', 'on');</code>",
					'<b>wp-config.php</b>'
				);
			echo '</div>';
		}

		// soft deleted
//		if ( defined( 'DOMAIN_CURRENT_SITE' ) ) {
//			$str = "<p><strong>" . __( "If you are having problems with domain mapping you should try removing the following lines from your wp-config.php file:.", 'domainmap' ) . "</strong></p>";
//			$str .= "<ul>";
//			$str .= "<li>" . "define( 'DOMAIN_CURRENT_SITE', '" . DOMAIN_CURRENT_SITE . "' );" . "</li>";
//			$str .= "<li>" . "define( 'PATH_CURRENT_SITE', '" . PATH_CURRENT_SITE . "' );" . "</li>";
//			$str .= "<li>" . "define( 'SITE_ID_CURRENT_SITE', 1 );" . "</li>";
//			$str .= "<li>" . "define( 'BLOG_ID_CURRENT_SITE', 1 );" . "</li>";
//			$str .= "</ul>";
//			$str .= "<p><strong>" . __( "Note: If your domain mapping plugin is WORKING correctly, then please LEAVE these lines in place.", 'domainmap' ) . "</strong></p>";
//
//			echo '<div class="domainmapping-info">', $str, '</div>';
//		}

		if ( !defined( 'DOMAINMAPPING_ALLOWMULTI' ) ) {
			echo '<div class="domainmapping-info">';
				printf(
					__( "If you want to allow your users to map multiple domains, then please add %s in the %s file.", 'domainmap' ),
					"<code>define( 'DOMAINMAPPING_ALLOWMULTI', 1 );</code>",
					'<b>wp-config.php</b>'
				);
			echo '</div>';
		}
	}

	/**
	 * Renders tab content.
	 *
	 * @since 4.0.0
	 *
	 * @access protected
	 */
	protected function _render_tab() {
		// messages
		$this->_render_messages();

		// options
		$this->_render_domain_configuration();
		$this->_render_administration_mapping();
		$this->_render_login_mapping();
		$this->_render_cross_autologin();
		$this->_render_domain_validation();
		$this->_render_pro_site();

		?><p class="submit">
			<button type="submit" class="button button-primary domainmapping-button">
				<i class="icon-ok icon-white"></i> <?php _e( 'Save Changes', 'domainmap' ) ?>
			</button>
		</p><?php
	}

	/**
	 * Renders domain configuration section.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 */
	private function _render_domain_configuration() {
		$ips = false;
		if ( function_exists( 'dns_get_record' ) && !empty( $this->basedomain ) && !defined( 'DM_SKIP_DNS_CHECK' ) ) {
			$host = parse_url( $this->basedomain, PHP_URL_HOST );
			$dns = @dns_get_record( $host, DNS_A );
			if ( is_array( $dns ) ) {
				$ips = wp_list_pluck( $dns, 'ip' );
			}
		}

		?><h4 class="domainmapping-block-header"><?php _e( 'Domain mapping configuration', 'domainmap' ) ?></h4>
		<p>
			<?php _e( "Enter the IP address users need to point their DNS A records at. If you don't know what it is, ping this site to get the IP address.", 'domainmap' ) ?>
			<?php _e( "If you have more than one IP address, separate them with a comma. This message is displayed on the Domain mapping page for your users.", 'domainmap' ) ?>
		</p>

		<?php if ( !empty( $ips ) ) : ?>
		<div class="domainmapping-info">
			<p><?php
				_e( 'Looks like we are able to resolve your DNS A record(s) for your main domain and fetch the IP address(es) assigned to it. You can use the following IP address(es) to enter in the <b>Server IP Address</b> field below:', 'domainmap' )
			?></p>
			<p>
				<b><?php echo implode( '</b>, <b>', $ips ) ?></b>
			</p>
		</div>
		<?php endif; ?>

		<div>
			<label><?php _e( "Server IP Address: ", 'domainmap' ) ?></label>
			<div>
				<input type="text" name="map_ipaddress" class="regular-text" value="<?php echo esc_attr( $this->map_ipaddress ) ?>">
			</div>
		</div>

		<p><?php _e( 'In case if you want to display your own instructions on the Domain Mapping page, then use the text area below to enter your instructions or leave it blank to show default ones.' ) ?></p>

		<textarea name="map_instructions" class="widefat" cols="150" rows="5"><?php echo esc_textarea( $this->map_instructions ) ?></textarea><?php
	}

	/**
	 * Renders admin mapping section.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 */
	private function _render_administration_mapping() {
		?><h4 class="domainmapping-block-header"><?php _e( 'Administration mapping', 'domainmap' ) ?></h4>
		<p>
			<?php _e( 'You can allow your members to access the administration area of your site through the domain they enter, you can also restrict it to the Mapped domain or the original domain (your website url):', 'domainmap' ) ?>
		</p>

		<ul class="domainmapping-compressed-list"><?php
			foreach ( $this->_get_mapping_options() as $map => $label ) :
				?><li>
					<label>
						<input type="radio" class="domainmapping-radio" name="map_admindomain" value="<?php echo $map ?>"<?php checked( $map, $this->map_admindomain ) ?>>
						<?php echo $label ?>
					</label>
				</li><?php
			endforeach;
		?></ul><?php
	}

	/**
	 * Renders login mapping section.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 */
	private function _render_login_mapping() {
		?><h4 class="domainmapping-block-header"><?php _e( 'Login mapping', 'domainmap' ) ?></h4>
		<p>
			<?php _e( 'How should your members access the login page of their website, this can be through the domain they enter, or restrict it to either the Mapped domain or the original domain (your website url):', 'domainmap' ) ?>
		</p>

		<ul class="domainmapping-compressed-list"><?php
			foreach ( $this->_get_mapping_options() as $map => $label ) :
				?><li>
					<label>
						<input type="radio" class="domainmapping-radio" name="map_logindomain" value="<?php echo $map ?>"<?php checked( $map, $this->map_logindomain ) ?>>
						<?php echo $label ?>
					</label>
				</li><?php
			endforeach;
		?></ul><?php
	}

	/**
	 * Renders cross-domain autologin settings.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 */
	private function _render_cross_autologin() {
		$selected = isset( $this->map_crossautologin ) ? (int)$this->map_crossautologin : 1;
		$options = array(
			1 => __( 'Yes', 'domainmap' ),
			0 => __( 'No', 'domainmap' ),
		);

		?><h4 class="domainmapping-block-header"><?php _e( 'Cross-domain autologin', 'domainmap' ) ?></h4>
		<p>
			<?php _e( "Would you like for your members to be logged into all sites within your network regardless of domain name:", 'domainmap' ) ?><br>
		</p>

		<ul class="domainmapping-compressed-list"><?php
			foreach ( $options as $option => $label ) :
				?><li>
					<label>
						<input type="radio" class="domainmapping-radio" name="map_crossautologin" value="<?php echo $option ?>"<?php checked( $option, $selected ) ?>>
						<?php echo $label ?>
					</label>
				</li><?php
			endforeach;
		?></ul><?php
	}

	/**
	 * Renders domain validation on map settings.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 */
	private function _render_domain_validation() {
		$selected = isset( $this->map_verifydomain ) ? (int)$this->map_verifydomain : 1;
		$options = array(
			1 => __( 'Yes', 'domainmap' ),
			0 => __( 'No', 'domainmap' ),
		);

		?><h4 class="domainmapping-block-header"><?php _e( "Verify domain's DNS settings", 'domainmap' ) ?></h4>
		<p>
			<?php _e( "Would you like to verify domain's DNS settings before they will be mapped by your members:", 'domainmap' ) ?><br>
		</p>

		<ul class="domainmapping-compressed-list"><?php
			foreach ( $options as $option => $label ) :
				?><li>
					<label>
						<input type="radio" class="domainmapping-radio" name="map_verifydomain" value="<?php echo $option ?>"<?php checked( $option, $selected ) ?>>
						<?php echo $label ?>
					</label>
				</li><?php
			endforeach;
		?></ul><?php
	}

	/**
	 * Renders pro site section.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 */
	private function _render_pro_site() {
		if ( !function_exists( 'is_pro_site' ) ) {
			return;
		}

		?><h4 class="domainmapping-block-header"><?php _e( 'Select Pro Sites Levels:', 'domainmap' ) ?></h4>
		<p><?php _e( 'Make this functionality only available to certain Pro Site levels', 'domainmap' ) ?></p>

		<ul class="domainmapping-compressed-list"><?php
			$levels = (array)get_site_option( 'psts_levels' );
			if ( !is_array( $this->map_supporteronly ) && !empty( $levels ) && $this->map_supporteronly == '1' ) :
				$keys = array_keys( $levels );
				$this->map_supporteronly = array( $keys[0] );
			endif;

			foreach ( $levels as $level => $value ) :
				?><li>
					<label>
						<input type="checkbox" class="domainmapping-radio" name="map_supporteronly[]" value="<?php echo $level ?>"<?php checked( in_array( $level, (array)$this->map_supporteronly ) ) ?>>
						<?php echo $level, ': ', esc_html( $value['name'] ) ?>
					</label>
				</li><?php
			endforeach;
		?></ul><?php
	}

}