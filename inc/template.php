<?php
/**
 * Template tags to use in themes.
 *
 */

/**
 * Conditional tag to decide if we're viewing a knowledgebase-related page.
 *
 */
function kbp_is_knowledgebase() {

	if ( is_singular( 'knowledgebase_item' ) || is_post_type_archive( 'knowledgebase_item' ) || is_tax( 'knowledgebase_tag' ) ) {
		$is_knowledgebase_page = true;
	} else {
		$is_knowledgebase_page = false;
	}

	return apply_filters( 'kbp_is_knowledgebase', $is_knowledgebase_page );
}

/**
 * Conditional tag to decide if we're viewing a knowledgebase-related archive page.
 *
 */
function kbp_is_knowledgebase_archive() {

	if ( is_post_type_archive( 'knowledgebase_item' ) ) {
		$is_knowledgebase_archive_page = true;
	} else {
		$is_knowledgebase_archive_page = false;
	}

	return apply_filters( 'kbp_is_knowledgebase_archive', $is_knowledgebase_archive_page );
}


/**
 * Conditional tag to decide if we're viewing a knowledgebase-related taxonomy page.
 *
 */
function kbp_is_knowledgebase_tax() {

	if ( is_tax( 'knowledgebase_tag' ) ) {
		$is_knowledgebase_tax_page = true;
	} else {
		$is_knowledgebase_tax_page = false;
	}

	return apply_filters( 'kbp_is_knowledgebase_tax', $is_knowledgebase_tax_page );
}

/**
 * Conditional tag to decide if we're viewing a knowledgebase-related single page.
 *
 */
function kbp_is_knowledgebase_single() {

	if ( is_singular( 'knowledgebase_item' ) ) {
		$is_knowledgebase_single_page = true;
	} else {
		$is_knowledgebase_single_page = false;
	}

	return apply_filters( 'kbp_is_knowledgebase_single', $is_knowledgebase_single_page );
}
