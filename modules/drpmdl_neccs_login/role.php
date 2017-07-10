<?php

/**
 * Add a Role.
 * if project id does not exist in role then add role and grant permission to role.
 */
function add_role_permission($project_id) {
    $role = user_role_load_by_name($project_id);
    if (!$role) {
        $role = add_role($project_id);
        grant_role_permissions($role->rid);
    }
    return $role;
}

/**
 * Grant Permissions to Role.
 * if role id does not exist in user'srole then edit role to login user.
 */
function grant_role($user, $role) {
    if (!array_key_exists($role->rid, $user->roles)) {
        user_multiple_role_edit(array($user->uid), 'add_role', $role->rid);
    }
}

/**
 * Add a Role.
 */
function add_role($name) {
    $role = new stdClass();
    $role->name = $name;
    user_role_save($role);
    return $role;
}

/**
 * Grant Permissions to Role.
 */
function grant_role_permissions($rid) {
    $permissions = array(
        'access content',
        'view own unpublished content',
        'access all views',
    );
    user_role_grant_permissions($rid, $permissions);
}