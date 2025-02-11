<?php
    /*
        Plugin Name: Woo Gold Surcharge
        Description: Adds a surcharge feature to WooCommerce.
        Version: 1.0
        Author: Jewelry Store Marketing
        Text Domain: woo-gold-surcharge
    */

    if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly
    }

    // Include additional plugin files
    require_once plugin_dir_path(__FILE__) . 'update_price_surcharge.php';

    // Register the admin menu under WooCommerce Products
    function woo_add_gold_surcharge_menu() {
        add_submenu_page(
            'edit.php?post_type=product', // Parent slug (under Products)
            'Gold Surcharge', // Page title
            'Gold Surcharge', // Menu title
            'manage_woocommerce', // Capability
            'woo-gold-surcharge', // Menu slug
            'woo_gold_surcharge_settings_page' // Callback function
        );
    }
    add_action('admin_menu', 'woo_add_gold_surcharge_menu');

    // Admin Page Callback Function
    function woo_gold_surcharge_settings_page() {
        // Get all `pa_metal` attribute terms dynamically
        $metal_terms = get_terms([
            'taxonomy'   => 'pa_metal',
            'hide_empty' => false,
        ]);
        // Save settings when the form is submitted
        if (isset($_POST['woo_gold_surcharge_save'])) {
            update_option('woo_gold_surcharge_us', sanitize_text_field($_POST['gold_surcharge_us']));
            update_option('woo_gold_surcharge_can', sanitize_text_field($_POST['gold_surcharge_can']));
            $selected_metals = isset($_POST['woo_gold_surcharge_selected_metals']) ? array_map('sanitize_text_field', $_POST['woo_gold_surcharge_selected_metals']) : [];
            update_option('woo_gold_surcharge_selected_metals', $selected_metals);
            echo '<div class="updated"><p>Settings Saved.</p></div>';
        }

        // Get saved values
        $gold_us = get_option('woo_gold_surcharge_us', '');
        $gold_can = get_option('woo_gold_surcharge_can', '');
        $selected_metals = get_option('woo_gold_surcharge_selected_metals', []); ?>
        
        <div class="plugin-wrapper wrap">
            <h1>
                <span>Gold Surcharge Settings</span>
                <small>This plugin updates the value of Gold using the Gold Surcharge. There are two types of Gold Surcharge: US and CAN. <br>
                All Gold prices set under product variations are affected, except for <b>'Sterling Silver'</b> and <b>'Gold Plate.'</b> The default value for all fields is 0.</small>
            </h1>
            <form method="post">
                <div class="wgs-2-columns">
                    <div class="wgs-form-group">
                        <label for="gold_surcharge_us">Gold Surcharge (US)</label>
                        <input type="number" min="0" name="gold_surcharge_us" id="gold_surcharge_us" value="<?php echo esc_attr($gold_us); ?>" class="wgs-form-control" placeholder="0">
                        <small>Default value is: 0</small>
                        <span class="float-icon">%</span>
                    </div>

                    <div class="wgs-form-group">
                        <label for="gold_surcharge_can">Gold Surcharge (CAN)</label>
                        <input type="number" min="0" name="gold_surcharge_can" id="gold_surcharge_can" value="<?php echo esc_attr($gold_can); ?>" class="wgs-form-control" placeholder="0">
                        <small>Default value is: 0</small>
                        <span class="float-icon">%</span>
                    </div>
                </div>
                <div class="wgs-row">
                    <div class="wgs-form-group form-checkbox-group">
                        <h6>Excluded Attributes for the Gold Surcharge:</h6>
                        <div class="fcg-wrapper">
                            <?php foreach ($metal_terms as $term){ ?>
                                <label class="default-label">
                                    <input type="checkbox" name="woo_gold_surcharge_selected_metals[]" value="<?php echo esc_attr($term->name); ?>"
                                    <?php checked(in_array($term->name, (array) $selected_metals, true)); ?>>
                                    <span><?=esc_html($term->name); ?></span>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="wgs-form-group">
                    <?php submit_button('Save Settings', 'primary woo-gold-btn', 'woo_gold_surcharge_save'); ?>
                </div>
            </form>
        </div>
    <?php }

    // Include CSS file
    // // Enqueue Admin Styles
    function woo_gold_surcharge_enqueue_styles($hook) {
        if ($hook !== 'product_page_woo-gold-surcharge') {
            return;
        }
        wp_enqueue_style(
            'woo-gold-surcharge-css',
            plugins_url('assets/styles.css', __FILE__),
            array(),
            '1.0.0'
        );
    }
    add_action('admin_enqueue_scripts', 'woo_gold_surcharge_enqueue_styles');

?>