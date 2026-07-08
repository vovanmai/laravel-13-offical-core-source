<?php

namespace App\Enums;

enum UserStatus: int
{
    case ACTIVE = 1;      // Được phép đăng nhập
    case SUSPENDED = 2;   // Bị khóa, không được đăng nhập
}
