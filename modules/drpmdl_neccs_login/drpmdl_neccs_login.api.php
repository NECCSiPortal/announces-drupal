<?php

/**
 * @file
 * Hooks provided by the drpmdl_neccs_login module.
 */

/**
 * Implements hook_drpmdl_neccs_login_user_alter().
 *
 * Provides a way to modify the user account information before it is saved to
 * the database.
 *
 */
function hook_drpmdl_neccs_login_user_alter(&$info, $data, $context) {
    // Set user's signature
    $info['signature'] = $data['user']['sig'];
    $info['signature_format'] = 'filtered_html';
}
