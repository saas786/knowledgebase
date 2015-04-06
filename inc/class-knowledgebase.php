<?php

final class Knowledgebase {

	/* --------------------------------------------*
	 * Constants
	 * -------------------------------------------- */

	const name = 'knowledgebase';
	const slug = 'knowledgebase';

	/* --------------------------------------------*
	 * Variables
	 * -------------------------------------------- */

	private $file;
	private $token;
	public $version;

	/* --------------------------------------------*
	 * Holds the instances of this class.
	 * -------------------------------------------- */

	private static $instance;

	/* --------------------------------------------*
	 * Constructor
	 * -------------------------------------------- */

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct( $file ) {
		$this->file = $file;
		$this->token = 'knowledgebase';
		$this->version = '0.0.2';

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( $this, 'includes' ), 3 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( $this, 'admin' ), 4 );

		/* Register activation hook. */
		register_activation_hook( $this->file, array( $this, 'activation' ) );
	}


	/**
	 * Defines constants for the plugin.
	 *
	 */
	function constants() {

		/* Set the version number of the plugin. */
		define( 'KNOWLEDGEBASE_VERSION', '0.0.2' );

		/* Set the database version number of the plugin. */
		define( 'KNOWLEDGEBASE_DB_VERSION', 1 );

		/* Set constant path to the plugin directory. */
		define( 'KNOWLEDGEBASE_DIR', trailingslashit( plugin_dir_path( $this->file ) ) );

		/* Set constant path to the plugin URI. */
		define( 'KNOWLEDGEBASE_URI', trailingslashit( plugin_dir_url( $this->file ) ) );
	}


	/**
	 * Loads the translation files.
	 *
	 */
	function i18n() {
		load_plugin_textdomain( 'knowledgebase', false, 'knowledgebase/languages' );
	}

	/**
	 * Loads files from the '/inc' folder.
	 *
	 */
	function includes() {

		require_once( KNOWLEDGEBASE_DIR . 'inc/core.php'       );
		require_once( KNOWLEDGEBASE_DIR . 'inc/post-types.php' );
		require_once( KNOWLEDGEBASE_DIR . 'inc/taxonomies.php' );
		require_once( KNOWLEDGEBASE_DIR . 'inc/template.php'   );
	}


	/**
	 * Loads admin files.
	 *
	 */
	function admin() {

		if ( is_admin() ) {
			require_once( KNOWLEDGEBASE_DIR . 'admin/class-knowledgebase-admin.php'    );
			require_once( KNOWLEDGEBASE_DIR . 'admin/class-knowledgebase-settings.php' );
		}
	}


	/**
	 * Method that runs only when the plugin is activated.
	 *
	 */
	function activation() {
		/* Get the administrator role. */
		$role =& get_role( 'administrator' );

		/* If the administrator role exists, add required capabilities for the plugin. */
		if ( !empty( $role ) ) {
			$role->add_cap( 'manage_knowledgebase' );
			$role->add_cap( 'create_knowledgebase_articles' );
			$role->add_cap( 'edit_knowledgebase_articles' );
		}
	}

	/**
	 * Returns the instance.
	 *
	 */
	public static function get_instance( $file ) {

		if ( !self::$instance ) {
			self::$instance = new self( $file );
		}

		return self::$instance;
	}
}

