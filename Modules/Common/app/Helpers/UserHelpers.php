<?php

function getLoggedInUserRole()
{
    return auth()->user()->role_name;
}

function isUserMasterRole($role = '')
{
    return $role == ROLE_MASTER;
}

function isLoggedInUserMasterRole()
{
    return isUserMasterRole(getLoggedInUserRole());
}
