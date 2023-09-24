<?php

/**
 * Theme Bootstrap
 * ---
 * Set up the theme environment, overriding and removing functions as
 * necessary, and queuing static resources.
 * php version 7.4.15
 *
 * @category   Includes
 * @package    b3
 * @author     Ian Pegg <ian@eggcupweb.com>
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
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\Enqueue_Common_styles');
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
function Define_Block_styles() {
    $Str_theme_text_domain = wp_get_theme()->get('TextDomain');

    /**
     * Define custom block styles using a multi-dimensional array, with the
     * outer array keyed on the block package/name slug and the inner array
     * keyed on the name of the custom block style:
     */
    $Arr_custom_block_styles = [
        'core/cover' => [
            'hero' => [
                'name'  => 'cover-hero',
                'label' => __('Hero', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-cover-hero'
            ]
        ],
        'core/group' => [
            'callout' => [
                'name'  => 'callout',
                'label' => __('Callout', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-group-callout'
            ],
            'cta' => [
                'name'  => 'call-to-action',
                'label' => __('CTA', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-group-cta'
            ],
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
                'style' => $Str_theme_text_domain . '-header-group'
            ],
            'footer' => [
                'name'  => 'footer',
                'label' => __('Footer', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-footer-group'
            ],
            'section' => [
                'name'  => 'section',
                'label' => __('Section', $Str_theme_text_domain),
                'style' => $Str_theme_text_domain . '-section-group'
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
            'cover',
            'group',
            'image',
            'list',
            'post-date',
            'pullquote',
            'quote',
            'search',
            'table'
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
 * Enqueue the custom theme common.css file
 * ---
 * TODO: Break this down into block CSS and enqueue using Enqueue_Block_styles
 * wherever possible.
 *
 * @return null
 */
function Enqueue_Common_styles()
{
	wp_enqueue_style(
		wp_get_theme()->get('TextDomain') . '-theme-styles',
		get_stylesheet_directory_uri() . '/dist/css/common.css',
		[],
		wp_get_theme()->get('Version')
	);
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
    if (has_block('contact-form-7/contact-form-selector')) {
        /**
         * Enqueue custom styles for CF7 and require the following
         * dependencies: CF7 and WP core button block styles for the
         * submit button
         */
        wp_enqueue_style(
            wp_get_theme()->get('TextDomain') . '-form-cf7',
            get_stylesheet_directory_uri() . '/dist/css/form-cf7.css',
            [
                'contact-form-7',
                'wp-block-button',
                'wp-block-buttons'
            ],
            wp_get_theme()->get('Version')
        );
    }
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
        wp_dequeue_script('gtm4wp-contact-form-7-tracker');
    }
}
