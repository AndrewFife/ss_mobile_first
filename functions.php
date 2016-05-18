<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Mobile First Theme' );
define( 'CHILD_THEME_URL', 'http://briangardner.com/themes/mobile-first/' );
define( 'CHILD_THEME_VERSION', '2.0' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'mobile_first_scripts_styles' );
function mobile_first_scripts_styles() {

	wp_enqueue_script( 'mobile-first-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'mobile-first-sticky-message', get_bloginfo( 'stylesheet_directory' ) . '/js/sticky-message.js', array( 'jquery' ), '1.0.0' );

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:400,400italic,700', array(), CHILD_THEME_VERSION );

}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 114,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Remove header right widget area
unregister_sidebar( 'header-right' );

//* Reposition primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

//* Remove sidebars
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );

//* Force full-width-content layout setting
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

//* Remove site layouts
genesis_unregister_layout( 'content-sidebar' );
genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Hook sticky message before site header
add_action( 'genesis_before', 'mobile_first_sticky_message' );
function mobile_first_sticky_message() {

	genesis_widget_area( 'sticky-message', array(
		'before' => '<div class="sticky-message">',
		'after'  => '</div>',
	) );

}

//* Modify size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'mobile_first_author_box_gravatar' );
function mobile_first_author_box_gravatar( $size ) {

	return 160;

}

//* Modify size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'mobile_first_comments_gravatar' );
function mobile_first_comments_gravatar( $args ) {

	$args['avatar_size'] = 100;
	return $args;

}

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'sticky-message',
	'name'        => __( 'Sticky Message', 'bg-mobile-first' ),
	'description' => __( 'This is the sticky message widget area.', 'bg-mobile-first' ),
) );
