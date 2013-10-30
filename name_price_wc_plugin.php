<?
class SV_WC_Donation extends WC_Cart
{

    public function __construct()
    {
    //    add_filter('woocommerce_get_price_html',           array($this,  'add_price_html'));
        add_filter('woocommerce_locate_template',       array($this, 'template_override'),10,3);
        add_action('woocommerce_product_options_pricing',  array($this,  'add_donation_checkbox'));
        add_action('save_post',                            array($this,  'set_named_price'));
        add_action('woocommerce_add_to_cart',              array($this,  'add_to_cart_hook'));
        add_action('woocommerce_before_calculate_totals',  array($this,  'add_custom_price' ));
        add_action('init', array($this, 'init_css'));
    }

    public function template_override($template, $template_name, $template_path ) {
    // Modification: Get the template from this plugin, if it exists

    $plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) ). '/templates/';
    
    if ( file_exists( $plugin_path . $template_name ) )
    {
      $template = $plugin_path . $template_name;
      return $template;
    }

    return $template;

    }

    public function init_css()
    {
        wp_register_style('donation_css', plugins_url('custom_styles.css',__FILE__ ), false, '1.0.1', 'all');
        wp_enqueue_style( 'donation_css' );
    }

    public function add_to_cart_hook($key)
    {
        global $woocommerce;
        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) 
        {
            if($cart_item_key == $key)
            {
                $values['data']->set_price(abs($_POST['price']));
                $woocommerce->session->__set($key .'_named_price', abs($_POST['price']));
            }
        }

    return $key;
    }


    public function set_named_price($post)
    {
        if($_POST['_own_price']){
            if(!get_post_meta($_POST['post_ID'], '_own_price', true )){
                add_post_meta($_POST['post_ID'], '_own_price', $_POST['_own_price']);
            } else {
                update_post_meta($_POST['post_ID'], '_own_price', $_POST['_own_price']);
            }
        } else {
            if(get_post_meta($_POST['post_ID'], '_own_price', true )){
                delete_post_meta($_POST['post_ID'], '_own_price');
            }
        }
    }

    public function add_donation_checkbox($content)
    {
       global $post;
       woocommerce_wp_checkbox(array('id' => '_own_price', 'class' => 'wc_own_price short', 'label' => __( 'Name your own price', 'woocommerce' ), 'value'=> 'yes', 'cbvalue' => get_post_meta($post->ID, '_own_price', true ) ? 'yes' : 'no'));
    }

    public function add_price_html($content)
    {
        global $post;
        var_dump($content);
        if(!$post)
            return;
        $post = get_post_meta($post->ID, '_own_price', true);
        
        if($post == 'yes')
        {
            print "
            <script>
                jQuery(function($){
                    $('.cart').submit(function(){
                        $('#price').clone().attr('type','hidden').appendTo($('form.cart'));
                        return;
                    });
                });
            </script>";
            return sprintf("<div class='name_price'><label>%s</label><input name='price' id='price' type='text' /></div>", "Name your own price");
        }
        return ;
        
    }

    public function add_custom_price( $cart_object ) {
        global $woocommerce;
        foreach ( $cart_object->cart_contents as $key => $value ) {

                $named_price = $woocommerce->session->__get($key .'_named_price');
                if($named_price)
                {
                    $value['data']->price = $named_price;
                }
        }
    }

}
new SV_WC_Donation();
