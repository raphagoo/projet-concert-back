<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
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
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/register", name="createUser")
     * @param Request $request
     * @param ObjectManager $manager
     */
    public function register(Request $request, ObjectManager $manager)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();
    }
}