<?php
/**
 * Plugin Name: Super Blocks CSS - Custom CSS for Gutenberg Blocks
 * Plugin URI: https://wordpress.org/plugins/super-custom-css/
 * Description: Adds custom CSS options to Gutenberg blocks and global CSS settings.
 * Version: 2.0.0
 * Author: Alii Raja
 * Author URI: https://wpmario.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: super-block-css
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('SUPER_BLOCK_CSS_VERSION', '2.0.0');
define('SUPER_BLOCK_CSS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SUPER_BLOCK_CSS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include the main plugin class
require_once SUPER_BLOCK_CSS_PLUGIN_DIR . 'includes/class-super-blocks-css.php';

// Initialize the plugin
function run_super_block_css() {
    $plugin = new Super_Block_CSS();
    $plugin->run();
}
add_action('plugins_loaded', 'run_super_block_css');

// Add settings link on plugin page
function super_block_css_settings_link($links) {
    $settings_link = '<a href="themes.php?page=super-block-css-settings">' . __('Settings', 'super-block-css') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'super_block_css_settings_link');