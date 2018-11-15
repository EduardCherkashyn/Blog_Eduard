<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/11/18
 * Time: 6:17 PM
 */

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        if ($form->isSubmitted()&& $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('show_articles');
        }
        return $this->render('form/addArticle.html.twig', [
            'registration_form' => $form->createView()
        ]);
    }
    /**
     * @Route("/user/articles/show", name="show_articles")
     */
    public function showAction()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
        return $this->render('articles.html.twig', [
            'articles' => $articles
        ]);
    }

}