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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="My first API",
 *   version="1.0.0",
 * )
 */


class ArticleController extends AbstractController
{
    /**
     * @Route("/api/articles/{id}", name="api_articles", methods={"GET"})
     * @OA\Get(path="/api/articles/{id}",
     *     summary="Get one article by id",
     *   @OA\Parameter(name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response="200",
     *     description="The Article",
     *     @OA\JsonContent(ref="#/components/schemas/article"),
     *   )
     * )
     */
    public function showOneArticleAction(Article $article)
    {
        return $this->json($article);
    }

    /**
     * @Route("/api/articles", name="api_articles_add", methods={"POST"})
     * @OA\Post(
     *     path="/api/articles",
     *     summary="Add a new article",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="text",
     *                     type="string"
     *                 ),
     *                 example={"name": "Name", "text": "Some Text Here"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="New Article has been created"
     *     )
     * )
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
     * * @OA\Get(path="/api/articles",
     *     summary="Get all articles",
     *   @OA\Response(response="200",
     *     description="All Articles",
     *   )
     * )
     */
    public function showAllArticleAction(PaginatorInterface $paginator,Request $request)
    {
        $query = $this->getDoctrine()->getRepository(Article::class)->findByApproved();
        $articles = $paginator->paginate($query,$request->query->getInt('page', 1),3);
        return $this->json($articles);
    }

    /**
     * @Route("/api/comments", name="api_comments_all", methods={"GET"})
     * @OA\Get(path="/api/comments",
     *     summary="Get all comments",
     *   @OA\Response(response="200",
     *     description="All comments",
     *   ))
     */
    public function showAllCommentsAction()
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        return $this->json($comments);
    }
}