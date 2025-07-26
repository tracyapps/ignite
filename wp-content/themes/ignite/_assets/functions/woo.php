<?php
/**
 *
 * functions for modifying woo functionality
 *
 */

 add_filter( 'woocommerce_single_product_image_thumbnail_html', 'ITS_remove_product_link' );

 function ITS_remove_product_link( $html ) {
   return strip_tags( $html, '<div><img>' );
 }