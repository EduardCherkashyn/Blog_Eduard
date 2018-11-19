<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/10/18
 * Time: 3:07 PM
 */

namespace App\Controller;

use App\Form\RegistrationType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $password = new UserService($encoder);
            $password->encodePassword($user);
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('form/registration.html.twig', [
            'registration_form' => $form->createView()
        ]);
    }
}
