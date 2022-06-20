<?php


class RolesUser
{
    private const ROLES_ADMIN = "ROLE_ADMIN";


    public static function rolesAdmin():string
    {
        return self::ROLES_ADMIN;
    }
}