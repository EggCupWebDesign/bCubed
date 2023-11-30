<?php

/**
 * Login Page Configuration
 * ---
 * Customises the login page to use a custom logo, title and URL
 * php version 7.4.15
 *
 * @category   Includes
 * @package    b3
 * @author     Ian Pegg <hello@eggcupweb.com>
 * @license    GNU/GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt
 * @link       https://eggcupwebdesign.com
 */

namespace EggCup\SSP\LoginConfig;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * This script registers following functions with WP action hooks/filters:
 */
add_filter('login_headerurl', __NAMESPACE__ . '\\Custom_Login_Logo_url');
add_filter('login_headertext', __NAMESPACE__ . '\\Custom_Login_Logo_title');
add_action('login_enqueue_scripts', __NAMESPACE__ . '\\Custom_logo');


/**
 * Returns a customised URL for the login page logo.
 *
 * @return String URL for the logo.
 */
function Custom_Login_Logo_url() {
    return home_url();
}

/**
 * Returns a string to be used as the anchor text on
 * the login logo.
 *
 * @return String Anchor text.
 */
function Custom_Login_Logo_title() {
    return get_bloginfo('name', 'display');
}

/**
 * Prints inline CSS to override the WP logo on the login page.
 *
 * @return void Seriously, this function just echoes out content when called!
 */
function Custom_logo()
{
    ?><style>
        #login h1 a,
        .login h1 a {
            background-image    : url('<?php echo esc_url(get_template_directory_uri() . '/dist/svg/login-page-logo.svg'); ?>');
		    height              : 65px;
		    width               : 100%;
		    background-size     : 80%;
		    background-repeat   : no-repeat;
        }
    </style><?php
}
