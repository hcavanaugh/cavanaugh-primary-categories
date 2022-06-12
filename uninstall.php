<?php

/**
 * Fired when the Cavanaugh Primary Categories plugin is uninstalled.
 *
 * @link       https://cavanaugh.dev
 * @since      1.0.0
 *
 */

function cavanaugh_primary_categories_uninstall() {

    // Security: If uninstall not called from WordPress, abort uninstall.
    if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ):
        exit;
    endif;
}
