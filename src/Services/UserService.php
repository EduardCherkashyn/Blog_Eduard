<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/18/18
 * Time: 1:03 PM
 */

namespace App\Services;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    public $encoder;

    public function encodePassword(User $user)
    {
        $plainPassword = $user->getPlainpassword();

        $encoded = $this->encoder->encodePassword($user, $plainPassword);

        $user->setPassword($encoded);
    }

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
}
