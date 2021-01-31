<?php


namespace App\Controller;


use App\BL\PrivatizationManager;
use App\Entity\Privatization;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PrivatizationController extends AbstractController
{
    /**
     * ArticleController constructor.
     * @param EntityManagerInterface $em
     */
    private LoggerInterface $logger;
    private SerializerHelper $serializer;
    private Security $security;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, SerializerHelper $serializer, Security $security)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->security = $security;
    }

    /**
     * @Route("/privatization", name="createPrivatization", methods={"POST"})
     * @param Request $request
     * @param PrivatizationManager $privatizationManager
     * @return Response
     */
    public function createPrivatization(Request $request, PrivatizationManager $privatizationManager){
        $json = $request->getContent();
        $privatization = new Privatization();
        $privatization = $this->serializer->deserializeRequest($json, Privatization::class, $privatization);

        $privatizationManager->save($privatization);

        return $this->serializer->prepareResponse($privatization, 'privatization_details');
    }

    /**
     * @Route("/privatization/{idPrivatization}", name="getPrivatization", methods={"GET"})
     * @param $idPrivatization
     * @param PrivatizationManager $privatizationManager
     * @return Response
     */
    public function getPrivatization($idPrivatization, PrivatizationManager $privatizationManager){
        $privatization = $privatizationManager->findPrivatizationById($idPrivatization);

        return $this->serializer->prepareResponse($privatization, 'privatization_details');
    }

    /**
     * @Route("/privatization", name="listPrivatization", methods={"GET"})
     * @param PrivatizationManager $privatizationManager
     * @return Response
     */
    public function listPrivatization(PrivatizationManager $privatizationManager){
        $privatizations =  $privatizationManager->getPrivatizations();

        return $this->serializer->prepareResponse($privatizations, 'privatization_details');
    }
}
