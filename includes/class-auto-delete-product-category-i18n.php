<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://helloeveryone.me/
 * @since      1.0.0
 *
 * @package    Auto_Delete_Product_Category
 * @subpackage Auto_Delete_Product_Category/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Auto_Delete_Product_Category
 * @subpackage Auto_Delete_Product_Category/includes
 * @author     Hello Everyone <hola@helloeveryone.me>
 */
class Auto_Delete_Product_Category_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'auto-delete-product-category',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
