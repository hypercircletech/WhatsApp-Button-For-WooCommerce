<?php
/**
 * Plugin Name: WhatsApp Button For WooCommerce
 * Description: Adds a WhatsApp button to WooCommerce product pages after the add-to-cart button.
 * Version: 1.2
 * Author: Rownok
 * Author URI: https://github.com/rownok860
 */

if (!defined('ABSPATH')) {
    exit;
}

// Add the WhatsApp button after the "Add to Cart" button
add_action('woocommerce_after_add_to_cart_button', 'wpb_add_whatsapp_button');
function wpb_add_whatsapp_button() {
    $whatsapp_number = get_option('wpb_whatsapp_number');
    if (!$whatsapp_number) {
        return;
    }

    global $product;
    $product_name = $product->get_name();
    $product_url = get_permalink($product->get_id());
    $whatsapp_url = "https://wa.me/{$whatsapp_number}?text=" . urlencode("Hi, I am interested in this product: {$product_name} - {$product_url}");

    echo '<a href="' . esc_url($whatsapp_url) . '" target="_blank" class="wpb-whatsapp-button">
            <span class="wpb-whatsapp-icon"></span> Message on WhatsApp
          </a>';
}

// Create a settings page in the admin dashboard
add_action('admin_menu', 'wpb_create_settings_page');
function wpb_create_settings_page() {
    add_options_page(
        'WhatsApp Button Settings',
        'WhatsApp Button',
        'manage_options',
        'wpb-settings',
        'wpb_settings_page'
    );
}

function wpb_settings_page() {
    ?>
    <div class="wrap">
        <h1>WhatsApp Button Settings</h1>
        <p><strong>Note:</strong> Enter the WhatsApp number with the country code, without the "+" symbol. For example, enter <code>15551234567</code> for a U.S. number.</p>
        <form method="post" action="options.php">
            <?php
            settings_fields('wpb-settings-group');
            do_settings_sections('wpb-settings-group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">WhatsApp Number</th>
                    <td><input type="text" name="wpb_whatsapp_number" value="<?php echo esc_attr(get_option('wpb_whatsapp_number')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings for the plugin
add_action('admin_init', 'wpb_register_settings');
function wpb_register_settings() {
    register_setting('wpb-settings-group', 'wpb_whatsapp_number');
}

// Enqueue styles for the WhatsApp button
add_action('wp_enqueue_scripts', 'wpb_enqueue_styles');
function wpb_enqueue_styles() {
    wp_register_style('wpb-whatsapp-style', plugins_url('style.css', __FILE__));
    wp_enqueue_style('wpb-whatsapp-style');
}
