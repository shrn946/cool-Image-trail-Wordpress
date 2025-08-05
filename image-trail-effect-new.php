<?php
/**
 * Plugin Name: Cool Image Trail Wordpress
 * Description: Adds shortcode [image-trail] to display animated image trail with image upload and preview in admin.
 * Version: 1.0
 * Author: WP_DESIGN LAB
 */

if (!defined('ABSPATH')) exit;

class ImageTrailPlugin {
    public function __construct() {
        // Hooks
        add_shortcode('image-trail', [$this, 'render_image_trail']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
    }

    public function enqueue_assets() {
        $plugin_url = plugin_dir_url(__FILE__);
        wp_enqueue_style('image-trail-style-main', $plugin_url . 'css/main-style.css');
        wp_enqueue_script('image-trail-imagesloaded', $plugin_url . 'js/imagesloaded.pkgd.min.js', [], null, true);
        wp_enqueue_script('image-trail-tweenmax', $plugin_url . 'js/TweenMax.min.js', [], null, true);
        wp_enqueue_script('image-trail-solve', $plugin_url . 'js/solve.js', ['image-trail-imagesloaded', 'image-trail-tweenmax'], null, true);
    }

    public function admin_scripts($hook) {
        if ($hook !== 'settings_page_image-trail-settings') return;
        wp_enqueue_media();
        wp_enqueue_script('image-trail-admin', plugin_dir_url(__FILE__) . 'js/admin.js', ['jquery'], null, true);
    }

    public function add_settings_page() {
        add_options_page(
            'Image Trail Settings',
            'Image Trail',
            'manage_options',
            'image-trail-settings',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('image_trail_group', 'image_trail_images');
        register_setting('image_trail_group', 'image_trail_title');
    }

   public function render_settings_page() {
    $images = get_option('image_trail_images', []);
    $title = get_option('image_trail_title', '');
    ?>
    <div class="wrap">
        <h1>Image Trail Settings</h1>
        <style>
            .image-trail-settings .image-trail-item {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                padding: 10px;
                border: 1px solid #ddd;
                background: #f9f9f9;
                max-width: 500px;
            }
            .image-trail-settings .image-trail-item img {
                width: 80px;
                height: auto;
                margin-right: 15px;
                border-radius: 5px;
            }
            .image-trail-settings .image-trail-item button {
                margin-left: auto;
            }
            .image-trail-settings #upload-images-button {
                margin-top: 10px;
            }
        </style>
        
        <form method="post" action="options.php" class="image-trail-settings">
            <?php settings_fields('image_trail_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Heading Content</th>
                    <td>
                        <input type="text" name="image_trail_title" value="<?php echo esc_attr($title); ?>" class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Upload Images</th>
                    <td>
                        <div id="image-trail-images-wrapper">
                            <?php
                            if (!empty($images)) {
                                foreach ($images as $img) {
                                    echo '<div class="image-trail-item">
                                            <img src="' . esc_url($img) . '" />
                                            <input type="hidden" name="image_trail_images[]" value="' . esc_url($img) . '" />
                                            <button type="button" class="button remove-image">Remove</button>
                                          </div>';
                                }
                            }
                            ?>
                        </div>
                        <button type="button" class="button" id="upload-images-button">Upload Images</button>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        Adds shortcode [image-trail] to display animated image trail with image upload and preview in admin.
    </div>
    <?php
}


public function render_image_trail($atts = [], $content = null) {
    // Disable preview in backend editor
    if (is_admin()) {
        return ''; // Or return the raw shortcode like: return '[image-trail]';
    }
	
        $images = get_option('image_trail_images', []);
        $title = get_option('image_trail_title', '');
        ob_start();
        ?>
        <div class="outer-me-sx">
            <div class="content">
                <?php
                if (!empty($images)) {
                    foreach ($images as $img_url) {
                        echo '<img class="content__img" src="' . esc_url($img_url) . '" alt="Image" />';
                    }
                }
                ?>
                <div class="slider_wrapper">
  <div class="slider_background_title"><?php echo esc_html($title); ?></div>
</div>

             
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}


new ImageTrailPlugin();
