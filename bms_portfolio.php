<?php
/**
 * @package BMS_Portfolio
 * @author Mike Lathrop
 * @version 0.0.1
 */
/*
Plugin Name: BMS Portfolio
Plugin URI: http://bigmikestudios.com
Description: Adds a 'Portfolio' post type and shortcodes
Version: 0.0.1
Author URI: http://bigmikestudios.com
*/

$cr = "\r\n";

// =============================================================================

function bms_pf_check_required_plugin() {
    if ( class_exists( 'acf' ) || !is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        return;
    }

    require_once ABSPATH . '/wp-admin/includes/plugin.php';
    deactivate_plugins( __FILE__ );

    $msg =  __( 'BMS Portfolio has been deactivated as it requires the <a href="http://www.advancedcustomfields.com/">Advanced Custom Fields</a> plugin.', 'bms_portfolio' ) . '<br /><br />';
    
    if ( file_exists( WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php' ) ) {
        $activate_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=advanced-custom-fields/acf.php', 'activate-plugin_advanced-custom-fields/acf.php' );
        $msg .= sprintf( __( 'It appears to already be installed. <a href="%s">Click here to activate it.</a>', 'bms_portfolio' ), $activate_url );
    }
    else {
        $download_url = 'http://downloads.wordpress.org/plugin/advanced-custom-fields.zip';
        $msg .= sprintf( __( '<a href="%s">Click here to download a zip of the latest version.</a> Then install and activate it. ', 'bms_portfolio' ), $download_url );
    }

    $msg .= '<br /><br />' . __( 'Once it has been activated, you can activate BMS Portfolio.', 'bms_portfolio' );

    wp_die( $msg );
}

add_action( 'plugins_loaded', 'bms_pf_check_required_plugin' );

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