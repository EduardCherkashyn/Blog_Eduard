<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 11/10/18
 * Time: 3:07 PM
 */

namespace App\Controller;

use App\Event\EncodePasswordEvent;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request, EventDispatcherInterface $dispatcher)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $event = new EncodePasswordEvent($user);
            $dispatcher->dispatch(EncodePasswordEvent::NAME, $event);
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
