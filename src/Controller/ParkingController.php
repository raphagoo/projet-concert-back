<?php


namespace App\Controller;


use App\BL\ParkingTicketManager;
use App\BL\TicketManager;
use App\Entity\ParkingTicket;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ParkingController extends AbstractController
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
     * @Route("/parking/{idTicket}", name="getParkingReservation", methods={"GET"})
     * @param $idTicket
     * @param TicketManager $ticketManager
     * @return Response
     */
    public function getParkingReservation($idTicket, TicketManager $ticketManager)
    {
        $ticket = $ticketManager->findTicketById($idTicket);
        $parkingTicket = $ticket->getParkingTicket();
        if (!$parkingTicket) {
            return (new Response('No parking ticket on this ticket'))->setStatusCode(404);
        }
        return $this->serializer->prepareResponse($parkingTicket, 'parkingTicket_details');
    }

    /**
     * @Route("/parking/{idTicket}", name="getParkingReservation", methods={"POST"})
     * @param Request $request
     * @param $idTicket
     * @param TicketManager $ticketManager
     * @param ParkingTicketManager $parkingTicketManager
     * @return Response
     */
    public function createParkingReservation(Request $request, $idTicket, TicketManager $ticketManager, ParkingTicketManager $parkingTicketManager)
    {
        $json = $request->getContent();

        $data = json_decode($request->getContent(), true);
        $ticket = $ticketManager->findTicketById($idTicket);

        if (!$this->security->getUser()) {
            if ($ticket->getReservation()->getClient()->getEmail() !== $data['email']) {
                return (new JsonResponse('This ticket isn\'t linked to email provided'))->setStatusCode(403);
            }
        }

        $parking = $ticket->getSeat()->getConcert()->getEvent()->getSalle()->getParking();

        $parkingTicket = new ParkingTicket();

        $parkingTicket = $this->serializer->deserializeRequest($json, ParkingTicket::class, $parkingTicket);
        $parkingTicket->setTicket($ticket);
        $parkingTicket->setParking($parking);

        $parkingTicket = $parkingTicketManager->save($parkingTicket);

        $ticket->setParkingTicket($parkingTicket);
        $ticket->setModificationDate(new \DateTime());
        $ticketManager->save($ticket);

        return $this->json("ticket created", $status = 201, $headers = [], $context = []);
    }

    /**
     * @Route("/parking/{idTicket}", name="getParkingReservation", methods={"PUT"})
     * @param Request $request
     * @param $idTicket
     * @param TicketManager $ticketManager
     * @param ParkingTicketManager $parkingTicketManager
     * @return Response
     */
    public function updateParkingReservation(Request $request, $idTicket, TicketManager $ticketManager, ParkingTicketManager $parkingTicketManager)
    {
        $data = json_decode($request->getContent(), true);

        $ticket = $ticketManager->findTicketById($idTicket);

        if (!$this->security->getUser()) {
            if ($ticket->getReservation()->getClient()->getEmail() !== $data['email']) {
                return (new JsonResponse('This ticket isn\'t linked to email provided'))->setStatusCode(403);
            }
        }

        $parkingTicket = $ticket->getParkingTicket();

        $parkingTicket->setNumberPlace($data['numberPlace']);

        $parkingTicketManager->save($parkingTicket);

        $ticket = $parkingTicket->getTicket();
        $ticket->setModificationDate(new \DateTime());
        $ticketManager->save($ticket);

        return $this->json("ticket updated", $status = 200, $headers = [], $context = []);
    }
}
