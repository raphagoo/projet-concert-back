<?php


namespace App\BL;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    /**
     * UserManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return User[]
     */
    public function getUsers(){
        return $this->em->getRepository(User::class)->findAll();
    }

    /**
     * @param $idUser
     * @return User|null
     */
    public function findUserById($idUser){
        return $this->em->getRepository(User::class)->find($idUser);
    }

    /**
     * @param User $user
     * @return int|null
     */
    public function save(User $user){

        $this->em->persist($user);
        $this->em->flush();
        return $user->getId();
    }

    /**
     * @param $user
     */
    public function deleteUser($user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
