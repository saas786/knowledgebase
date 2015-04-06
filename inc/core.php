<?php

/**
 * Core functions file for the plugin.  This file sets up default actions/filters and defines other functions
 * needed within the plugin.
 *
 */

/* Filter the post type archive title. */
add_filter( 'post_type_archive_title', 'kbp_post_type_archive_title' );

/* Add custom image sizes (for menu listing in admin). */
add_action( 'init', 'kbp_add_image_sizes' );

/**
 * Returns the default plugin settings.
 *
 */
function kbp_get_default_settings() {

	$settings = array(
		'knowledgebase_item_archive_title' => __( 'Knowledgebase',            'knowledgebase' ),
		'knowledgebase_item_description'   => __( 'Got questions? You’re in the right place!.', 'knowledgebase' )
	);

	return $settings;
}

/**
 * Defines the base URL slug for the "knowledgebase" section of the Web site.
 *
 */
function kbp_knowledgebase_menu_base() {
	return apply_filters( 'kbp_knowledgebase_menu_base', 'knowledgebase' );
}

/**
 * Filters 'post_type_archive_title' to use our custom 'archive_title' label.
 *
 */
function kbp_post_type_archive_title( $title ) {

	if ( is_post_type_archive( 'knowledgebase_item' ) ) {
		$post_type = get_post_type_object( 'knowledgebase_item' );
		$title     = isset( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $title;
	}

	return $title;
}

/**
 * Adds a custom image size for viewing in the admin edit posts screen.
 *
 */
function kbp_add_image_sizes() {
	add_image_size( 'knowledgebase-thumbnail', 100, 75, true );
}
