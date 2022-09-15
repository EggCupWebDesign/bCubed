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
add_action('after_setup_theme', __NAMESPACE__ . '\\Enqueue_Block_styles');
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\Enqueue_Common_styles');
add_action('after_setup_theme', __NAMESPACE__ . '\\Enqueue_Editor_styles');
add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\\Enqueue_Block_Editor_assets');
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\Dequeue_assets', 9999);


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
     * Add excerpt support to pages:
     */
    add_post_type_support('page', 'excerpt');
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
    /**
     * Define custom block styles using a multi-dimensional array, with the
     * outer array keyed on the block package/name slug and the inner array
     * keyed on the name of the custom block style:
     */
    $Arr_custom_block_styles = [
        'core/group' => [
            'cta' => [
                'name'  => 'call-to-action',
                'label' => __('CTA', wp_get_theme()->get('TextDomain')),
                'style' => wp_get_theme()->get('TextDomain') . '-cta-group'
            ]
        ],
        'core/list' => [
            'inline' => [
                'name'  => 'list-inline',
                'label' => __('Inline', wp_get_theme()->get('TextDomain')),
                'style' => wp_get_theme()->get('TextDomain') . '-list-inline'
            ],
            'unstyled' => [
                'name'  => 'list-unstyled',
                'label' => __('Unstyled', wp_get_theme()->get('TextDomain')),
                'style' => wp_get_theme()->get('TextDomain') . '-list-unstyled'
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
 * Dequeue assets either globally or conditionally
 * ---
 * This function is hooked in late so that it can
 * remove assets enqueued by plugins or core.
 *
 * @return void
 */
function Dequeue_assets()
{
    global $post;

    // Add in anything you'd like to dequeue
}
