<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 1/8/19
 * Time: 22:19
 */

namespace App\Controller\Api\Article;

use App\Entity\Article;
use App\Entity\Comment;
use App\Exception\JsonHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ArticleController extends AbstractController
{
    /**
     * @Route("/api/articles/{id}", name="api_articles", methods={"GET"})
     */
    public function showOneArticleAction(Article $article)
    {
        return $this->json($article);
    }

    /**
     * @Route("/api/articles/{id}", name="api_articles_add", methods={"POST"})
     */
    public function addArticleAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }

        /** @var Article $article */
        $article = $serializer->deserialize($request->getContent(),Article::class,'json');

        $errors = $validator->validate($article);

        if (count($errors)) {
            throw new JsonHttpException(400, 'Bad Request');
        }

        $this->getDoctrine()->getManager()->persist($article);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($article);
    }

    /**
     * @Route("/api/articles", name="api_articles_all", methods={"GET"})
     */
    public function showAllArticleAction()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findByApproved()->getResult();
        return $this->json($articles);
    }

    /**
     * @Route("/api/comments", name="api_comments_all", methods={"GET"})
     */
    public function showAllCommentsAction()
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        return $this->json($comments);
    }
}