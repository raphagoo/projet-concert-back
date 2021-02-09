<?php


namespace App\Controller;


use App\BL\ParkingTicketManager;
use App\BL\ReservationManager;
use App\BL\RestaurantTicketManager;
use App\BL\SeatManager;
use App\BL\TicketManager;
use App\BL\TicketObtainingManager;
use App\BL\UserManager;
use App\Entity\ParkingTicket;
use App\Entity\Reservation;
use App\Entity\RestaurantTicket;
use App\Entity\Ticket;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @Route ("/ticket", name="createTicket", methods={"POST"})
     * @param Request $request
     * @param TicketManager $ticketManager
     * @param UserManager $userManager
     * @param SeatManager $seatManager
     * @param ReservationManager $reservationManager
     * @param TicketObtainingManager $ticketObtainingManager
     * @param RestaurantTicketManager $restaurantTicketManager
     * @param ParkingTicketManager $parkingTicketManager
     * @return Response
     * @throws Exception
     */
    public function createTicket(Request $request, TicketManager $ticketManager, UserManager $userManager, SeatManager $seatManager, ReservationManager $reservationManager, TicketObtainingManager $ticketObtainingManager, RestaurantTicketManager $restaurantTicketManager, ParkingTicketManager $parkingTicketManager){
        $json = $request->getContent();

        $data = json_decode($request->getContent(), true);


        $user = $this->getUser();

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
            if($data['restaurantPlaces'] > 0){
                $restaurantTicket = new RestaurantTicket();
                $restaurantTicket->setNumberPlace(1);
                $data['restaurantPlaces']--;
                $restaurantTicket->setReservationTime(new \DateTime($data['restaurantTime']));
                $restaurantTicket->setTicket($ticket);
                $restaurantTicket->setRestaurant($ticket->getSeat()->getConcert()->getEvent()->getSalle()->getRestaurant());
                $restaurantTicketManager->save($restaurantTicket);
            }
            if($data['parking']){
                $parkingTicket = new ParkingTicket();
                $parkingTicket->setTicket($ticket);
                $parkingTicket->setNumberPlace($data['parkingPlaces']);
                $parkingTicket->setParking($ticket->getSeat()->getConcert()->getEvent()->getSalle()->getParking());
                $parkingTicketManager->save($parkingTicket);
            }
        }

        //$result = $this->serializer->normalizeObject($ticketArray, Ticket::class);

        return $this->serializer->prepareResponse($ticketArray, 'ticket_creation');

        //return $this->json("", $status = 201, $headers = [], $context = []);
    }
}
