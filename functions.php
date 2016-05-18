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
//*add_theme_support( 'custom-header', array );

//* Add support for custom logo
add_theme_support( 'custom-logo', array(
	'height'      => 57, // set to your dimensions
	'width'       => 115,
	'flex-height' => true,
	'flex-width'  => true,
) );

/**
 * Add an image inline in the site title element for the main logo
 *
 * The custom logo is then added via the Customiser
 *
 * @param string $title All the mark up title.
 * @param string $inside Mark up inside the title.
 * @param string $wrap Mark up on the title.
 * @author @_AlphaBlossom
 * @author @_neilgee
 */
function genesischild_custom_logo( $title, $inside, $wrap ) {
	// Check to see if the Custom Logo function exists and set what goes inside the wrapping tags.
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) :
		$logo = the_custom_logo();
	else :
	 	$logo = get_bloginfo( 'name' );
	endif;
 	 // Use this wrap if no custom logo - wrap around the site name
	 $inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( home_url() ), esc_attr( get_bloginfo( 'name' ) ), $logo );
	 // Determine which wrapping tags to use - changed is_home to is_front_page to fix Genesis bug.
	 $wrap = is_front_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';
	 // A little fallback, in case an SEO plugin is active - changed is_home to is_front_page to fix Genesis bug.
	 $wrap = is_front_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;
	 // And finally, $wrap in h1 if HTML5 & semantic headings enabled.
	 $wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;
	 $title = sprintf( '<%1$s %2$s>%3$s</%1$s>', $wrap, genesis_attr( 'site-title' ), $inside );
	 return $title;
}
add_filter( 'genesis_seo_title','genesischild_custom_logo', 10, 3 );
/**
 * Add class for screen readers to site description.
 * This will keep the site description mark up but will not have any visual presence on the page
 * This runs if their is a header image set in the Customiser.
 *
 * @param string $attributes Add screen reader class if custom logo is set.
 *
 * @author @_neilgee
 */
 function genesischild_add_site_description_class( $attributes ) {
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$attributes['class'] .= ' screen-reader-text';
		return $attributes;
	}
	else {
		return $attributes;
	}
 }
 add_filter( 'genesis_attr_site-description', 'genesischild_add_site_description_class' );
 
 /* Removing custom title/logo metabox from Genesis theme options page.
 * See http://www.billerickson.net/code/remove-metaboxes-from-genesis-theme-settings/
 * Updated to use $_genesis_admin_settings instead of legacy variable in Bill's example.
 */
add_action( 'genesis_theme_settings_metaboxes', 'be_remove_metaboxes' );
function be_remove_metaboxes( $_genesis_admin_settings ) {
	remove_meta_box( 'genesis-theme-settings-header', $_genesis_admin_settings, 'main' );
}
/*
 * Removing custom title/logo metabox from Genesis customizer
 * See https://developer.wordpress.org/themes/advanced-topics/customizer-api/
 */
add_action( 'customize_register', 'es_theme_customize_register', 99 ); // Priority had to be last for this to work
function es_theme_customize_register( $wp_customize ) {
	$wp_customize->remove_control('blog_title');
   
}

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
