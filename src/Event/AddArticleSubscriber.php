<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/19/18
 * Time: 3:01 PM
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddArticleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
      return [
          AddArticleEvent::NAME => 'onAddArticle'
      ];
    }
    public function onAddArticle(AddArticleEvent $event)
    {
        dump($event);
    }
}