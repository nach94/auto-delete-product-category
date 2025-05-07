<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://helloeveryone.me/
 * @since             1.0.0
 * @package           Auto_Delete_Product_Category
 *
 * @wordpress-plugin
 * Plugin Name:       Auto Delete Product Categoy
 * Plugin URI:        https://github.com/nach94/auto-delete-product-category
 * Description:       The plugin allows you to select a product category that is temporary for products that were published after x time.
 * Version:           1.0.0
 * Author:            Hello Everyone
 * Author URI:        https://helloeveryone.me//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       auto-delete-product-category
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AUTO_DELETE_PRODUCT_CATEGORY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-auto-delete-product-category-activator.php
 */
function activate_auto_delete_product_category() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-delete-product-category-activator.php';
	Auto_Delete_Product_Category_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-auto-delete-product-category-deactivator.php
 */
function deactivate_auto_delete_product_category() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-delete-product-category-deactivator.php';
	Auto_Delete_Product_Category_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_auto_delete_product_category' );
register_deactivation_hook( __FILE__, 'deactivate_auto_delete_product_category' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-auto-delete-product-category.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-auto-delete-product-category-functions.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_auto_delete_product_category() {

	$plugin = new Auto_Delete_Product_Category();
	$plugin->run();

}
run_auto_delete_product_category();
