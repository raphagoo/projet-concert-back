<?php


namespace App\Controller;


use App\BL\SalleManager;
use App\Entity\Salle;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Psr\Log\LoggerInterface;

class SalleController extends AbstractController
{
    /**
     * ArticleController constructor.
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
     * @Route ("/salle/create", name="createSalle")
     * @param Request $request
     * @param SalleManager $salleManager
     * @return Response
     */
    public function createSalle(Request $request, SalleManager $salleManager){
        $json = $request->getContent();


        $salle = $this->serializer->deserializeRequest($json, Salle::class);

        $salleManager->save($salle);

        return $this->json($json, $status = 200, $headers = [], $context = []);
    }

    /**
     * @Route ("/salle/list", name="listSalle")
     * @param SalleManager $salleManager
     * @return Response
     */
    public function listSalle(SalleManager $salleManager){
        $salles = $salleManager->getSalles();

        return $this->serializer->prepareResponse($salles);
    }

}
