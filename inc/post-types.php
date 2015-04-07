<?php
/**
 * File for registering custom post types.
 *
 * @package    Knowledgebase
 * @subpackage Includes
 * @since      0.0.1
 */

/* Register custom post types on the 'init' hook. */
add_action( 'init', 'knowledgebase_register_post_types' );

/* Filter post updated messages for custom post types. */
add_filter( 'post_updated_messages', 'kbp_post_updated_messages' );

/* Filter the "enter title here" text. */
add_filter( 'enter_title_here', 'kbp_enter_title_here', 10, 2 );

/**
 * Registers post types needed by the plugin.
 *
 * @since  0.0.1
 * @access public
 * @return void
 */
function knowledgebase_register_post_types() {

	/* Get plugin settings. */
	$settings = get_option( 'knowledgebase_settings', kbp_get_default_settings() );

	/* Set up the arguments for the post type. */
	$args = array(
		'description'         => $settings['knowledgebase_item_description'],
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'show_in_nav_menus'   => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-lightbulb',
		'can_export'          => true,
		'delete_with_user'    => false,
		'hierarchical'        => false,
		'has_archive'         => kbp_knowledgebase_base(),
		'query_var'           => 'knowledgebase_item',
		'capability_type'     => 'knowledgebase_item',
		'map_meta_cap'        => true,

		'capabilities' => array(

			// meta caps (don't assign these to roles)
			'edit_post'              => 'edit_knowledgebase_item',
			'read_post'              => 'read_knowledgebase_item',
			'delete_post'            => 'delete_knowledgebase_item',

			// primitive/meta caps
			'create_posts'           => 'create_knowledgebase_items',

			// primitive caps used outside of map_meta_cap()
			'edit_posts'             => 'edit_knowledgebase_items',
			'edit_others_posts'      => 'manage_knowledgebase',
			'publish_posts'          => 'manage_knowledgebase',
			'read_private_posts'     => 'read',

			// primitive caps used inside of map_meta_cap()
			'read'                   => 'read',
			'delete_posts'           => 'manage_knowledgebase',
			'delete_private_posts'   => 'manage_knowledgebase',
			'delete_published_posts' => 'manage_knowledgebase',
			'delete_others_posts'    => 'manage_knowledgebase',
			'edit_private_posts'     => 'edit_knowledgebase_items',
			'edit_published_posts'   => 'edit_knowledgebase_items'
		),

		'rewrite' => array(
			'slug'       => kbp_knowledgebase_base(), // . '/items',
			'with_front' => false,
			'pages'      => true,
			'feeds'      => true,
			'ep_mask'    => EP_PERMALINK,
		),

		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'comments',
			'revisions',
		),

		'labels' => array(
			'name'               => __( 'Knowledgebase Articles',                   'knowledgebase' ),
			'singular_name'      => __( 'Knowledgebase Article',                    'knowledgebase' ),
			'menu_name'          => __( 'Knowledgebase',                   'knowledgebase' ),
			'name_admin_bar'     => __( 'Knowledgebase Article',         'knowledgebase' ),
			'all_items'          => __( 'Articles',                   'knowledgebase' ),
			'add_new'            => __( 'Add Article',                'knowledgebase' ),
			'add_new_item'       => __( 'Add New Article',            'knowledgebase' ),
			'edit_item'          => __( 'Edit Article',               'knowledgebase' ),
			'new_item'           => __( 'New Article',                'knowledgebase' ),
			'view_item'          => __( 'View Article',               'knowledgebase' ),
			'search_items'       => __( 'Search Articles',            'knowledgebase' ),
			'not_found'          => __( 'No articles found',          'knowledgebase' ),
			'not_found_in_trash' => __( 'No articles found in trash', 'knowledgebase' ),

			/* Custom archive label.  Must filter 'post_type_archive_title' to use. */
			'archive_title'      => $settings['knowledgebase_item_archive_title'],
		)
	);

	/* Register the post type. */
	register_post_type( 'knowledgebase_item', $args );
}

/**
 * Custom "enter title here" text.
 *
 * @since  0.0.1
 * @access public
 * @param  string  $title
 * @param  object  $post
 * @return string
 */
function kbp_enter_title_here( $title, $post ) {

	if ( 'knowledgebase_item' === $post->post_type )
		$title = __( 'Enter article title', 'knowledgebase' );

	return $title;
}

/**
 * @since  0.0.1
 * @access public
 * @return void
 */
function kbp_post_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['knowledgebase_item'] = array(
		 0 => '', // Unused. Messages start at index 1.
		 1 => sprintf( __( 'Article updated. <a href="%s">View article</a>', 'knowledgebase' ), esc_url( get_permalink( $post_ID ) ) ),
		 2 => '',
		 3 => '',
		 4 => __( 'Article updated.', 'knowledgebase' ),
		 5 => isset( $_GET['revision'] ) ? sprintf( __( 'Article restored to revision from %s', 'knowledgebase' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		 6 => sprintf( __( 'Article published. <a href="%s">View article</a>', 'knowledgebase' ), esc_url( get_permalink( $post_ID ) ) ),
		 7 => __( 'Article saved.', 'knowledgebase' ),
		 8 => sprintf( __( 'Article submitted. <a target="_blank" href="%s">Preview article</a>', 'knowledgebase' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		 9 => sprintf( __( 'Article scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview article</a>', 'knowledgebase' ), date_i18n( __( 'M j, Y @ G:i', 'restaurant' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Article draft updated. <a target="_blank" href="%s">Preview article</a>', 'knowledgebase' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);

	return $messages;
}
