<?php


namespace App\Controller;


use App\BL\UserManager;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->encoder = $encoder;
        $this->em = $em;
    }

    /**
     * @Route("/register", name="createUser")
     * @param Request $request
     * @param UserManager $manager
     */
    public function register(Request $request, UserManager $manager)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->em->persist($user);
        $this->em->flush();

        $data = array("username" => $user->getUsername(), "email" => $user->getEmail());

        return new JsonResponse($data, 201);
    }
}