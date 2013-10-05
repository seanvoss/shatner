<?php
/*
Plugin Name: WooCommerce - Name your own price
Plugin URI: http://seanvoss.com/wooprice
Description: Allows users to set their own price (can also be used for donations) 
Version: 0.1
Author: Sean Voss
Author URI: http://seanvoss.com/nameyourprice

*/

/*
 * Title   : WooCommerce Name your own price
 * Author  : Sean Voss
 * Url     : http://seanvoss.com/wooprice
 * License : http://seanvoss.com/wooprice/legal
 */

function sv_name_price_wc_init() 
{
    /**
     * Check if WooCommerce is active
     **/
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        include_once('name_price_wc_plugin.php');
    }
}

add_action('plugins_loaded', 'sv_name_price_wc_init', 10);
