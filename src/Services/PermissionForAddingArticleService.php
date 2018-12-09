<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 12/9/18
 * Time: 2:22 PM
 */

namespace App\Services;

use App\Entity\User;

class PermissionForAddingArticleService
{
    public function ifAdminRole(User $user)
    {
        $roles = $user->getRoles();
        $role = in_array('ROLE_ADMIN',$roles);
        if($role){
            $permmison = $user->setPermissionRequest(true);
        }
        else {
            $permmison = $user->getPermissionRequest();
        }

        return $permmison;
    }

}