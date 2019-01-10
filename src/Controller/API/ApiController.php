<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 1/8/19
 * Time: 22:19
 */

namespace App\Controller\API;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class ApiController extends AbstractController
{
    /**
     * @Route("/api/article/{id}", name="api_articles")
     */
    public function showOneArticleAction(Article $article,SerializerInterface $serializer)
    {

        $jsonContent = $serializer->serialize($article,'json', array('groups' => 'group1'));

        return new JsonResponse(json_decode($jsonContent));
    }

    /**
     * @Route("/api/articles/new", name="api_articles_add")
     */
    public function addArticleAction(Request $request,SerializerInterface $serializer)
    {
        $data = $request->getContent();
        $jsonContent = $serializer->deserialize($data,Article::class,'json');

        return new Response(var_dump($jsonContent));
    }
}