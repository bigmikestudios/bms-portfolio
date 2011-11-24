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

Here is an edit to test how this works on github.
*/

$cr = "\r\n";

// =============================================================================

//////////////////////////
//
// INCLUDES
//
//////////////////////////

wp_register_style('bms_portfolio', plugins_url() .'/bms_portfolio/bms_portfolio.css');
wp_enqueue_style('bms_portfolio');


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
    'singular_name' => _x( 'Item', 'portfolio' ),
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
    'supports' => array( 'title', 'editor' ),
    'taxonomies' => array( 'category', 'post_tag' ),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => false,
    'has_archive' => false,
    'query_var' => true,
    'can_export' => true,
    'rewrite' => true,
    'capability_type' => 'post'
    );
    register_post_type( 'portfolio', $args );
    } 

// =============================================================================

//////////////////////////
//
// ADD META BOX
//
//////////////////////////

if (is_admin()) {
	if (!class_exists('SmartMetaBox')) {
		require_once("../wp-content/plugins/bms_smart_meta_box/SmartMetaBox.php");
	}
	
	new SmartMetaBox('smart_meta_box_portfolio', array(
		'title'     => 'BMS Portfolio',
		'pages'     => array('portfolio'),
		'context'   => 'normal',
		'priority'  => 'high',
		'fields'    => array(
			array(
				'name' => 'Image',
				'id' => 'bms_portfolio_image',
				'default' => '',
				'desc' => 'Add an image.',
				'type' => 'file',
			),
		)
	));
}
	
// =============================================================================

//////////////////////////
//
// ADD IMAGE SIZES
//
//////////////////////////

add_image_size( '150x150', 150, 150, true );
add_image_size( '150x9999', 150, 9999 );
add_image_size( '150x150', 600, 9999, true );

// =============================================================================

//////////////////////////
//
// SHORT CODES
//
//////////////////////////

// create shortcode for listing:
function bms_portfolio_listing($atts, $content=null) {
	extract( shortcode_atts( array(
		'foo' => 'something',
		'bar' => 'something else',
	), $atts ) );
	
	$return="<ul>"."\r\n";
	$i = 0;
	
	// get posts
	$args = array('post_type'=>'portfolio', 'orderby'=>'menu_order', 'order'=>'ASC');
	$my_posts = get_posts($args);
	foreach($my_posts as $my_post) {
		$img = get_post_meta($my_post->ID, '_smartmeta_bms_portfolio_image', true);
		$img = wp_get_attachment_image_src( $img, '150x150');
		$img_src	=$img[0];
		$img_width	=$img[1];
		$img_height	=$img[2];	
		$img_class	= ($i%2 ==0) ? 'alignleft' : 'alignright';
		
		$return .= "<li class='bms-portfolio portfolio-".$my_post->ID."'>"."\r\n";
		$return .= "<p><strong><a href='". get_permalink($my_post->ID)."'>".$my_post->post_title."</a></strong></p>"."\r\n";
		$return .= "<img src='$img_src' width='$img_width' height='$img_height' class='$img_class' alt='image' />"."\r\n";
		// if( current_user_can('edit_posts') ) $return .= "<p><a href=".get_edit_post_link($my_post->ID).">Edit</a>"."\r\n";
		$return .= "</li>"."\r\n";
		$i++;
	}
	
	$return.="</ul>"."\r\n";

	return $return;
}

add_shortcode('bms_portfolio_listing', 'bms_portfolio_listing');


// =============================================================================


// SINGLE ENTRY

function bms_portfolio_the_content ($c) {
	global $post;
	
	if ($post->post_type == "portfolio") {
		$img = get_post_meta($post->ID, '_smartmeta_bms_portfolio_image', true);
		$img = wp_get_attachment_image_src( $img, '600x9999');
		$img_src	=$img[0];
		$img_width	=$img[1];
		$img_height	=$img[2];	
		
		$return = "<!-- the_content start -->"."\r\n";
		$return .= "<img src='$img_src' width='$img_width' height='$img_height' class='$img_class' alt='image' />"."\r\n";
		$return .= wpautop($post->post_content);
		$return .= "<!-- the_content end -->"."\r\n";
		
		return $return;
	} else {
		return $c;
	}
}
add_filter('the_content', 'bms_portfolio_the_content');