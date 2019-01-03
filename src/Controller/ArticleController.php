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
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Services\CheckIfAdmin;
use App\Services\LikeService;
use App\Services\PermissionForAddingArticleService;
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
    public function addAction(Request $request, CheckIfAdmin $checkIfAdmin)
    {
        $admin = $checkIfAdmin->index($this->getUser());
        $article = new Article();
        $tag = new Tag();
        $article->addTag($tag);
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
            'admin' => $admin
        ]);
    }

    /**
     * @Route("/reader/articles/show", name="show_articles")
     */
    public function showAction(Request $request, PaginatorInterface $paginator, CheckIfAdmin $checkIfAdmin)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $admin = $checkIfAdmin->index($user);
        $query = $this->getDoctrine()->getRepository(Article::class)->findByApproved();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('ArticleController/articles.html.twig', [
            'articles' => $pagination,
            'user' => $user,
            'admin' => $admin
        ]);

    }

    /**
     * @Route("/reader", name="home_page")
     */
    public function homePageAction(CheckIfAdmin $checkIfAdmin)
    {
        $admin = $checkIfAdmin->index($this->getUser());
        return $this->render('ArticleController/homepage.html.twig',[
            'admin' => $admin
        ]);
    }


    /**
     * @Route("/reader/articles/comment/{id}", name="article_comment")
     */
    public function commentAction(Request $request, Article $article, LikeService $like, CheckIfAdmin $checkIfAdmin)
    {
        $comment = new Comment();
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $admin = $checkIfAdmin->index($user);
        $amountOfLikes = $like->countLikes($article);
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
            'admin' => $admin

        ]);
    }

    /**
     * @Route("/reader/article/{id}", name="article_likes", methods={"POST"})
     */
    public function likesAction(Article $article, LikeService $like)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $userLike = $like->ajaxRequest($user, $article);
        $em = $this->getDoctrine()->getManager();
        $em->persist($userLike);
        $em->flush();
        $em->refresh($article);
        $amountOfLikes = $like->countLikes($article);

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
     * @Route("/reader/{category}", name="show_category")
     */
    public function categoryShowAction($category, Request $request, PaginatorInterface $paginator, CheckIfAdmin $checkIfAdmin)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $admin = $checkIfAdmin->index($user);
        $query = $this->getDoctrine()->getRepository(Article::class)->findBy($category);

        $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        3
    );

        return $this->render('ArticleController/articles.html.twig', [
            'articles' => $pagination,
            'admin' => $admin
        ]);

    }
}
