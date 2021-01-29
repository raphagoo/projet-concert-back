<?php


namespace App\Controller;


use App\BL\RestaurantTicketManager;
use App\BL\TicketManager;
use App\Entity\RestaurantTicket;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
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
     * @Route("/restaurant/{idTicket}", name="getRestaurantReservation", methods={"GET"})
     * @param $idTicket
     * @param TicketManager $ticketManager
     * @return Response
     */
    public function getRestaurantReservation($idTicket, TicketManager $ticketManager)
    {
       $ticket = $ticketManager->findTicketById($idTicket);
       $restaurantTicket = $ticket->getRestaurantTicket();
       if(!$restaurantTicket) {
           return (new Response('No restaurant ticket on this ticket'))->setStatusCode(404);
       }
       return $this->serializer->prepareResponse($restaurantTicket, 'restaurantTicket_details');
    }

    /**
     * @Route("/restaurant/{idTicket}", name="getRestaurantReservation", methods={"POST"})
     * @param Request $request
     * @param TicketManager $ticketManager
     * @param RestaurantTicketManager $restaurantTicketManager
     * @return Response
     */
    public function createRestaurantReservation(Request $request, TicketManager $ticketManager, RestaurantTicketManager $restaurantTicketManager)
    {
        $json = $request->getContent();

        $data = json_decode($request->getContent(), true);
        $idTicket = $data['idTicket'];
        $ticket = $ticketManager->findTicketById($idTicket);
        $restaurant = $ticket->getSeat()->getConcert()->getEvent()->getSalle()->getRestaurant();

        $restaurantTicket = new RestaurantTicket();

        $restaurantTicket = $this->serializer->deserializeRequest($json, RestaurantTicket::class, $restaurantTicket);
        $restaurantTicket->setTicket($ticket);
        $restaurantTicket->setRestaurant($restaurant);

        $restaurantTicket = $restaurantTicketManager->save($restaurantTicket);
        $ticket->setRestaurantTicket($restaurantTicket);
        $ticket->setModificationDate(new \DateTime());

        return $this->json("ticket created", $status = 201, $headers = [], $context = []);
    }
}
