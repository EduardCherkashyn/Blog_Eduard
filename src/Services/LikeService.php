<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 12/4/18
 * Time: 8:13 PM.
 */

namespace App\Services;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\UserLike;

class LikeService
{
    public function ajaxRequest(User $user, Article $article): UserLike
    {
        $data = null;
        $allUserLikes = $user->getUserlike();
        foreach ($allUserLikes as $item) {
            if ($item->getArticle() === $article) {
                $data = $item;
                break;
            }
        }
        $likes = $data;
        if (null == $likes) {
            $userLike = new UserLike();
            $userLike->setArticle($article);
            $userLike->setUser($user);
            $userLike->setLikeOn(true);

            return $userLike;
        }
        if (null == $likes->getLikeOn()) {
            $likes->setLikeOn(true);
        } else {
            $likes->setLikeOn(false);
        }

        return $likes;
    }
}
