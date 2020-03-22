<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 1/3/19
 * Time: 16:28.
 */

namespace App\Services;

use App\Entity\Article;
use App\Entity\Tag;

class GetAllArtFilterTags
{
    public function index(array $tags): array
    {
        $query = [];
        foreach ($tags as $tag) {
            /**
             * @var Tag
             */
            $articles = $tag->getArticle();
            foreach ($articles as $article) {
                /**
                 * @var Article
                 */
                if (true == $article->getApproved()) {
                    array_unshift($query, $article);
                }
            }
        }

        return $query;
    }
}
