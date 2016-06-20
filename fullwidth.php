<?php
/**
 * This file adds the Full Width Page Builder
 * template to the SS mobile first theme.
 *
 * @author Andrew Fife
 * @package Mobile First Theme
 * @subpackage Customizations
 */

/*
Template Name: FullWidth-PB
*/

//* Add custom body class to the head
add_filter( 'body_class', 'ucc_add_body_class' );
function ucc_add_body_class( $classes ) {

	$classes[] = 'fullwidth-pb';
	return $classes;

}



//* Remove navigation
remove_action( 'genesis_after_header', 'genesis_do_subnav' );

//* Remove breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs');

//* Remove Entry Header
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );


//* Run the Genesis loop
genesis();
