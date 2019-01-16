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
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\UserLike;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Services\GetAllArtFilterTags;
use App\Services\LikeService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
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
            $tags = $form->get('tags')->getData();
            $user = $this->getUser();
            $article->setUser($user);
            foreach ($tags as $tag) {
                $article->addTag($tag);
            }
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
     * @Route("/reader/articles/show", name="show_articles")
     */
    public function showAllAction(Request $request, PaginatorInterface $paginator)
    {
        /** @var User $user */
        $user = $this->getUser();
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();
        $query = $this->getDoctrine()->getRepository(Article::class)->findByApproved();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('ArticleController/articles.html.twig', [
            'articles' => $pagination,
            'user' => $user,
            'tags' => $tags
        ]);

    }

    /**
     * @Route("/reader", name="home_page")
     */
    public function homePageAction()
    {
        return $this->render('ArticleController/homepage.html.twig');
    }


    /**
     * @Route("/reader/articles/comment/{id}", name="article_comment")
     */
    public function showOneAction(Request $request, Article $article)
    {
        $comment = new Comment();
        /**@var User $user */
        $user = $this->getUser();
        $amountOfLikes = $this->getDoctrine()->getRepository(UserLike::class)->countLikes($article);
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

        return $this->render('ArticleController/articleComment.html.twig',[
            'article' => $article,
            'comment_form' => $form->createView(),
            'likes' => $amountOfLikes,

        ]);
    }

    /**
     * @Route("/reader/article/{id}", name="article_likes", methods={"POST"})
     */
    public function likesAction(Article $article, LikeService $like)
    {
        /** @var User $user */
        $user = $this->getUser();
        $userLike = $like->ajaxRequest($user, $article);
        $em = $this->getDoctrine()->getManager();
        $em->persist($userLike);
        $em->flush();
        $em->refresh($article);
        $amountOfLikes = $this->getDoctrine()->getRepository(UserLike::class)->countLikes($article);

        return new JsonResponse(['likes' => $amountOfLikes ]);
    }

    /**
     * @Route("/reader/askForUserRole/{id}", name="role_permissions")
     */
    public function askForRoleUser(User $user)
    {
        $user->setPermissionRequest(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('show_articles');
    }

    /**
     * @Route("/reader/category/{category}", name="show_category")
     */
    public function categoryShowAction($category, Request $request, PaginatorInterface $paginator, GetAllArtFilterTags $getAllArtFilterTags)
    {

        $tags2 = $this->getDoctrine()->getRepository(Tag::class)->findAll();
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findBy([
            'tag' => $category
            ]);
        $query = $getAllArtFilterTags->index($tags);
        $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        3
    );

        return $this->render('ArticleController/articles.html.twig', [
            'articles' => $pagination,
            'tags' => $tags2
        ]);

    }
}
