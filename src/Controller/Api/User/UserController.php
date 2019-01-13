<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 1/11/19
 * Time: 17:20
 */

namespace App\Controller\Api\User;


use App\Entity\User;
use App\Exception\JsonHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/registrations", name="api_registration", methods={"POST"})
     */
    public function registrationAction(Request $request, SerializerInterface $serializer)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }

        /** @var User $user */
        $user = $serializer->deserialize($request->getContent(),User::class,'json');

//        $errors = $validator->validate($article);
//
//        if (count($errors)) {
//            throw new JsonHttpException(400, 'Bad Request');
//        }

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($user);

    }
}