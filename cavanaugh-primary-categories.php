<?php

/**
 *
 * @link              https://cavanaugh.dev
 * @since             1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Cavanaugh Primary Categories
 * Plugin URI:        https://cavanaugh.dev
 * Description:       Allow WordPress publishers to select a primary category for post categories and custom post type taxonomies.
 * Version:           1.0.0
 * Author:            Heather Cavanaugh
 * Author URI:        https://cavanaugh.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cavanaugh-primary-categories
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PRIMARY_CATEGORIES_VERSION', '1.0.0' );

register_uninstall_hook( __FILE__ . 'uninstall.php', 'cavanaugh_primary_categories_uninstall' );

if( is_admin() ):
    require_once plugin_dir_path( __FILE__ ) . 'inc/class-cavanaugh-primary-categories-metaboxes.php';
    new Primary_Categories_Metaboxes();
endif;
