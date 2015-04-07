<?php
/**
 * Handles custom post meta boxes for the 'knowledgebase_item' post type.
 *
 * @package    Knowledgebase
 * @subpackage Admin
 * @since      0.0.1
 */

final class KBP_Knowledgebase_Settings {

	/**
	 * Holds the instances of this class.
	 *
	 * @since  0.0.1
	 * @access private
	 * @var    object
	 */
	private static $instance;

	/**
	 * Settings page name.
	 *
	 * @since  0.0.1
	 * @access public
	 * @var    string
	 */
	public $settings_page = '';

	/**
	 * Holds an array the plugin settings.
	 *
	 * @since  0.0.1
	 * @access public
	 * @var    array
	 */
	public $settings = array();

	/**
	 * Sets up the needed actions for adding and saving the meta boxes.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Sets up custom admin menus.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function admin_menu() {

		$this->settings_page = add_submenu_page(
			'edit.php?post_type=knowledgebase_item',
			__( 'Knowledgebase Settings', 'knowledgebase' ),
			__( 'Settings',            'knowledgebase' ),
			apply_filters( 'knowledgebase_settings_capability', 'manage_options' ),
			'knowledgebase-settings',
			array( $this, 'settings_page' )
		);

		if ( !empty( $this->settings_page ) ) {

			/* Register the plugin settings. */
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
	}

	/**
	 * Registers the plugin settings.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	function register_settings() {

		$this->settings = get_option( 'knowledgebase_settings', kbp_get_default_settings() );

		register_setting( 'knowledgebase_settings', 'knowledgebase_settings', array( $this, 'validate_settings' ) );

		add_settings_section(
			'kbp_section_menu',
			__( 'Knowledgebase Settings', 'knowledgebase' ),
			array( $this, 'section_menu' ),
			$this->settings_page
		);

		add_settings_field(
			'kbp_field_menu_title',
			__( 'Knowledgebase Archive Title', 'knowledgebase' ),
			array( $this, 'field_menu_title' ),
			$this->settings_page,
			'kbp_section_menu'
		);

		add_settings_field(
			'kbp_field_menu_description',
			__( 'Knowledgebase Archive Description', 'knowledgebase' ),
			array( $this, 'field_menu_description' ),
			$this->settings_page,
			'kbp_section_menu'
		);
	}

	/**
	 * Validates the plugin settings.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	function validate_settings( $settings ) {

		$settings['knowledgebase_item_archive_title'] = strip_tags( $settings['knowledgebase_item_archive_title'] );

		/* Kill evil scripts. */
		if ( !current_user_can( 'unfiltered_html' ) )
			$settings['knowledgebase_item_description'] = stripslashes( wp_filter_post_kses( addslashes( $settings['knowledgebase_item_description'] ) ) );

		/* Return the validated/sanitized settings. */
		return $settings;
	}

	/**
	 * Displays the menu settings section.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function section_menu() { ?>

		<p class="description">
			<?php printf( __( "Your knowledgebase is located at %s.", 'knowledgebase' ), '<a href="' . get_post_type_archive_link( 'knowledgebase_item' ) . '"><code>' . get_post_type_archive_link( 'knowledgebase_item' ) . '</code></a>' ); ?>
		</p>
	<?php }

	/**
	 * Displays the menu title field.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function field_menu_title() { ?>

		<p>
			<input type="text" class="regular-text" name="knowledgebase_settings[knowledgebase_item_archive_title]" id="knowledgebase_settings-knowledgebase_item_archive_title" value="<?php echo esc_attr( $this->settings['knowledgebase_item_archive_title'] ); ?>" />
		</p>
	<?php }

	/**
	 * Displays the menu description field.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function field_menu_description() { ?>

		<p>
			<textarea class="large-text" name="knowledgebase_settings[knowledgebase_item_description]" id="knowledgebase_settings-knowledgebase_item_description" rows="4"><?php echo esc_textarea( $this->settings['knowledgebase_item_description'] ); ?></textarea>
			<span class="description"><?php _e( "Custom description for your knowledgebase. You may use <abbr title='Hypertext Markup Language'>HTML</abbr>. Your theme may or may not display this description.", 'knowledgebase' ); ?></span>
		</p>
	<?php }

	/**
	 * Renders the settings page.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function settings_page() { ?>

		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e( 'Knowledgebase Settings', 'knowledgebase' ); ?></h2>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php settings_fields( 'knowledgebase_settings' ); ?>
				<?php do_settings_sections( $this->settings_page ); ?>
				<?php submit_button( esc_attr__( 'Update Settings', 'knowledgebase' ), 'primary' ); ?>
			</form>

		</div><!-- wrap -->
	<?php }

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}

KBP_Knowledgebase_Settings::get_instance();
