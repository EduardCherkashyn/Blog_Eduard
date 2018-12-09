<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 12/9/18
 * Time: 2:20 PM
 */

namespace App\Services;


use App\Entity\Article;

class ArticlesSorterService
{
    public function index(array $queryNotSortedArray) :array
    {
        $queryNotSorted=[];
        /**
         * @var Article $article
         */
        foreach ($queryNotSortedArray as $article  ){
            if($article->getText() !==null){
                $queryNotSorted[] = $article;
            }
        }
        $query = array_reverse($queryNotSorted);

        return $query;
    }
}