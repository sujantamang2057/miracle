<?php

/**
 * constants-roles.php
 */
define('ROLE_MASTER', 'master');
define('ROLE_ADMIN', 'administrator');
define('ROLE_USER', 'user');

define('ROLES_ORDER_LIST', [
    ROLE_MASTER,
    ROLE_ADMIN,
    ROLE_USER,
]);

define('HAS_ROLE_MASTER_PERMISSION', 'role:' . ROLE_MASTER);
define('HAS_ROLE_MASTER_OR_PERMISSION', 'role_or_permission:' . ROLE_MASTER . '|');
define('HAS_ROLE_ADMIN_OR_PERMISSION', 'role_or_permission:' . ROLE_ADMIN . '|');

define('HAS_ANY_ROLE_MASTER_OR_ADMIN', ROLE_MASTER . '|' . ROLE_ADMIN);
define('HAS_ANY_ROLE_ADMIN_OR_USER', ROLE_ADMIN . '|' . ROLE_USER);
