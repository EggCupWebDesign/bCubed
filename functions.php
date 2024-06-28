<?php

/**
 * Theme functions
 * ---
 * This file is to be used solely to include functions that relate
 * directly to the theme presentation. Model and controller functions
 * belong in a plugin or must-use plugin.
 * php version 8.2.14
 *
 * @category Functions
 * @package  b3
 * @author   Ian Pegg <hello@eggcupweb.com>
 * @license  GNU/GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt
 * @link     https://eggcupwebdesign.com
 */

if (!defined('ABSPATH')) {
    exit;
}

$Str_includes_path   = get_template_directory() . '/inc/';

require_once $Str_includes_path . 'theme-bootstrap.inc.php';
require_once $Str_includes_path . 'login-page-config.inc.php';