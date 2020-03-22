<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 12/28/18
 * Time: 22:20.
 */

namespace App\Services;

use App\Entity\User;

class CheckIfAdmin
{
    public function index(User $user): bool
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            return true;
        }
        return false;
    }
}
