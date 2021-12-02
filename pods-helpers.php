<?php
/**
 * Plugin Name: Pods Helpers Add-On
 * Plugin URI: https://pods.io/
 * Description: A holdover from Pods 1.x for backwards compatibility purposes, you most likely don't need these and we recommend you use our WP filters and actions instead.
 * Version: 2.4.0
 * Author: Pods Framework Team
 * Author URI: https://pods.io/
 * License: GPL-2.0+
 * Text Domain: pods-helpers
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Add our Pods Helpers component.
add_filter( 'pods_components_register', static function( $component_files ) {
	if ( defined( 'PODS_VERSION' ) && version_compare( PODS_VERSION, '2.8.0', '<' ) ) {
		return $component_files;
	}

	$component_files[] = [
		'File' => __DIR__ . '/Helpers.php',
	];

	return $component_files;
} );

add_filter( 'pods_helper_allow_callbacks', static function( $allowed, $params ) {
	// Try and get the helper, then maybe add a filter.
	if ( ! empty( $params['helper'] ) && ! is_callable( $params['helper'] ) ) {
		add_filter( 'pods_helper_include_obj', '__return_true', 15 );

		add_filter( $params['helper'], static function( $value, $obj = null ) use ( $params ) {
			$value = Pods_Helpers::helper( $params, $obj );

			if ( $obj ) {
				remove_filter( 'pods_helper_include_obj', '__return_true', 15 );
			}

			return $value;
		}, 10, 2 );
	}

	return $allowed;
}, 10, 2 );