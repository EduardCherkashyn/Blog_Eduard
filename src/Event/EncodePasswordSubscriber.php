<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/19/18
 * Time: 3:01 PM
 */

namespace App\Event;


use App\Services\UserService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EncodePasswordSubscriber implements EventSubscriberInterface
{
    /**
     * Password encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * HashPasswordListener constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getSubscribedEvents()
    {
      return [
          EncodePasswordEvent::NAME => 'onEncodePassword'
      ];
    }

    public function onEncodePassword(EncodePasswordEvent $event)
    {
        $password = new UserService($this->passwordEncoder);
        $user = $event->getUser();
        $password->encodePassword($user);
    }
}