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
 * Renders map domain tab page.
 *
 * @category Domainmap
 * @package Render
 * @subpackage Site
 *
 * @since 4.0.0
 */
class Domainmap_Render_Site_Map extends Domainmap_Render_Site {

	/**
	 * Determines whether ability to map multi domains is enabled or not.
	 *
	 * @since 4.0.3
	 *
	 * @static
	 * @access protected
	 * @return boolean TRUE if multi domains mapping enabled, otherwise FALSE.
	 */
	protected static function _is_multi_enabled() {
		return defined( 'DOMAINMAPPING_ALLOWMULTI' ) && filter_var( DOMAINMAPPING_ALLOWMULTI, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Renders instructions how to configure DNS records.
	 *
	 * @since 4.0.0
	 *
	 * @access private
	 */
	private function _render_instructions() {
		if ( trim( $this->map_instructions ) != '' ) {
			?><p class="domainmapping-info"><?php echo $this->map_instructions ?></p><?php
			return;
		}

		$descriptions = array();
		$descriptions[] = __( 'If your domain name includes a sub-domain such as "blog" then you can add a CNAME for that hostname in your DNS pointing at this blog URL.', 'domainmap' );

		$map_ipaddress = isset( $this->map_ipaddress ) ? trim( $this->map_ipaddress ) : '';
		if ( !empty( $map_ipaddress ) ) {
			if ( strpos( $map_ipaddress, ',' ) ) {
				$descriptions[] = __( 'If you want to redirect a domain you will need to add multiple DNS "A" records pointing at the IP addresses of this server: ', 'domainmap' ) . "<strong>{$map_ipaddress}</strong>";
			} else {
				$descriptions[] = __( 'If you want to redirect a domain you will need to add a DNS "A" record pointing at the IP address of this server: ', 'domainmap' ) . "<strong>{$map_ipaddress}</strong>";
			}
		}

		?><p class="domainmapping-info"><?php echo implode( '<br>', $descriptions ) ?></p><?php
	}

	/**
	 * Renders tab content.
	 *
	 * @since 4.0.0
	 *
	 * @access protected
	 */
	protected function _render_tab() {
		$schema = defined( 'DM_FORCE_PROTOCOL_ON_MAPPED_DOMAIN' ) && DM_FORCE_PROTOCOL_ON_MAPPED_DOMAIN && is_ssl() ? 'https' : 'http';
		$form_class = count( $this->domains ) > 0 && !self::_is_multi_enabled() ? ' domainmapping-form-hidden' : '';
		$admin_ajax = esc_url( admin_url( 'admin-ajax.php' ) );

		$mapping = get_option( 'domainmap_frontend_mapping', 'mapped' );
		$mapping_types = array(
			'user'     => __( 'disabled and entered domain should be used', 'domainmap' ),
			'mapped'   => __( 'directed to mapped (primary) domain', 'domainmap' ),
			'original' => __( 'directed to original domain', 'domainmap' ),
		);

		$this->_render_instructions();

		?><div class="domainmapping-domains domainmapping-box">
			<h3>
				<?php _e( 'Domain(s) mapped to', 'domainmap' ) ?>
				<span class="domainmapping-origin"><?php echo $schema ?>://<?php echo esc_html( $this->origin->domain . $this->origin->path ) ?></span>
			</h3>
			<div class="domainmapping-domains-wrapper domainmapping-box-content<?php echo $form_class ?>">
				<div class="domainmapping-locker"></div>
				<ul class="domainmapping-domains-list">
					<li>
						<span class="domainmapping-mapped"><?php _e( 'Mapped domain', 'domainmap' ) ?></span>
						<span class="domainmapping-map-state"><?php _e( 'Health status', 'domainmap' ) ?></span>
						<span class="domainmapping-map-remove"><?php _e( 'Actions', 'domainmap' ) ?></span>
					</li>
					<?php foreach( $this->domains as $row ) : ?>
						<?php self::render_mapping_row( $row, $schema ) ?>
					<?php endforeach; ?>
					<li class="domainmapping-form">
						<form id="domainmapping-front-mapping" action="<?php echo $admin_ajax ?>" method="post">
							<?php wp_nonce_field( Domainmap_Plugin::ACTION_CHANGE_FRONTEND_REDIRECT, 'nonce' ) ?>
							<input type="hidden" name="action" value="<?php echo Domainmap_Plugin::ACTION_CHANGE_FRONTEND_REDIRECT ?>">
							<span><?php esc_html_e( 'Front end redirect should be', 'domainmap' ) ?></span>
							<select name="mapping">
								<?php foreach ( $mapping_types as $key => $label ) : ?>
								<option value="<?php echo $key ?>"<?php selected( $key, $mapping ) ?>><?php echo esc_html( $label ) ?></option>
								<?php endforeach; ?>
							</select>
						</form>
						<form id="domainmapping-form-map-domain" action="<?php echo $admin_ajax ?>" method="post">
							<?php wp_nonce_field( Domainmap_Plugin::ACTION_MAP_DOMAIN, 'nonce' ) ?>
							<input type="hidden" name="action" value="<?php echo Domainmap_Plugin::ACTION_MAP_DOMAIN ?>">
							<input type="text" class="domainmapping-input-prefix" readonly disabled value="<?php echo $schema ?>://">
							<div class="domainmapping-controls-wrapper">
								<input type="text" class="domainmapping-input-domain" autofocus name="domain">
							</div>
							<input type="text" class="domainmapping-input-sufix" readonly disabled value="/">
							<button type="submit" class="button button-primary domainmapping-button"><i class="icon-globe icon-white"></i><?php _e( 'Map domain', 'domainmap' ) ?></button>
							<div class="domainmapping-clear"></div>
						</form>
					</li>
				</ul>
			</div>
		</div><?php
	}

	/**
	 * Renders domain mapping row.
	 *
	 * @since 4.0.0
	 *
	 * @static
	 * @access public
	 * @global stdClass $current_site Current site object.
	 * @param object $row The mapped domain name.
	 * @param string $schema The current schema.
	 */
	public static function render_mapping_row( $row, $schema = false ) {
		global $current_site;

		if ( !$schema ) {
			$schema = defined( 'DM_FORCE_PROTOCOL_ON_MAPPED_DOMAIN' ) && DM_FORCE_PROTOCOL_ON_MAPPED_DOMAIN && is_ssl() ? 'https' : 'http';;
		}

		$multi = self::_is_multi_enabled();
		$admin_ajax = admin_url( 'admin-ajax.php' );

		$remove_link = add_query_arg( array(
			'action' => Domainmap_Plugin::ACTION_UNMAP_DOMAIN,
			'nonce'  => wp_create_nonce( Domainmap_Plugin::ACTION_UNMAP_DOMAIN ),
			'domain' => $row->domain,
		), $admin_ajax );

		// if multi domains mapping enabled, then add ability to select primary domain
		if ( $multi ) {
			$primary_class = $row->is_primary == 1 ? 'icon-star' : 'icon-star-empty';
			$select_primary = esc_url( add_query_arg( array(
				'action' => Domainmap_Plugin::ACTION_SELECT_PRIMARY_DOMAIN,
				'nonce'  => wp_create_nonce( Domainmap_Plugin::ACTION_SELECT_PRIMARY_DOMAIN ),
				'domain' => $row->domain,
			), $admin_ajax ) );
			$deselect_primary = esc_url( add_query_arg( array(
				'action' => Domainmap_Plugin::ACTION_DESELECT_PRIMARY_DOMAIN,
				'nonce'  => wp_create_nonce( Domainmap_Plugin::ACTION_DESELECT_PRIMARY_DOMAIN ),
				'domain' => $row->domain,
			), $admin_ajax ) );
		}

		?><li>
			<a class="domainmapping-mapped" href="<?php echo $schema ?>://<?php echo $row->domain, $current_site->path ?>" target="_blank" title="<?php _e( 'Go to this domain', 'domainmap' ) ?>">
				<?php echo $schema ?>://<?php echo $row->domain, $current_site->path ?>
			</a>
			<?php self::render_health_column( $row->domain ) ?>
			<a class="domainmapping-map-remove icon-trash" href="#" data-href="<?php echo esc_url( $remove_link ) ?>" title="<?php _e( 'Remove the domain', 'domainmap' ) ?>"></a>
			<?php if ( $multi ) : ?>
			<a class="domainmapping-map-primary <?php echo $primary_class ?>" href="#" data-select-href="<?php echo $select_primary ?>" data-deselect-href="<?php echo $deselect_primary ?>" title="<?php _e( 'Select as primary domain', 'domainmap' ) ?>"></a>
			<?php endif; ?>
		</li><?php
	}

	/**
	 * Renders health check status columnt.
	 *
	 * @since 4.0.0
	 *
	 * @static
	 * @access public
	 * @param string $domain The domain name.
	 */
	public static function render_health_column( $domain ) {
		$health_link = add_query_arg( array(
			'action' => Domainmap_Plugin::ACTION_HEALTH_CHECK,
			'nonce'  => wp_create_nonce( Domainmap_Plugin::ACTION_HEALTH_CHECK ),
			'domain' => $domain,
		), admin_url( 'admin-ajax.php' ) );

		$health = get_site_transient( "domainmapping-{$domain}-health" );
		$health_message = __( 'need revalidate', 'domainmap' );
		$health_class = ' domainmapping-need-revalidate';
		if ( $health !== false ) {
			if ( $health ) {
				$health_class = ' domainmapping-valid-domain';
				$health_message = __( 'valid', 'domainmap' );
			} else {
				$health_class = ' domainmapping-invalid-domain';
				$health_message = __( 'invalid', 'domainmap' );
			}
		}

		?><a class="domainmapping-map-state<?php echo $health_class ?>" href="<?php echo $health_link ?>" title="<?php _e( 'Refresh health status', 'domainmap' ) ?>"><?php
			echo $health_message
		?></a><?php
	}

}