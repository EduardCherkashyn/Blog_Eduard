<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/11/18
 * Time: 6:17 PM.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\UserLike;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Services\LikeService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends Controller
{
    /**
     * @Route("/user/articles/add", name="add_article")
     */
    public function addAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $article->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('show_articles');
        }

        return $this->render('form/addArticle.html.twig', [
            'registration_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/articles/show", name="show_articles")
     */
    public function showAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryNotSorted = $em->getRepository(Article::class)->findAll();
        $query = array_reverse($queryNotSorted);
        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('articles.html.twig', [
            'articles' => $pagination]);

    }

    /**
     * @Route("/user/articles/comment/{id}", name="article_comment")
     */
    public function commentAction(Request $request,Article $article)
    {
        $comment = new Comment();
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $amountOfLikes = LikeService::countLikes($article);
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $user->addComment($comment);
            $article->addComment($comment);
            $comment->setDate(new\DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->persist($user);
            $em->persist($article);
            $em->flush();
        }

        return $this->render('articleComment.html.twig',[
            'article' => $article,
            'comment_form' => $form->createView(),
            'likes' => $amountOfLikes

        ]);
    }

    /**
     * @Route("/user/article/{id}", name="article_likes", methods={"POST"})
     */
    public function likesAction(Article $article)
    {
        $user = $this->getUser();
        $userLike = LikeService::ajaxRequest($user,$article);
        $em = $this->getDoctrine()->getManager();
        $em->persist($userLike);
        $em->flush();
        $em->refresh($article);
        $amountOfLikes = LikeService::countLikes($article);

        return new JsonResponse(['likes' => $amountOfLikes ]);
    }
}
