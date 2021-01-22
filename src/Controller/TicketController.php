<?php


namespace App\Controller;


use App\BL\ReservationManager;
use App\BL\SeatManager;
use App\BL\TicketManager;
use App\BL\TicketObtainingManager;
use App\BL\UserManager;
use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
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
     * @Route ("/ticket", name="createTicket")
     * @param Request $request
     * @param TicketManager $ticketManager
     * @param UserManager $userManager
     * @param SeatManager $seatManager
     * @param ReservationManager $reservationManager
     * @param TicketObtainingManager $ticketObtainingManager
     * @return Response
     */
    public function createTicket(Request $request, TicketManager $ticketManager, UserManager $userManager, SeatManager $seatManager, ReservationManager $reservationManager, TicketObtainingManager $ticketObtainingManager){
        $json = $request->getContent();

        $data = json_decode($request->getContent(), true);

        $user = $userManager->findUserById($data['userId']);

        $reservation = new Reservation();
        $reservation->setClient($user);
        $reservation->setDateCommand(new \DateTime());
        $reservation = $reservationManager->save($reservation);

        $ticketArray = [];
        for($i = 0; $i < count($data['seatId']); $i++) {
            $ticket = new Ticket();
            $ticket = $this->serializer->deserializeRequest($json, Ticket::class, $ticket);
            $ticket->setPurchaseDate(new \DateTime());
            $ticket->setReservation($reservation);
            $ticketObtaining = $ticketObtainingManager->findTicketObtainingById($data['obtainingMethodId']);
            $ticket->setObtainingMethod($ticketObtaining);
            $seat = $seatManager->findSeatById($data['seatId'][$i]);
            $ticket->setSeat($seat);
            array_push($ticketArray, $ticketManager->save($ticket));
        }

        $result = $this->serializer->normalizeObject($ticketArray, Ticket::class);

        return $this->json($result, $status = 201, $headers = [], $context = []);
    }
}
