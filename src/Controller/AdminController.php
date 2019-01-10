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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function indexAction(PaginatorInterface $paginator,Request $request)
    {
        $query = $this->getDoctrine()->getRepository(User::class)->findAllQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('AdminController/adminHomePage.html.twig',[
            'users' => $pagination,
            'admin' => true
        ]);
    }

    /**
     * @Route("/admin/info{id}", name="user_info")
     */
    public function userInfoAction(User $user)
    {
        return $this->render('AdminController/userInfo.html.twig',[
            'user' => $user,
            'admin' => true
        ]);
    }

    /**
     * @Route("/admin/textCheck", name="text_check")
     */
    public function checkTextForPublishingAction()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['approved'=> null]);

        return $this->render('AdminController/checkArticleBeforePublishing.html.twig',[
            'articles' => $articles,
            'admin' => true
        ]);
    }

    /**
     * @Route("/admin/publishArticle/{id}", name="article_publish")
     */
    public function publishArticleAction(Article $article)
    {
        $article->setApproved(true);
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

    /**
     * @Route("/admin/addUserRole/{id}", name="add_user_role")
     */
    public function addRoleAction(User $user){
        $user->setRoles(['ROLE_USER']);
        $user->setPermissionRequest(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_info',['id' =>$user->getId()]);
    }

    /**
     * @Route("/admin/removeUserRole/{id}", name="remove_user_role")
     */
    public function removeRoleAction(User $user){
        $user->setRoles(['ROLE_READER']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('user_info',['id' =>$user->getId()]);
    }

}