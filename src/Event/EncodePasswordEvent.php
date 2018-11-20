<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/19/18
 * Time: 2:46 PM
 */

namespace App\Event;


use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class EncodePasswordEvent extends Event
{
    const NAME = 'encode.password';

    /**
     * @var User $user
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

}