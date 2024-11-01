<?php
class Super_Block_CSS {
    private $custom_css_collection = array();

    public function run() {
        add_filter('render_block', array($this, 'apply_custom_css_to_block'), 10, 2);
        add_action('wp_head', array($this, 'output_custom_css'), 100);
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_settings_assets'));
    }

    public function apply_custom_css_to_block($block_content, $block) {
        if (!empty($block['attrs']['customCSS']) && !empty($block['attrs']['customCSSId'])) {
            $custom_css = wp_strip_all_tags($block['attrs']['customCSS']);
            $custom_css_id = esc_attr($block['attrs']['customCSSId']);
            
            $block_content = $this->add_class_to_block_content($block_content, $custom_css_id);
            $this->custom_css_collection[$custom_css_id] = $custom_css;
            
            return $block_content;
        }
        return $block_content;
    }

    private function add_class_to_block_content($block_content, $class) {
        $pattern = '/<([a-zA-Z][a-zA-Z0-9]*)([^>]*)class="([^"]*)"([^>]*)>/';
        $replacement = '<$1$2class="$3 ' . $class . '"$4>';
        $modified_content = preg_replace($pattern, $replacement, $block_content, 1);
        
        if ($modified_content !== null && $modified_content !== $block_content) {
            return $modified_content;
        }
        
        $pattern = '/<([a-zA-Z][a-zA-Z0-9]*)([^>]*)>/';
        $replacement = '<$1 class="' . $class . '"$2>';
        $modified_content = preg_replace($pattern, $replacement, $block_content, 1);
        
        if ($modified_content !== null && $modified_content !== $block_content) {
            return $modified_content;
        }
        
        return '<div class="' . $class . '">' . $block_content . '</div>';
    }

    public function output_custom_css() {
        $global_css = get_option('super_block_css_global');
        echo "<style id='super-block-css-global'>\n";
        echo wp_strip_all_tags($global_css) . "\n";
        echo "</style>\n";

        if (!empty($this->custom_css_collection)) {
            echo "<style id='super-block-css-custom'>\n";
            foreach ($this->custom_css_collection as $id => $css) {
                echo ".$id { $css }\n";
            }
            echo "</style>\n";
        }
    }

    public function add_settings_page() {
        add_theme_page(
            __('Super Blocks CSS Settings', 'super-block-css'),
            __('Super Blocks CSS', 'super-block-css'),
            'manage_options',
            'super-block-css-settings',
            array($this, 'render_settings_page')
        );
    }

    public function register_settings() {
        register_setting('super_block_css_settings', 'super_block_css_global');
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('super_block_css_settings');
                do_settings_sections('super_block_css_settings');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Global CSS', 'super-block-css'); ?></th>
                        <td>
                            <textarea name="super_block_css_global" id="super_block_css_global" rows="10" cols="50" class="large-text code"><?php echo esc_textarea(get_option('super_block_css_global')); ?></textarea>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function enqueue_editor_assets() {
        wp_enqueue_script(
            'super-block-css-editor',
            SUPER_BLOCK_CSS_PLUGIN_URL . 'js/editor.js',
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-block-editor', 'wp-codemirror'),
            SUPER_BLOCK_CSS_VERSION,
            true
        );

        wp_enqueue_style(
            'super-block-css-editor-style',
            SUPER_BLOCK_CSS_PLUGIN_URL . 'css/editor-style.css',
            array('wp-codemirror'),
            SUPER_BLOCK_CSS_VERSION
        );
    }

    public function enqueue_settings_assets($hook) {
        if ('appearance_page_super-block-css-settings' !== $hook) {
            return;
        }

        wp_enqueue_code_editor(array('type' => 'text/css'));
        wp_enqueue_script('wp-theme-plugin-editor');
        wp_enqueue_style('wp-codemirror');

        wp_enqueue_script(
            'super-block-css-settings',
            SUPER_BLOCK_CSS_PLUGIN_URL . 'js/settings.js',
            array('jquery', 'wp-theme-plugin-editor'),
            SUPER_BLOCK_CSS_VERSION,
            true
        );

        wp_enqueue_style(
            'super-block-css-settings-style',
            SUPER_BLOCK_CSS_PLUGIN_URL . 'css/editor-style.css',
            array(),
            SUPER_BLOCK_CSS_VERSION
        );
    }
}