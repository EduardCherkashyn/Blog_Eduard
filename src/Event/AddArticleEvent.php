<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/19/18
 * Time: 2:46 PM
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;
use App\Entity\Article;

class AddArticleEvent extends Event
{
    const NAME = 'add.new.article';

    /**
     * @var Article $article
     */
    protected $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function getArticleName()
    {
        return $this->article;
    }

}