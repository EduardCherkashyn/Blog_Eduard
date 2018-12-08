<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 12/5/18
 * Time: 9:56 AM
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('adminHomePage.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/info{id}", name="user_info")
     */
    public function userInfoAction(User $user)
    {
        return $this->render('userInfo.html.twig',[
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/textCheck", name="text_check")
     */
    public function checkTextForPublishingAction()
    {
        $allArticles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $articles = [];
        /**
         * @var Article $article
         */
        foreach($allArticles as $article){
            if($article->getTextToPublish()!==null){
                $articles[] = $article;
            }
        }
        return $this->render('checkArticleBeforePublishing.html.twig',[
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/publishArticle/{id}", name="article_publish")
     */
    public function publishArticleAction(Article $article)
    {
        $article->setText($article->getTextToPublish());
        $article->setTextToPublish(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return $this->redirectToRoute('text_check');

    }

    /**
     * @Route("/admin/deleteArticle/{id}", name="article_delete")
     */
    public function deleteArticleAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('text_check');
    }



}