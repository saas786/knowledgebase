<?php

/**
 * Plugin Name: Knowledgebase
 * Plugin URI: http://hybopressthemes.com/plugins/knowledgebase
 * Description: A base plugin for building knowledgebase Web sites.
 * Version: 0.0.6
 * Author: Syed Abrar Ahmed Shah
 * Author URI: http://hybopressthemes.com
 * Text Domain: knowledgebase
 * Domain Path: /languages
 *
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation; either version 2 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package    Knowledgebase
 * @version    0.0.6
 * @author     Syed Abrar Ahmed Shah <syed@hybopressthemes.com>
 * @copyright  Copyright (c) 2015, Syed Abrar Ahmed Shah
 * @link       http://hybopressthemes.com/plugins/knowledgebase
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit();
}

/**
 * Sets up and initializes the Knowledgebase plugin.
 *
 * @since  0.0.1
 * @access public
 * @return void
 */
final class Knowledgebase {

	/**
	 * Holds the instances of this class.
	 *
	 * @since  0.0.1
	 * @access private
	 * @var    object
	 */
	private static $instance;

	/**
	 * Sets up needed actions/filters for the plugin to initialize.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( $this, 'includes' ), 3 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( $this, 'admin' ), 4 );

		/* Register activation hook. */
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
	}

	/**
	 * Defines constants for the plugin.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	function constants() {

		/* Set the version number of the plugin. */
		define( 'KNOWLEDGEBASE_VERSION', '0.0.1' );

		/* Set the database version number of the plugin. */
		define( 'KNOWLEDGEBASE_DB_VERSION', 1 );

		/* Set constant path to the plugin directory. */
		define( 'KNOWLEDGEBASE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set constant path to the plugin URI. */
		define( 'KNOWLEDGEBASE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	/**
	 * Loads files from the '/inc' folder.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	function includes() {

		require_once( KNOWLEDGEBASE_DIR . 'inc/core.php'       );
		require_once( KNOWLEDGEBASE_DIR . 'inc/post-types.php' );
		require_once( KNOWLEDGEBASE_DIR . 'inc/taxonomies.php' );
		require_once( KNOWLEDGEBASE_DIR . 'inc/template.php'   );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	function i18n() {
		load_plugin_textdomain( 'knowledgebase', false, 'knowledgebase/languages' );
	}

	/**
	 * Loads admin files.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	function admin() {

		if ( is_admin() ) {
			require_once( KNOWLEDGEBASE_DIR . 'admin/class-knowledgebase-admin.php'    );
			require_once( KNOWLEDGEBASE_DIR . 'admin/class-knowledgebase-settings.php' );
		}
	}

	/**
	 * On plugin activation, add custom capabilities to the 'administrator' role.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	function activation() {

		//clear permalinks
		flush_rewrite_rules();

		$role = get_role( 'administrator' );

		if ( !empty( $role ) ) {
			$role->add_cap( 'manage_knowledgebase'       );
			$role->add_cap( 'create_knowledgebase_items' );
			$role->add_cap( 'edit_knowledgebase_items'   );
		}
	}

	/**
	 * Returns the instance.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

Knowledgebase::get_instance();
