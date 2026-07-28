<?php
/**
 * Fires only when the plugin is deleted via the WordPress Plugins screen
 * (not on simple deactivation). Removes the plugin's saved setting.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'hashcalendly_link' );
