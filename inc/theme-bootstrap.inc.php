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
add_action('after_setup_theme', __NAMESPACE__ . '\\Enqueue_Block_styles');
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\Enqueue_Common_styles');
add_action('after_setup_theme', __NAMESPACE__ . '\\Enqueue_Editor_styles');


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
    $Str_block_css_dir = 'dist/css/blocks/';
    $Arr_styled_blocks = [
        'cover',
        'image',
        'post-date',
        'pullquote',
        'quote'
    ];

    foreach ( $Arr_styled_blocks as $Str_block_name ) {
        $Arr_args = [
            'handle' => wp_get_theme()->get( 'TextDomain' ) . '-' . $Str_block_name,
            'src'    => get_theme_file_uri($Str_block_css_dir . $Str_block_name . '.css'),
            $Arr_args['path'] = get_theme_file_path($Str_block_css_dir . $Str_block_name . '.css'),
        ];
        wp_enqueue_block_style('core/' . $Str_block_name, $Arr_args);
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
 * Enqueue the editor-style.css file
 * ---
 *
 * @return null
 */
function Enqueue_Editor_styles()
{
    add_theme_support('editor-styles');
    add_editor_style('dist/css/admin/editor-style.css');
}
