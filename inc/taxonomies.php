<?php

/**
 * File for registering custom taxonomies.
 *
 */

/* Register taxonomies on the 'init' hook. */
add_action( 'init', 'knowledgebase_register_taxonomies' );

/**
 * Register taxonomies for the plugin.
 *
 */
function knowledgebase_register_taxonomies() {

	$tag_args = array(
		'public'            => true,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_admin_column' => true,
		'hierarchical'      => false,
		'query_var'         => 'knowledgebase_tag',

		/* Only 2 caps are needed: 'manage_knowledgebase' and 'edit_knowledgebase_items'. */
		'capabilities' => array(
			'manage_terms' => 'manage_knowledgebase',
			'edit_terms'   => 'manage_knowledgebase',
			'delete_terms' => 'manage_knowledgebase',
			'assign_terms' => 'edit_knowledgebase_items',
		),

		/* The rewrite handles the URL structure. */
		'rewrite' => array(
			'slug'         => kbp_knowledgebase_menu_base() . '/tags',
			'with_front'   => false,
			'hierarchical' => false,
			'ep_mask'      => EP_NONE
		),

		/* Labels used when displaying taxonomy and terms. */
		'labels' => array(
			'name'                       => __( 'Tags',                           'knowledgebase' ),
			'singular_name'              => __( 'Tag',                            'knowledgebase' ),
			'menu_name'                  => __( 'Tags',                           'knowledgebase' ),
			'name_admin_bar'             => __( 'Tags',                           'knowledgebase' ),
			'search_items'               => __( 'Search Tags',                    'knowledgebase' ),
			'popular_items'              => __( 'Popular Tags',                   'knowledgebase' ),
			'all_items'                  => __( 'All Tags',                       'knowledgebase' ),
			'edit_item'                  => __( 'Edit Tag',                       'knowledgebase' ),
			'view_item'                  => __( 'View Tag',                       'knowledgebase' ),
			'update_item'                => __( 'Update Tag',                     'knowledgebase' ),
			'add_new_item'               => __( 'Add New Tag',                    'knowledgebase' ),
			'new_item_name'              => __( 'New Tag Name',                   'knowledgebase' ),
			'separate_items_with_commas' => __( 'Separate tags with commas',      'knowledgebase' ),
			'add_or_remove_items'        => __( 'Add or remove tags',             'knowledgebase' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'knowledgebase' ),
		)
	);

	/* Register the Knowledgebase Tag taxonomy. */
	register_taxonomy( 'knowledgebase_tag', array( 'knowledgebase_item' ), $tag_args );

	//Register Categories
	$category_args = array(
			'public'            => true,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'query_var'         => 'knowledgebase_category',
			/* Capabilities. */
			'capabilities' => array(
				'manage_terms' => 'manage_knowledgebase',
				'edit_terms'   => 'manage_knowledgebase',
				'delete_terms' => 'manage_knowledgebase',
				'assign_terms' => 'edit_knowledgebase_items',
			),
			/* The rewrite handles the URL structure. */
			'rewrite' => array(
				'slug'         => kbp_knowledgebase_menu_base() . '/category',
				'with_front'   => false,
				'hierarchical' => true,
				'ep_mask'      => EP_NONE
			),
			/* Labels used when displaying taxonomy and terms. */
			'labels' => array(
				'name'                       => __( 'Knowledgebase Categories', 'knowledgebase' ),
				'singular_name'              => __( 'Knowledgebase Category',   'knowledgebase' ),
				'menu_name'                  => __( 'Categories',             'knowledgebase' ),
				'name_admin_bar'             => __( 'Category',               'knowledgebase' ),
				'search_items'               => __( 'Search Categories',      'knowledgebase' ),
				'popular_items'              => __( 'Popular Categories',     'knowledgebase' ),
				'all_items'                  => __( 'All Categories',         'knowledgebase' ),
				'edit_item'                  => __( 'Edit Category',          'knowledgebase' ),
				'view_item'                  => __( 'View Category',          'knowledgebase' ),
				'update_item'                => __( 'Update Category',        'knowledgebase' ),
				'add_new_item'               => __( 'Add New Category',       'knowledgebase' ),
				'new_item_name'              => __( 'New Category Name',      'knowledgebase' ),
				'parent_item'                => __( 'Parent Category',        'knowledgebase' ),
				'parent_item_colon'          => __( 'Parent Category:',       'knowledgebase' ),
				'separate_items_with_commas' => null,
				'add_or_remove_items'        => null,
				'choose_from_most_used'      => null,
				'not_found'                  => null,
			)
		);

	/* Register the Knowledgebase Category taxonomy. */
	register_taxonomy( 'knowledgebase_category', array( 'knowledgebase_item' ), $category_args );
}
