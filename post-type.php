<?php

if ( !defined( 'ABSPATH' ) )
	exit;

add_action( 'init', function() {
	register_post_type( 'daytip', [
		'labels' => [
			'name' => __( 'Tips', 'daytip' ),
			'singular_name' => __( 'Tip', 'daytip' ),
			'add_new' => _x( 'Add New', 'daytip', 'daytip' ),
			'add_new_item' => __( 'Add New Tip', 'daytip' ),
			'edit_item' => __( 'Edit Tip', 'daytip' ),
			'new_item' => __( 'New Tip', 'daytip' ),
			'view_item' => __( 'View Tip', 'daytip' ),
			'search_items' => __( 'Search Tips', 'daytip' ),
			'not_found' => __( 'No tips found', 'daytip' ),
			'not_found_in_trash' => __( 'No tips found in Trash', 'daytip' ),
			// parent_item_colon
			'all_items' => __( 'All Tips', 'daytip' ),
			// 'archives' => __( 'Tip Archives', 'daytip' ),
			// 'insert_into_item' => __( 'Insert into tip', 'daytip' ),
			// 'uploaded_to_this_item' => __( 'Uploaded to this tip', 'daytip' ),
			// *featured_image
			// menu_name
			// *items_list*
			// name_admin_bar
		],
		// 'description'
		// 'public' => TRUE,
		'exclude_from_search' => TRUE,
		'publicly_queryable' => FALSE,
		'show_ui' => TRUE,
		'show_in_nav_menus' => TRUE,
		// show_in_menu
		// show_in_admin_bar
		// menu_position
		'menu_icon' => 'dashicons-editor-quote',
		'capability_type' => 'page',
		// capabilities
		// map_meta_cap
		// hierarchical
		'supports' => [
			'title',
			'editor',
			'author',
			// 'thumbnail',
			// 'excerpt',
			// 'trackbacks',
			'custom_fields',
			// 'comments',
			'revisions',
			// 'page-attributes',
			// 'post-formats',
		],
		// register_meta_box_cb
		// taxonomies
		// has_archive
		'rewrite' => FALSE,
		// permalink_epmask
		// query_var
		// can_export
		// delete_with_user
		// show_in_rest
		// rest_base
		// rest_controller_class
	] );
} );

// TODO customize messages, @see https://codex.wordpress.org/Function_Reference/register_post_type#Example
