<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 1/8/19
 * Time: 22:19
 */

namespace App\Controller\API;

use App\Entity\Article;
use App\Exception\JsonHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ApiController extends AbstractController
{
    /**
     * @Route("/api/articles/{id}/show", name="api_articles")
     */
    public function showOneArticleAction(Article $article)
    {
        return $this->json($article);
    }

    /**
     * @Route("/api/articles/new", name="api_articles_add")
     */
    public function addArticleAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, RouterInterface $router)
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
}