<?php


namespace App\Controller;


use App\BL\SalleManager;
use App\BL\TicketObtainingManager;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketObtainingController extends AbstractController
{
    private LoggerInterface $logger;
    private SerializerHelper $serializer;
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, SerializerHelper $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;

    }

    /**
     * @Route ("/ticketObtainingMethod", name="listTicketObtainingMethod", methods={"GET"})
     * @param TicketObtainingManager $ticketObtainingManager
     * @return Response
     */
    public function listTicketObtainingMethod(TicketObtainingManager $ticketObtainingManager){
        $ticketObtainingMethods = $ticketObtainingManager->getTicketObtainings();

        return $this->serializer->prepareResponse($ticketObtainingMethods, 'ticketObtainingMethod_list');
    }
}
