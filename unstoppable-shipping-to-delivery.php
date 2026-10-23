<?php
/**
 * Plugin Name: Unstoppable Shipping to Delivery for WooCommerce
 * Plugin URI: https://unstoppablesolutions.app/plugins/unstoppable-shipping-to-delivery
 * Description: Automatically replaces all instances of "Shipping" with "Delivery" throughout WooCommerce on the frontend. Simple activate-and-go functionality with no configuration needed.
 * Version: 1.2.3
 * Author: Unstoppable Solutions
 * Author URI: https://unstoppablesolutions.app
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: unstoppable-shipping-to-delivery
 * Requires at least: 5.8
 * Tested up to: 7.1
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 10.4
 * Requires Plugins: woocommerce
 */

defined( 'ABSPATH' ) || exit;

/**
 * Declare HPOS compatibility
 */
add_action( 'before_woocommerce_init', function() {
    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

/**
 * Replace "Shipping" with "Delivery" across WooCommerce
 */
function ustd_change_shipping_to_delivery( $translated_text, $text, $domain ) {
    if ( $domain === 'woocommerce' || $domain === 'woo-gutenberg-products-block' || $domain === 'default' ) {
        $translated_text = str_replace( 'Shipping', 'Delivery', $translated_text );
        $translated_text = str_replace( 'shipping', 'delivery', $translated_text );
        $translated_text = str_replace( 'SHIPPING', 'DELIVERY', $translated_text );
    }
    return $translated_text;
}
add_filter( 'gettext', 'ustd_change_shipping_to_delivery', 20, 3 );
add_filter( 'gettext_with_context', 'ustd_change_shipping_to_delivery', 20, 3 );
add_filter( 'ngettext', 'ustd_change_shipping_to_delivery', 20, 3 );

/**
 * Replace shipping labels
 */
function ustd_change_shipping_label( $label ) {
    if ( ! is_string( $label ) ) {
        return $label;
    }
    $label = str_replace( 'Shipping', 'Delivery', $label );
    $label = str_replace( 'shipping', 'delivery', $label );
    return $label;
}
add_filter( 'woocommerce_shipping_package_name', 'ustd_change_shipping_label', 10, 1 );
add_filter( 'woocommerce_shipping_method_title', 'ustd_change_shipping_label', 10, 1 );
add_filter( 'woocommerce_cart_shipping_method_full_label', 'ustd_change_shipping_label', 10, 1 );
add_filter( 'woocommerce_no_shipping_available_html', 'ustd_change_shipping_label', 10, 1 );
add_filter( 'woocommerce_cart_no_shipping_available_html', 'ustd_change_shipping_label', 10, 1 );
add_filter( 'woocommerce_shipping_not_enabled_on_cart_html', 'ustd_change_shipping_label', 10, 1 );
add_filter( 'woocommerce_shipping_estimate_html', 'ustd_change_shipping_label', 10, 1 );
add_filter( 'woocommerce_shipping_destination_html', 'ustd_change_shipping_label', 10, 1 );

/**
 * Add JavaScript fallback for any remaining shipping text
 */
function ustd_add_js_fallback() {
    if ( is_admin() ) {
        return;
    }
    ?>
    <script>
    (function() {
        function replaceShippingText() {
            var walker = document.createTreeWalker(
                document.body,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );
            var node;
            while (node = walker.nextNode()) {
                if (node.nodeValue.match(/shipping/i)) {
                    node.nodeValue = node.nodeValue
                        .replace(/SHIPPING/g, 'DELIVERY')
                        .replace(/Shipping/g, 'Delivery')
                        .replace(/shipping/g, 'delivery');
                }
            }
        }
        
        // Run on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', replaceShippingText);
        } else {
            replaceShippingText();
        }
        
        // Run after AJAX updates (for WooCommerce cart/checkout)
        jQuery(document.body).on('updated_cart_totals updated_checkout updated_shipping_method', function() {
            setTimeout(replaceShippingText, 100);
        });
        
        // MutationObserver for dynamic content
        var observer = new MutationObserver(function(mutations) {
            replaceShippingText();
        });
        observer.observe(document.body, { childList: true, subtree: true });
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'ustd_add_js_fallback', 999 );

/**
 * Add plugin action links
 */
function ustd_plugin_action_links( $links ) {
    // Check if Pro version is installed
    if ( ! is_plugin_active( 'unstoppable-shipping-text-manager/unstoppable-shipping-text-manager.php' ) ) {
        $pro_link = '<a href="https://unstoppablesolutions.app/plugins/unstoppable-shipping-text-manager" target="_blank" style="color: #6B46C1; font-weight: 600;">Get Pro</a>';
        array_unshift( $links, $pro_link );
    }
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ustd_plugin_action_links' );

/**
 * Show admin notice about Pro version (dismissible, once per month)
 */
function ustd_admin_notice_pro() {
    // Don't show if Pro is installed
    if ( is_plugin_active( 'unstoppable-shipping-text-manager/unstoppable-shipping-text-manager.php' ) ) {
        return;
    }
    
    // Only show on plugins page or WooCommerce pages
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->id, array( 'plugins', 'woocommerce_page_wc-settings' ), true ) ) {
        return;
    }
    
    // Check if dismissed
    $dismissed = get_option( 'ustd_pro_notice_dismissed', 0 );
    if ( $dismissed && ( time() - $dismissed ) < MONTH_IN_SECONDS ) {
        return;
    }
    ?>
    <div class="notice notice-info is-dismissible ustd-pro-notice">
        <p>
            <strong>Unstoppable Shipping to Delivery</strong> — 
            Need "Pickup", "Billing", or custom text instead of "Delivery"? 
            <a href="https://unstoppablesolutions.app/plugins/unstoppable-shipping-text-manager" target="_blank">Upgrade to Pro</a> for more options!
        </p>
    </div>
    <script>
    jQuery(document).on('click', '.ustd-pro-notice .notice-dismiss', function() {
        jQuery.post(ajaxurl, { action: 'ustd_dismiss_pro_notice', _wpnonce: '<?php echo esc_js( wp_create_nonce( 'ustd_dismiss_notice' ) ); ?>' });
    });
    </script>
    <?php
}
add_action( 'admin_notices', 'ustd_admin_notice_pro' );

/**
 * AJAX handler to dismiss Pro notice
 */
function ustd_dismiss_pro_notice() {
    check_ajax_referer( 'ustd_dismiss_notice' );
    update_option( 'ustd_pro_notice_dismissed', time() );
    wp_die();
}
add_action( 'wp_ajax_ustd_dismiss_pro_notice', 'ustd_dismiss_pro_notice' );
