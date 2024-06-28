<?php

/**
 * Helper Functions
 * ---
 * Portable helper functions that help to abstract away details
 * from main logic.
 * php version 8.2.14
 *
 * @category   Includes
 * @package    b3
 * @author     Ian Pegg <hello@eggcupweb.com>
 * @license    GNU/GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt
 * @link       https://eggcupwebdesign.com
 */

namespace b3\WPTheme\Helpers;


/**
 * Establishes whether the current object has a post_name (slug) and
 * then compares it to the string provided.
 * 
 * @param String $Str_check_slug The string to check.
 * 
 * @return Bool True if the provided string matches the current object post_name.
 */
function Check_Current_Object_slug($Str_check_slug) {
    $Str_post_slug = isset(get_queried_object()->post_name) ? get_queried_object()->post_name : null;
    if ((!isset($Str_post_slug)) OR $Str_check_slug !== $Str_post_slug) {
        return false;
    }
    
    return true;
}