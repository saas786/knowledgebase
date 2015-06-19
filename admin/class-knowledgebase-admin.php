<?php

/**
 * Sets up the admin functionality for the plugin.
 *
 * @package    Knowledgebase
 * @subpackage Admin
 * @since      0.0.1
 */

final class KBP_Knowledgebase_Admin {

	/**
	 * Holds the instances of this class.
	 *
	 * @since  0.0.1
	 * @access private
	 * @var    object
	 */
	private static $instance;

	/**
	 * Sets up needed actions/filters for the admin to initialize.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Only run our customization on the 'edit.php' page in the admin. */
		add_action( 'load-edit.php', array( $this, 'load_edit' ) );

		/* Edit post editor meta boxes. */
		add_action( 'do_meta_boxes', array( $this, 'edit_metaboxes' ) );

		/* Order the knowledgebase items by the 'order' attribute in the 'all_items' column view. */
		add_filter( 'pre_get_posts', array( $this, 'column_order' ) );


		/* Modify the columns on the "menu items" screen. */
		add_filter( 'manage_edit-knowledgebase_item_columns', array( $this, 'edit_knowledgebase_item_columns' ) );

		add_action( 'manage_knowledgebase_item_posts_custom_column',  array( $this, 'manage_knowledgebase_item_columns' ), 10, 2 );

	}

	/**
	 * Adds a custom filter on 'request' when viewing the edit menu items screen in the admin.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function load_edit() {
		$screen = get_current_screen();

		if ( !empty( $screen->post_type ) && 'knowledgebase_item' === $screen->post_type ) {
			add_filter( 'request',               array( $this, 'request'       ) );
			add_action( 'restrict_manage_posts', array( $this, 'tags_dropdown' ) );
			add_action( 'admin_head',            array( $this, 'print_styles'  ) );
		}
	}

	function edit_metaboxes() {

		remove_meta_box('pageparentdiv', 'knowledgebase_item', 'side');

		add_meta_box('pageparentdiv', __('Item Order', 'sliders'), 'page_attributes_meta_box', 'knowledgebase_item', 'side', 'low');

	}

	function column_order() {
		if ( is_admin() ) {

			$post_type = array_key_exists( 'post_type', $wp_query->query ) ? $wp_query->query['post_type'] : '';

			if ( $post_type == 'knowledgebase_item' ) {
				$wp_query->set('orderby', 'menu_order');
				$wp_query->set('order', 'ASC');
			}
		}
	}


	/**
	 * Filter on the 'request' hook to change the 'order' and 'orderby' query variables when
	 * viewing the "edit menu items" screen in the admin.  This is to order the menu items
	 * alphabetically.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $vars
	 * @return array
	 */
	public function request( $vars ) {

		/* Default ordering alphabetically. */
		if ( !isset( $vars['order'] ) && !isset( $vars['orderby'] ) ) {
			$vars = array_merge(
				$vars,
				array(
					'order'   => 'ASC',
					'orderby' => 'title'
				)
			);
		}

		return $vars;
	}

	/**
	 * Renders a knowledgebase tags dropdown on the "menu items" screen table nav.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function tags_dropdown() {

		$tag   = isset( $_GET['knowledgebase_tag'] ) ? esc_attr( $_GET['knowledgebase_tag'] ) : '';
		$terms = get_terms( 'knowledgebase_tag' );

		if ( !empty( $terms ) ) {
			echo '<select name="knowledgebase_tag" class="postform">';

			echo '<option value="' . selected( '', $tag, false ) . '">' . __( 'View all tags', 'knowledgebase' ) . '</option>';

			foreach ( $terms as $term )
				printf( '<option value="%s"%s>%s (%s)</option>', esc_attr( $term->slug ), selected( $term->slug, $tag, false ), esc_html( $term->name ), esc_html( $term->count ) );

			echo '</select>';
		}
	}

	/**
	 * Filters the columns on the "menu items" screen.
	 *
	 * @since  0.0.1
	 * @access public
	 * @param  array  $post_columns
	 * @return array
	 */
	public function edit_knowledgebase_item_columns( $post_columns ) {

		$screen     = get_current_screen();
		$post_type  = $screen->post_type;
		$columns    = array();
		$taxonomies = array();

		/* Adds the checkbox column. */
		$columns['cb'] = $post_columns['cb'];

		/* Add custom columns and overwrite the 'title' column. */
		$columns['title']     = __( 'Article',      'knowledgebase' );

		/* Get taxonomies that should appear in the manage posts table. */
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$taxonomies = wp_filter_object_list( $taxonomies, array( 'show_admin_column' => true ), 'and', 'name' );

		/* Allow devs to filter the taxonomy columns. */
		$taxonomies = apply_filters( "manage_taxonomies_for_{$post_type}_columns", $taxonomies, $post_type );
		$taxonomies = array_filter( $taxonomies, 'taxonomy_exists' );

		/* Loop through each taxonomy and add it as a column. */
		foreach ( $taxonomies as $taxonomy ) {
			$columns[ 'taxonomy-' . $taxonomy ] = get_taxonomy( $taxonomy )->labels->name;
		}

		/* Add the comments column. */
		if ( !empty( $post_columns['comments'] ) ) {
			$columns['comments'] = $post_columns['comments'];
		}

		$columns['order'] = __( 'Order', 'knowledgebase' );

		/* Return the columns. */
		return $columns;
	}

	public function manage_knowledgebase_item_columns( $column, $post_id ) {
		global $post;

		/* Get the post edit link for the post. */
		$edit_link = get_edit_post_link( $post->ID );

		switch( $column ) {

			case 'order' :

				echo '<a href="' . $edit_link . '">' . $post->menu_order . '</a>';

			/* Just break out of the switch statement for everything else. */
			default :
				break;
		}
	}


	/**
	 * Style adjustments for the manage menu items screen, particularly for adjusting the thumbnail
	 * column in the table to make sure it doesn't take up too much space.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return void
	 */
	public function print_styles( ) { ?>
		<style type="text/css">
		.edit-php .actions select[name="m"] {
			display: none;
		}
		</style>
	<?php }

	/**
	 * Returns the instance.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}

KBP_Knowledgebase_Admin::get_instance();
