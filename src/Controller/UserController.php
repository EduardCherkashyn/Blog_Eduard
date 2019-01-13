<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/10/18
 * Time: 3:07 PM.
 */

namespace App\Controller;

use App\Event\EncodePasswordEvent;
use App\Form\RegistrationType;
use App\Services\CheckIfAdmin;
use App\Services\PermissionForAddingArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request, EventDispatcherInterface $dispatcher, TokenGeneratorInterface $tokenGenerator)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $event = new EncodePasswordEvent($user);
            $dispatcher->dispatch(EncodePasswordEvent::NAME, $event);
            $user->setApiToken( $tokenGenerator->generateToken());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('form/registration.html.twig', [
            'registration_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reader/profile", name="user_profile")
     */
    public function profileAction(PermissionForAddingArticleService $permissionForAddingArticleService, CheckIfAdmin $checkIfAdmin)
    {
        $user = $this->getUser();
        $admin = $checkIfAdmin->index($user);
        $permmison = $permissionForAddingArticleService->ifAdminRole($user);

        return $this->render('UserController/profile.html.twig',[
            'user' => $user,
            'admin' => $admin,
            'link' => $permmison,
        ]);
    }
}
