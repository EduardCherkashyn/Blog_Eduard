<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 12/5/18
 * Time: 9:56 AM
 */

namespace App\Controller;

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
    public function userInfo(User $user)
    {
        return $this->render('userInfo.html.twig',[
            'user' => $user
        ]);
    }

}