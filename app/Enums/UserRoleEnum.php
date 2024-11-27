<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Common = 'common';
}
