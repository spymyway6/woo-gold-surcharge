<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get the stored surcharge values
$base_surcharge_us = floatval(get_option('woo_gold_surcharge_us', '0'));
$base_surcharge_can = floatval(get_option('woo_gold_surcharge_can', '0'));

// Targeted metals that don't need the surcharge
$excluded_metals = get_option('woo_gold_surcharge_selected_metals', []);

// Check if the site domain is Canadian (.ca)
$is_canada = strpos($_SERVER['HTTP_HOST'], '.ca') !== false;

// Select the appropriate surcharge based on the domain
$base_surcharge = $is_canada ? $base_surcharge_can : $base_surcharge_us;

// Simple, grouped and external products
add_filter('woocommerce_product_get_price', 'apply_gold_surcharge', 99, 2);
add_filter('woocommerce_product_get_regular_price', 'apply_gold_surcharge', 99, 2);

// Variations
add_filter('woocommerce_product_variation_get_regular_price', 'apply_gold_surcharge', 99, 2);
add_filter('woocommerce_product_variation_get_price', 'apply_gold_surcharge', 99, 2);

// Price surcharge on variation price range display
add_filter('woocommerce_variation_prices_price', 'custom_price_range_variation', 99, 3);
add_filter('woocommerce_variation_prices_regular_price', 'custom_price_range_variation', 99, 3);
add_filter('woocommerce_get_price_html', 'custom_variation_price_range_display', 99, 2);

function apply_gold_surcharge($price, $product){
    global $base_surcharge, $excluded_metals;

    if ($base_surcharge <= 0 || empty($price)) {
        return $price; // No surcharge if not set
    }

    // Get metal attribute directly
    $metal = $product->get_attribute('pa_metal');
    if (!in_array($metal, $excluded_metals, true)) {
        $surcharge = $price * ($base_surcharge / 100);
        return $price + $surcharge;
    }
    return $price;
}

function custom_price_range_variation($price, $variation, $product){
    return apply_gold_surcharge($price, $product);
}

// Fix price range display on shop & product pages
function custom_variation_price_range_display($price_html, $product){
    if ($product->is_type('variable')) {
        $prices = array();

        // Get all variations
        foreach ($product->get_available_variations() as $variation) {
            $variation_id = $variation['variation_id'];
            $variation_obj = wc_get_product($variation_id);

            if ($variation_obj) {
                $prices[] = $variation_obj->get_price();
            }
        }

        if (!empty($prices)) {
            $min_price = min($prices);
            $max_price = max($prices);

            // Format the price range correctly
            $price_html = wc_format_price_range($min_price, $max_price);
        }
    }

    return $price_html;
}

// Force WooCommerce to recalculate prices
add_action('woocommerce_update_product_variation', 'recalculate_product_variation_price');
function recalculate_product_variation_price($variation_id){
    $product = wc_get_product($variation_id);
    if ($product && $product->is_type('variable')) {
        WC_Product_Variable::sync($product->get_id());
    }
}