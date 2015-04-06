<?php

/**
 * Core functions file for the plugin.  This file sets up default actions/filters and defines other functions
 * needed within the plugin.
 *
 */

/* Filter the post type archive title. */
add_filter( 'post_type_archive_title', 'kbp_post_type_archive_title' );

/**
 * Returns the default plugin settings.
 *
 */
function kbp_get_default_settings() {

	$settings = array(
		'knowledgebase_archive_title' => __( 'Knowledgebase',            'knowledgebase' ),
		'knowledgebase_description'   => __( 'Got questions? You’re in the right place!.', 'knowledgebase' )
	);

	return $settings;
}

/**
 * Defines the base URL slug for the "knowledgebase" section of the Web site.
 *
 */
function kbp_knowledgebase_base() {
	return apply_filters( 'kbp_knowledgebase_base', 'knowledgebase' );
}

/**
 * Filters 'post_type_archive_title' to use our custom 'archive_title' label.
 *
 */
function kbp_post_type_archive_title( $title ) {

	if ( is_post_type_archive( 'knowledgebase' ) ) {
		$post_type = get_post_type_object( 'knowledgebase' );
		$title     = isset( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $title;
	}

	return $title;
}

