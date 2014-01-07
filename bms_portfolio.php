<?php
/**
 * @package BMS_Portfolio
 * @author Mike Lathrop
 * @version 0.0.1
 */
/*
Plugin Name: BMS Portfolio
Plugin URI: http://bigmikestudios.com
Depends: bms_smart_meta_box/bms_smart_meta_box.php
Description: Adds a 'Portfolio' post type and shortcodes
Version: 0.0.1
Author URI: http://bigmikestudios.com
*/

$cr = "\r\n";

// =============================================================================

//////////////////////////
//
// CUSTOM POST TYPES
//
//////////////////////////

add_action( 'init', 'register_cpt_portfolio' );
function register_cpt_portfolio() {
  $labels = array(
	  'name' => _x( 'Portfolio Items', 'portfolio' ),
	  'singular_name' => _x( 'Portfolio Item', 'portfolio' ),
	  'add_new' => _x( 'Add New', 'portfolio' ),
	  'add_new_item' => _x( 'Add New Item', 'portfolio' ),
	  'edit_item' => _x( 'Edit Item', 'portfolio' ),
	  'new_item' => _x( 'New Item', 'portfolio' ),
	  'view_item' => _x( 'View Item', 'portfolio' ),
	  'search_items' => _x( 'Search Portfolio Items', 'portfolio' ),
	  'not_found' => _x( 'No portfolio items found', 'portfolio' ),
	  'not_found_in_trash' => _x( 'No portfolio items found in Trash', 'portfolio' ),
	  'parent_item_colon' => _x( 'Parent Item:', 'portfolio' ),
	  'menu_name' => _x( 'Portfolio Items', 'portfolio' ),
  );
  $args = array(
	  'labels' => $labels,
	  'hierarchical' => false,
	  'supports' => array( 'title', 'editor', 'custom-fields', 'post-formats', 'thumbnail' ),
	  'taxonomies' => array( 'portfolio_category' ),
	  'public' => true,
	  'show_ui' => true,
	  'show_in_menu' => true,
	  'show_in_nav_menus' => true,
	  'publicly_queryable' => true,
	  'exclude_from_search' => false,
	  'has_archive' => true,
	  'query_var' => true,
	  'can_export' => true,
	  'rewrite' => true,
	  'capability_type' => 'post'
  );
  register_post_type( 'portfolio', $args );
  add_theme_support( 'post-thumbnails', array( 'portfolio' ) );
} 

add_action( 'init', 'bms_portfolio_add_post_thumbnails' );
function bms_portfolio_add_post_thumbnails() {
	add_theme_support( 'post-thumbnails', array( 'portfolio' ) );
}




// =============================================================================

//////////////////////////
//
// ADD META BOX
//
//////////////////////////
/*
if (is_admin()) {
	if (!class_exists('SmartMetaBox')) {
		require_once("../wp-content/plugins/bms_smart_meta_box/SmartMetaBox.php");
	}
		
	new SmartMetaBox('smart_meta_box_portfolio', array(
		'title'     => 'BMS Portfolio',
		'pages'     => array('portfolio'),
		'context'   => 'normal',
		'priority'  => 'high',
		'fields'    => $fields
	));
}
*/

// =============================================================================

//////////////////////////
//
// ADD META BOX
//
//////////////////////////

function portfolio_bg_imgs( $attachments )
{
  $fields         = array(
    array(
      'name'      => 'title',                         // unique field name
      'type'      => 'text',                          // registered field type
      'label'     => __( 'Title', 'attachments' ),    // label to display
      // 'default'   => 'title',                         // default value upon selection
    ),
  );

  $args = array(

    // title of the meta box (string)
    'label'         => 'Background Images',

    // all post types to utilize (string|array)
    'post_type'     => array( 'portfolio' ),

    // meta box position (string) (normal, side or advanced)
    'position'      => 'normal',

    // meta box priority (string) (high, default, low, core)
    'priority'      => 'high',

    // allowed file type(s) (array) (image|video|text|audio|application)
    'filetype'      => array('image'),  // no filetype limit

    // include a note within the meta box (string)
    'note'          => 'Attach files here!',

    // by default new Attachments will be appended to the list
    // but you can have then prepend if you set this to false
    'append'        => true,

    // text for 'Attach' button in meta box (string)
    'button_text'   => __( 'Attach Files', 'attachments' ),

    // text for modal 'Attach' button (string)
    'modal_text'    => __( 'Attach', 'attachments' ),

    // which tab should be the default in the modal (string) (browse|upload)
    'router'        => 'browse',

    // fields array
    'fields'        => $fields,

  );

  $attachments->register( 'portfolio_bg_imgs', $args ); // unique instance name
}

add_action( 'attachments_register', 'portfolio_bg_imgs' );

// ===============================================================

function portfolio_other_imgs( $attachments )
{
  $fields         = array(
    array(
      'name'      => 'title',                         // unique field name
      'type'      => 'text',                          // registered field type
      'label'     => __( 'Title', 'attachments' ),    // label to display
      // 'default'   => 'title',                         // default value upon selection
    ),
  );

  $args = array(

    // title of the meta box (string)
    'label'         => 'Other Images',

    // all post types to utilize (string|array)
    'post_type'     => array( 'portfolio' ),

    // meta box position (string) (normal, side or advanced)
    'position'      => 'normal',

    // meta box priority (string) (high, default, low, core)
    'priority'      => 'high',

    // allowed file type(s) (array) (image|video|text|audio|application)
    'filetype'      => array('image'),  // no filetype limit

    // include a note within the meta box (string)
    'note'          => 'Attach files here!',

    // by default new Attachments will be appended to the list
    // but you can have then prepend if you set this to false
    'append'        => true,

    // text for 'Attach' button in meta box (string)
    'button_text'   => __( 'Attach Files', 'attachments' ),

    // text for modal 'Attach' button (string)
    'modal_text'    => __( 'Attach', 'attachments' ),

    // which tab should be the default in the modal (string) (browse|upload)
    'router'        => 'browse',

    // fields array
    'fields'        => $fields,

  );

  $attachments->register( 'portfolio_other_imgs', $args ); // unique instance name
}

add_action( 'attachments_register', 'portfolio_other_imgs' );