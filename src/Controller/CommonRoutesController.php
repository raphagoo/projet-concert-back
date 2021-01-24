<?php


namespace App\Controller;


use App\BL\CategoryManager;
use App\BL\SalleManager;
use App\Entity\Salle;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Psr\Log\LoggerInterface;

class CommonRoutesController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     */
    private LoggerInterface $logger;
    private SerializerHelper $serializer;
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, SerializerHelper $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;

    }

    /**
     * @Route ("/music-categories", name="listMusicCategories", methods={"GET"})
     * @param CategoryManager $categoryManager
     * @return Response
     */
    public function listCategories(CategoryManager $categoryManager){
        $categories = $categoryManager->getCategorys();

        return $this->serializer->prepareResponse($categories);
    }

}
