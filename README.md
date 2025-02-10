# Woo Gold Surcharge Plugin

## Description
Woo Gold Surcharge is a custom WooCommerce plugin that dynamically applies a surcharge to product prices based on the selected metal attributes. The surcharge can be customized for the US and Canadian markets.

## Features
- Add a surcharge to product prices based on the **pa_metal** attribute.
- Separate surcharge rates for **US** and **Canada**.
- Dynamically updates prices on **shop, product pages, cart, and checkout**.
- Works for **simple and variable** products.
- Admin settings to select affected attributes and set surcharge values.
- CSS styling for selected attributes.

## Installation
1. **Download & Zip the Plugin**
   - Ensure all plugin files are inside a folder named `woo-gold-surcharge`.
   - Create a ZIP file. Use WinRAR, 7zip, or which one you prefer to zip the file. Or just write the code below:
     ```sh
     zip -r woo-gold-surcharge.zip woo-gold-surcharge/
     ```

2. **Upload the Plugin to WordPress**
   - Navigate to `WordPress Admin > Plugins > Add New`.
   - Click `Upload Plugin`.
   - Select `woo-gold-surcharge.zip` and click `Install Now`.
   - Activate the plugin after installation.

## Usage
### 1. Configure the Surcharge Settings
- Go to `WordPress Admin > Products > Gold Surcharge`.
- Set the **Gold Surcharge (US)** and **Gold Surcharge (CAN)** values.
- Select which **Excluded Attributes for the Gold Surcharge** attributes will have the surcharge excluded.
- Click `Save Settings`.

### 2. How the Surcharge Works
- The plugin automatically **applies the surcharge** to product prices **only if the product’s attribute is selected in the settings**.
- Prices update dynamically across:
  - Shop page
  - Single product page
  - Cart and checkout
- Canadian domains (`.ca`) use the **Gold Surcharge (CAN)** value.
- All other domains use the **Gold Surcharge (US)** value.

### 3. CSS Customization
- The plugin includes a **CSS trick** where selected attributes in the admin panel will have a color change effect when checked.
- To modify styles, edit the CSS file: `woo-gold-surcharge/wgs_admin_style.php`.

## Contributing
Feel free to **fork** this repository, submit **pull requests**, or report issues.

## License
This plugin is licensed under the **MIT License**.

