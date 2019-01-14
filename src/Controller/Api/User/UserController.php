<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 1/11/19
 * Time: 17:20
 */

namespace App\Controller\Api\User;


use App\Entity\User;
use App\Event\EncodePasswordEvent;
use App\Exception\JsonHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;



class UserController extends AbstractController
{

    /**
     * @Route("/registrations", name="api_registration", methods={"POST"})
     * @throws \Exception
     *  @OA\Post(
     *     path="/registrations",
     *     summary="User registration",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                   @OA\Property(
     *                     property="plainpassword",
     *                     type="string"
     *                 ),
     *                 example={"name": "Name", "email": "email@gmail.com","plainpassword": "123456"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registration is successful"
     *     )
     * )
     */
    public function registrationAction(Request $request,
                                       SerializerInterface $serializer,
                                       ValidatorInterface $validator,
                                       EventDispatcherInterface $dispatcher)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }

        /** @var User $user */
        $user = $serializer->deserialize($request->getContent(),User::class,'json');
        $event = new EncodePasswordEvent($user);
        $dispatcher->dispatch(EncodePasswordEvent::NAME, $event);
        $errors = $validator->validate($user);
        if (count($errors)) {
            throw new JsonHttpException(400, 'Bad Request');
        }

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return ($this->json($user));
    }

    /**
     * @Route("/login-api", name="api_login", methods={"POST"})
     * @throws \Exception
     *  @OA\Post(
     *     path="/login-api",
     *     summary="User login",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                   @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "email@gmail.com","password": "123456"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login is successful"
     *     )
     * )
     */
    public function loginAction(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $data = json_decode($content, true);
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$data['email']]);
        if($user instanceof User) {
            if($passwordEncoder->isPasswordValid($user,$data['password'])){
            return ($this->json($user));
        }}
        throw new JsonHttpException(400, 'Bad Request');
    }

}