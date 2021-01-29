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

    /**
     * @Route("/register", name="createUser")
     * @param Request $request
     * @param UserManager $manager
     */
    public function register(Request $request, UserManager $manager)
    {
        try{
            $data = json_decode($request->getContent(), true);
            $email = $data['email'];
            $password = $data['password'];
            $firstName = $data['firstName'];
            $lastName = $data['lastName'];
            $gender = $data['gender'];
            $address = $data['address'];
            $residence = (isset($data['residence'])) ? $data['residence'] : null;
            $locality = (isset($data['locality'])) ? $data['locality'] : null;
            $zipCode = $data['zipCode'];
            $city = $data['city'];
            $country = $data['country'];
            $phoneNumber = $data['phoneNumber'];
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

        if(($birthDate = DateTime::createFromFormat('d/m/Y', $birthDateStr)) === false){
            $message = "The birthDate format is incorrect (format: dd/mm/YYYY)";
            throw new BadRequestHttpException($message);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($firstName);
        $user->setLastName($lastName);
        $user->setGender($gender);
        $user->setAddress($address);
        if($residence){$user->setResidence($residence);}
        if($locality){$user->setLocality($locality);}
        $user->setZipCode($zipCode);
        $user->setCity($city);
        $user->setCountry($country);
        $user->setPhoneNumber($phoneNumber);
        $user->setBirthDate($birthDate);

        $user->setPassword($password);
        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        return $this->serializer->prepareResponse($user, 'user_detail');
    }

    /**
     * @Route("/test", name="test")
     * @param Request $request
     */
    public function test(Request $request)
    {
        $user = $this->security->getUser();
        $data = array("username" => $user->getUsername(), "email" => $user->getEmail());

        return new JsonResponse($data, 200);

    }
}