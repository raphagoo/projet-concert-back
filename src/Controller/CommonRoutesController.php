<?php


namespace App\Controller;


use App\BL\CategoryManager;
use App\BL\ConcertManager;
use App\BL\TicketObtainingManager;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommonRoutesController
{
    /**
     * ArticleController constructor.
     * @param EntityManagerInterface $em
     */
    private LoggerInterface $logger;
    private SerializerHelper $serializer;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, SerializerHelper $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->em = $em;
    }

    /**
     * @Route("/musicCategories", name="getMusicCategories", methods={"GET"})
     * @param CategoryManager $categoryManager
     * @return Response
     */
    public function getMusicCategories(CategoryManager $categoryManager){
        $musicCategories = $categoryManager->getCategories();
        return $this->serializer->prepareResponse($musicCategories, "music_category");
    }

    /**
     * @Route("/ticketTypes", name="getTicketTypes", methods={"GET"})
     * @param TicketObtainingManager $ticketObtainingManager
     * @return Response
     */
    public function getTicketTypes(TicketObtainingManager $ticketObtainingManager){
        $ticketTypes = $ticketObtainingManager->getTicketObtainings();
        return $this->serializer->prepareResponse($ticketTypes, "ticket_type");
    }
}