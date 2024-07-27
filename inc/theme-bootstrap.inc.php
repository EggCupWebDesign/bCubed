<?php

/**
 * Theme Bootstrap
 * ---
 * Set up the theme environment, overriding and removing functions as
 * necessary, and queuing static resources.
 * php version 8.2.14
 *
 * @category   Includes
 * @package    b3
 * @author     Ian Pegg <hello@eggcupweb.com>
 * @license    GNU/GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt
 * @link       https://eggcupwebdesign.com
 */

namespace b3\WPTheme\ThemeBootstrap;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * This script registers following functions with WP action hooks/filters:
 */
add_action('after_setup_theme', __NAMESPACE__ . '\\Theme_bootstrap');
add_action('after_setup_theme', __NAMESPACE__ . '\\Define_Block_styles');
add_action('after_setup_theme', __NAMESPACE__ . '\\Enqueue_Block_styles');
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\Load_Static_resources');
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\Dequeue_assets', 9999);
add_action('enqueue_block_assets', __NAMESPACE__ . '\\Enqueue_Override_styles');
add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\\Enqueue_Block_Editor_assets');
add_action('after_setup_theme', __NAMESPACE__ . '\\Enqueue_Editor_styles');


/**
 * Theme Bootstrap
 * ---
 * Adds / removes theme supports and registers features.
 * This function is attached to the 'after_setup_theme' action hook.
 *
 * @uses add_theme_support()
 * @uses add_post_type_support()
 * @uses register_block_style()
 *
 * @return void
 */
function Theme_bootstrap()
{
    /**
     * Remove default posts and comments RSS feed links from <head>:
     */
    remove_theme_support('automatic-feed-links');

    /**
     * Remove core block patterns as we have our own:
     */
    remove_theme_support('core-block-patterns');

    /**
     * Add excerpt support to pages:
     */
    add_post_type_support('page', 'excerpt');

    /**
     * Adjust the 'big_image_size_threshold' to 4k resolution (3840px):
     */
    add_filter('big_image_size_threshold', function() { return 3840; }, 999, 1);

    /**
     * Re-enable the theme customizer:
     */
    add_action('customize_register', '__return_true');

    /**
     * Declare WooCommerce support:
     */
    add_theme_support(
        'woocommerce', [
            'thumbnail_image_width' => 150,
            'single_image_width'    => 300
        ]
    );

    /**
     * Remove <p> and <br/> from Contact Form 7:
     */
    add_filter('wpcf7_autop_or_not', '__return_false');
}


/**
 * Define custom block styles
 * ---
 * By defining custom block styles, the styling variation becomes
 * selectable in the editor - without the need to manually add custom
 * classes to the block.
 *
 * @link https://fullsiteediting.com/lessons/custom-block-styles/
 *
 * @return null
 */
function Define_Block_styles()
{
    $Str_theme_text_domain = wp_get_theme()->get('TextDomain');

    /**
     * Define custom block styles using a multi-dimensional array, with the
     * outer array keyed on the block package/name slug and the inner array
     * keyed on the name of the custom block style:
     */
    $Arr_custom_block_styles = [
        'core/button' => [
            'secondary' => [
                'name'  => 'secondary',
                'label' => __('Secondary', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-button-secondary'
            ]
        ],
        'core/cover' => [
            'hero' => [
                'name'  => 'cover-hero',
                'label' => __('Hero', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-cover-hero'
            ]
        ],
        'core/group' => [
            'well-sm' => [
                'name'  => 'well-sm',
                'label' => __('Small Well', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-group-well-sm'
            ],
            'well' => [
                'name'  => 'well',
                'label' => __('Well', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-group-well'
            ],
            'well-lg' => [
                'name'  => 'well-lg',
                'label' => __('Large Well', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-group-well-lg'
            ],
            'header' => [
                'name'  => 'header',
                'label' => __('Header', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-group-header'
            ],
            'footer' => [
                'name'  => 'footer',
                'label' => __('Footer', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-group-footer'
            ],
            'section' => [
                'name'  => 'section',
                'label' => __('Section', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-group-section'
            ],
        ],
        'core/list' => [
            'inline' => [
                'name'  => 'list-inline',
                'label' => __('Inline', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-list-inline'
            ],
            'unstyled' => [
                'name'  => 'list-unstyled',
                'label' => __('Unstyled', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-list-unstyled'
            ]
        ],
        'core/paragraph' => [
            'indented' => [
                'name'  => 'paragraph-indented',
                'label' => __('Indented', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-paragraph-indented'
            ]
        ]
    ];
    foreach ( $Arr_custom_block_styles as $Str_block_name => $Arr_block_styles ) {
        foreach ( $Arr_block_styles as $Arr_style_properties ) {
            register_block_style($Str_block_name, $Arr_style_properties);
        }
    }
}


/**
 * Enqueue custom styles for blocks
 * ---
 * Each stylesheet will only be included on pages that use the
 * corresponding block.
 *
 * @link https://developer.wordpress.org/themes/block-themes/block-theme-setup/#including-css-for-block-styles
 *
 * @return null
 */
function Enqueue_Block_styles()
{
    $Str_block_css_path = 'dist/css/blocks/';
    $Arr_styled_blocks = [
        'core' => [
            'avatar',
            'button',
            'cover',
            'group',
            'image',
            'list',
            'navigation',
            'paragraph',
            'post-date',
            'pullquote',
            'quote',
            'search',
            'table'
        ],
        'yoast' => [
            'how-to-block'
        ],
        'yoast-seo' => [
            'breadcrumbs'
        ]
    ];

    foreach ( $Arr_styled_blocks as $Arr_package_name => $Arr_block_names ) {
        foreach ( $Arr_block_names as $Str_block_name ) {
            $Str_file_rel_path = $Str_block_css_path . $Str_block_name . '.css';
            $Arr_args = [
                'handle' => wp_get_theme()->get('TextDomain') . '-' . $Str_block_name,
                'src'    => get_theme_file_uri($Str_file_rel_path),
                'deps'   => [],
                'ver'    => filemtime(get_theme_file_path($Str_file_rel_path)),
                'media'  => ''
            ];
            wp_enqueue_block_style($Arr_package_name . '/' . $Str_block_name, $Arr_args);
        }
    }
}


/**
 * Triggers the enqueuing of CSS and JS assets.
 *
 * @return void
 */
function Load_Static_resources()
{
    // Enqueue stylesheets
    Enqueue_assets(Define_Style_registrations(), 'styles');

    // Enqueue scripts
    Enqueue_assets(Define_Script_registrations(), 'scripts');
}


/**
 * Returns an array of stylesheets that are to be registered with
 * core, complete with the appropriate settings.
 *
 * @return Array Defined stylesheets, with settings.
 */
function Define_Style_registrations()
{
    $Arr_enqueue_styles = [];
    $Str_theme_css_path = '/dist/css/';
    $Str_text_domain = wp_get_theme()->get('TextDomain');

    $Arr_enqueue_styles = [
        $Str_text_domain . '-theme' => [
            'path'          => $Str_theme_css_path . 'common.css',
            'dependencies'  => [],
            'options'       => 'all',
        ]
    ];

    if (is_user_logged_in()) {
        $Arr_enqueue_styles[ $Str_text_domain . '-admin' ] = [
            'path' => $Str_theme_css_path . 'admin/logged-in.css',
            'dependencies'  => [ $Str_text_domain . '-theme' ],
            'options'       => 'all',
        ];
    }

    return $Arr_enqueue_styles;
}


/**
 * Returns an array of stylesheets that are to be registered with
 * core, complete with the appropriate settings.
 *
 * @return Array Defined stylesheets, with settings.
 */
function Define_Script_registrations()
{
    $Arr_enqueue_scripts = [];
    $Str_theme_js_path = '/dist/js/';
    
    $Arr_enqueue_scripts = [
        'app' => [
            'path'          => $Str_theme_js_path . 'app.js',
            'dependencies'  => [],
            'options'       => [ 'strategy' => 'defer' ]
        ]
    ];

    return $Arr_enqueue_scripts;
}


/**
 * Accepts a multidimensional array of assets to enqueue, then calls
 * either wp_enqueue_style or wp_enqueue_script via a callback
 * function, according to whether an array of styles or an array
 * of scripts was provided.
 *
 * Also handles cachebusting by checking filemtimes of assets.
 *
 * @param Array  $Arr_enqueue_list List of assets to enqueue
 * @param String $Str_type         Type of assets being enqueued (scripts or styles)
 *
 * @return void
 */
function Enqueue_assets( $Arr_enqueue_list = [], $Str_type = 'styles' )
{
    if (!empty($Arr_enqueue_list)) {
        $Str_library_root   = get_stylesheet_directory();
        $Str_library_uri    = get_stylesheet_directory_uri();
        $Str_modified_time  = filemtime(__FILE__);

        if ('styles' === $Str_type) {
            $Call_function      = 'wp_enqueue_style';
            $Str_library_dir    = '';
        } else {
            $Call_function      = 'wp_enqueue_script';
            $Str_library_dir    = '';
        }

        foreach ($Arr_enqueue_list as $Str_handle => $Arr_properties) {
            /**
             * If the path validates as a URL with a path then it is an external resource.
             * Simply use the URL once validated:
             */
            $Str_asset_uri = filter_var(
                $Arr_properties[ 'path' ],
                FILTER_VALIDATE_URL,
                FILTER_FLAG_PATH_REQUIRED
            );

            /**
             * If it's not a valid URL, filter_var will return false,
             * so build our internal URL instead:
             */
            if (!$Str_asset_uri) {
                $Str_full_path
                    = $Str_library_root
                    . $Str_library_dir
                    . $Arr_properties[ 'path' ];

                if (file_exists($Str_full_path)) {
                    $Str_modified_time = filemtime($Str_full_path);

                    $Str_asset_uri
                        = $Str_library_uri
                        . $Str_library_dir
                        . $Arr_properties[ 'path' ];
                }
            }

            /**
             * Finally, call the appropriate WP asset registration function:
             */
            $Call_function(
                $Str_handle,
                $Str_asset_uri,
                $Arr_properties[ 'dependencies' ],
                $Str_modified_time,
                $Arr_properties[ 'options' ]
            );
        }
    }
}


/**
 * Enqueue CSS for the editor
 * ---
 * The Gutenberg editor and the front-end should ideally look
 * identical. Add front end CSS to the editor stylesheet to
 * achieve this.
 *
 * @return null
 */
function Enqueue_Editor_styles()
{
    add_theme_support('editor-styles');
    add_editor_style('dist/css/admin/editor-style.css');
}


/**
 * Enqueue block editor assets
 * ---
 * The assets enqueued by this function are only to be used in the editor -
 * not on the front-end.
 *
 * @return void
 */
function Enqueue_Block_Editor_assets()
{
    wp_enqueue_script(
		'b3-block-variations',
		get_template_directory_uri() . '/dist/js/admin/block-variations.js',
		[ 'wp-blocks' ]
	);
}


/**
 * Enqueues custom styles that are intended to override certain
 * core or plugin styles, and that need to declare specific
 * dependencies.
 *
 * @return void
 */
function Enqueue_Override_styles()
{
    $Arr_enqueue_styles = [];
    $Str_theme_css_path = '/dist/css/';
    $Str_text_domain = wp_get_theme()->get('TextDomain');

    if (has_block('contact-form-7/contact-form-selector')) {
        /**
         * Enqueue custom styles for CF7 and require the following
         * dependencies: CF7 and WP core button block styles for the
         * submit button
         */
        $Arr_enqueue_styles[ $Str_text_domain . '-form-cf7' ] = [
            'path'          => $Str_theme_css_path . 'form-cf7.css',
            'dependencies'  => [
                'contact-form-7',
                'wp-block-button',
                'wp-block-buttons'
            ],
            'options'       => 'all',
        ];
    }

    Enqueue_assets($Arr_enqueue_styles, 'styles');
}


/**
 * Dequeues assets either completely or conditionally.
 * This function is hooked in late so that it can
 * remove assets enqueued by plugins or core.
 *
 * @return void
 */
function Dequeue_assets()
{
    if (!has_block('contact-form-7/contact-form-selector')) {
        /**
         * Dequeue CF7 styles and script if there is no form block on
         * the current page:
         */
        wp_dequeue_style('contact-form-7');
        wp_dequeue_script('contact-form-7');
    }
}
