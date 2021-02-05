<?php


namespace App\Controller;


use App\BL\UserManager;
use App\Entity\User;
use App\Helpers\SerializerHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

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

    private $security;
    private SerializerHelper $serializer;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     * @param Security $security
     * @param SerializerHelper $serializer
     */
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, Security $security,
                                SerializerHelper $serializer)
    {
        $this->encoder = $encoder;
        $this->em = $em;
        $this->security = $security;
        $this->serializer = $serializer;
    }

    public function getData($data){
        $result = [];

        try{
            $result['email'] = $data['email'];
            $result['password'] = $data['password'];
            $result['firstName'] = $data['firstName'];
            $result['lastName'] = $data['lastName'];
            $gender = $data['gender'];
            $result['address'] = $data['address'];
            $result['residence'] = (isset($data['residence'])) ? $data['residence'] : null;
            $result['locality'] = (isset($data['locality'])) ? $data['locality'] : null;
            $result['zipCode'] = $data['zipCode'];
            $result['city'] = $data['city'];
            $result['country'] = $data['country'];
            $result['phoneNumber'] = $data['phoneNumber'];
            $birthDateStr = $data['birthDate'];
        } catch (ErrorException $e){
            $message = "A required field is missing (required : email, password, firstName, lastName, gender, address, 
            zipCode, city, country, phoneNumber, birthDate";
            throw new BadRequestHttpException($message);
        }

        if($gender != "F" && $gender != "M"){
            $message = "The field gender is incorrect (should be either F or M)";
            throw new BadRequestHttpException($message);
        }
        $result['gender'] = $gender;

        if(($birthDate = DateTime::createFromFormat('Y-m-d', $birthDateStr)) === false){
            $message = "The birthDate format is incorrect (format: YYYY-mm-dd)";
            throw new BadRequestHttpException($message);
        }
        $result['birthDate'] = $birthDate;

        return $result;
    }

    public function setUserData($user, $data){
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setLastName($data['lastName']);
        $user->setGender($data['gender']);
        $user->setAddress($data['address']);
        if($data['residence']){$user->setResidence($data['residence']);}
        if($data['locality']){$user->setLocality($data['locality']);}
        $user->setZipCode($data['zipCode']);
        $user->setCity($data['city']);
        $user->setCountry($data['country']);
        $user->setPhoneNumber($data['phoneNumber']);
        $user->setBirthDate($data['birthDate']);

        $user->setPassword($data['password']);
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        return $user;
    }

    /**
     * @Route("/register", name="createUser")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $userData = $this->getData($data);

        $user = new User();
        $createdUser = $this->setUserData($user, $userData);

        $this->em->persist($createdUser);
        $this->em->flush();

        return $this->serializer->prepareResponse($createdUser, 'user_detail');
    }

    /**
     * @Route("/user", name="updateUser", methods={"PATCH"})
     * @param Request $request
     * @return Response
     */
    public function updateUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $userData = $this->getData($data);

        $user = $this->security->getUser();
        $updatedUser = $this->setUserData($user, $userData);

        $this->em->persist($updatedUser);
        $this->em->flush();

        return $this->serializer->prepareResponse($updatedUser, 'user_detail');
    }

    /**
     * @Route("/user", name="getUserData", methods={"GET"})
     * @return Response
     */
    public function getUser()
    {
        $user = $this->security->getUser();

        return $this->serializer->prepareResponse($user, 'user_detail');
    }


    /**
     * @Route("/test", name="test")
     * @param Request $request
     * @return JsonResponse
     */
    public function test(Request $request)
    {
        $user = $this->security->getUser();
        $data = array("username" => $user->getUsername(), "email" => $user->getEmail());

        return new JsonResponse($data, 200);

    }
}
