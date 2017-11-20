<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://localhost/wordpress/
 * @since             1.0.0
 * @package           bootstrap-builder
 *
 * @wordpress-plugin
 * Plugin Name:       Bootstrap Page Builder
 * Plugin URI:        http://localhost/wordpress/
 * Description:       Build your Bootstrap-themed pages visually in an easy to use, interactive interface
 * Version:           0.1.0
 * Author:            Mike Ritchie
 * Author URI:        http://www.fusebuilder.net/
 * License:           
 * License URI:       
 * Text Domain:       bootstrap-builder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BOOTSTRAP_BUILDER_VERSION', '0.1.0' );
define( 'BOOTSTRAP_BUILDER_BASEURL', plugins_url('', __FILE__) );
define( 'BOOTSTRAP_BUILDER_BASEDIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bootstrap-builder-activator.php
 */
function activate_bootstrap_builder() {
	require_once BOOTSTRAP_BUILDER_BASEDIR . 'includes/class-bootstrap-builder-activator.php';
	Bootstrap_Builder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bootstrap-builder-deactivator.php
 */
function deactivate_bootstrap_builder() {
	require_once BOOTSTRAP_BUILDER_BASEDIR . 'includes/class-bootstrap-builder-deactivator.php';
	Bootstrap_Builder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bootstrap_builder' );
register_deactivation_hook( __FILE__, 'deactivate_bootstrap_builder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require BOOTSTRAP_BUILDER_BASEDIR . 'includes/class-bootstrap-builder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bootstrap_builder() {

	$plugin = new Bootstrap_Builder();
	$plugin->run();

}
run_bootstrap_builder();
