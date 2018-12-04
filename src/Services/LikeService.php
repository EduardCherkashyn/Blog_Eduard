<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 12/4/18
 * Time: 8:13 PM
 */

namespace App\Services;


use App\Entity\Article;
use App\Entity\User;
use App\Entity\UserLike;

class LikeService
{
    public static function countLikes(Article $article)
    {
        $allLikes = $article->getUserLikes();
        $amountOfLikes = count($allLikes);
        foreach($allLikes as $like){
            if($like->getLikeOn()==false){
                $amountOfLikes--;
            }
        }

        return $amountOfLikes;
    }

    public static function ajaxRequest(User $user,Article $article)
    {
        $allUserLikes = $user->getUserlike();
        foreach($allUserLikes as $item){
            if($item->getArticle() === $article){
                $data = $item;
                break;
            }
            $data = null;
        }
        $like = $data;
        if($like == null) {
            $userLike = new UserLike();
            $userLike->setArticle($article);
            $userLike->setUser($user);
            $userLike->setLikeOn(true);

            return $userLike;
        }
        if($like->getLikeOn() == null){
            $like->setLikeOn(true);
        }
        else{
            $like->setLikeOn(false);
        }

        return $like;
    }
}