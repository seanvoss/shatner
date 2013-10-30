<?php
/*
Plugin Name: WooCommerce - Name your own price
Plugin URI: http://blog.seanvoss.com/product/shatner
Description: Allows users to set their own price (can also be used for donations) 
Version: 0.5
Author: Sean Voss
Author URI: http://seanvoss.com/

*/

/*
 * Title   : WooCommerce Name your own price
 * Author  : Sean Voss
 * Url     : http://blog.seanvoss.com/product/shatner
 * License : http://github.com/seanvoss/shatner/LICENSE
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