<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __DIR__ ) . 'includes/class-orion-task-manager-builder.php';
//require plugin_dir_path( __DIR__ ) . 'includes/class-evaluator-evaluator.php';
if ( ! function_exists( 'o_admin_fields' ) ) {
	require plugin_dir_path( __DIR__ ) . 'includes/utils.php';
}
//require plugin_dir_path( __DIR__ ) . 'includes/functions.php';