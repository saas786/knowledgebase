<?php

/**
 * Sets up the admin functionality for the plugin.
 *
 */

final class KBP_Knowledgebase_Admin {

	/**
	 * Holds the instances of this class.
	 *
	 */
	private static $instance;

	/**
	 * Sets up needed actions/filters for the admin to initialize.
	 *
	 */
	public function __construct() {

		/* Only run our customization on the 'edit.php' page in the admin. */
		add_action( 'load-edit.php', array( $this, 'load_edit' ) );

		/* Modify the columns on the "knowledgebase articles" screen. */
		add_filter( 'manage_edit-knowledgebase_columns',          array( $this, 'edit_knowledgebase_columns'            )        );
	}

	/**
	 * Adds a custom filter on 'request' when viewing the edit knowledge articles screen in the admin.
	 *
	 */
	public function load_edit() {
		$screen = get_current_screen();

		if ( !empty( $screen->post_type ) && 'knowledgebase' === $screen->post_type ) {
			add_filter( 'request',               array( $this, 'request'       ) );
			add_action( 'restrict_manage_posts', array( $this, 'tags_dropdown' ) );
			add_action( 'admin_head',            array( $this, 'print_styles'  ) );
		}
	}

	/**
	 * Filter on the 'request' hook to change the 'order' and 'orderby' query variables when
	 * viewing the "edit knowledgebase articles" screen in the admin.  This is to order the knowledgebase articles
	 * alphabetically.
	 *
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
	 * Renders a knowledgebase tags dropdown on the "knowledgebase articles" screen table nav.
	 *
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
	 * Filters the columns on the "knowledgebase articles" screen.
	 *
	 */
	public function edit_knowledgebase_columns( $post_columns ) {

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

		/* Return the columns. */
		return $columns;
	}

	/**
	 * Style adjustments for the manage knowledgebase articles screen, particularly for adjusting the thumbnail
	 * column in the table to make sure it doesn't take up too much space.
	 *
	 */
	public function print_styles( ) { ?>
		<style type="text/css">
		.edit-php .wp-list-table td.thumbnail.column-thumbnail,
		.edit-php .wp-list-table th.manage-column.column-thumbnail {
			text-align: center;
			width: 100px;
		}
		.edit-php .actions select[name="m"] {
			display: none;
		}
		</style>
	<?php }

	/**
	 * Returns the instance.
	 *
	 */
	public static function get_instance() {

		if ( !self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

KBP_Knowledgebase_Admin::get_instance();
