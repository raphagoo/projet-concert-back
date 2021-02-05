<?php


namespace App\Controller;


use App\BL\RestaurantTicketManager;
use App\BL\TicketManager;
use App\Entity\RestaurantTicket;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Secu;

class RestaurantController extends AbstractController
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
     * @Route("/restaurant/{idTicket}", name="getRestaurantReservation", methods={"GET"})
     * @Secu("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")
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
     * @Route("/restaurant/{idTicket}", name="createRestaurantReservation", methods={"POST"})
     * @Secu("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")
     * @param Request $request
     * @param $idTicket
     * @param TicketManager $ticketManager
     * @param RestaurantTicketManager $restaurantTicketManager
     * @return Response
     */
    public function createRestaurantReservation(Request $request, $idTicket, TicketManager $ticketManager, RestaurantTicketManager $restaurantTicketManager)
    {
        $json = $request->getContent();

        $data = json_decode($request->getContent(), true);
        $ticket = $ticketManager->findTicketById($idTicket);

        if(!$this->security->getUser()){
            if($ticket->getReservation()->getClient()->getEmail() !== $data['email']){
                return (new JsonResponse('This ticket isn\'t linked to email provided'))->setStatusCode(403);
            }
        }

        $restaurant = $ticket->getSeat()->getConcert()->getEvent()->getSalle()->getRestaurant();

        $restaurantTicket = new RestaurantTicket();

        $restaurantTicket = $this->serializer->deserializeRequest($json, RestaurantTicket::class, $restaurantTicket);
        $restaurantTicket->setTicket($ticket);
        $restaurantTicket->setRestaurant($restaurant);

        $restaurantTicket = $restaurantTicketManager->save($restaurantTicket);

        $ticket->setRestaurantTicket($restaurantTicket);
        $ticket->setModificationDate(new \DateTime());
        $ticketManager->save($ticket);

        return $this->json("ticket created", $status = 201, $headers = [], $context = []);
    }

    /**
     * @Route("/restaurant/{idTicket}", name="updateRestaurantReservation", methods={"PUT"})
     * @Secu("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")
     * @param Request $request
     * @param $idTicket
     * @param TicketManager $ticketManager
     * @param RestaurantTicketManager $restaurantTicketManager
     * @return Response
     * @throws Exception
     */
    public function updateRestaurantReservation(Request $request, $idTicket, TicketManager $ticketManager, RestaurantTicketManager $restaurantTicketManager)
    {
        $data = json_decode($request->getContent(), true);

        $ticket = $ticketManager->findTicketById($idTicket);

        if(!$this->security->getUser()){
           if($ticket->getReservation()->getClient()->getEmail() !== $data['email']){
               return (new JsonResponse('This ticket isn\'t linked to email provided'))->setStatusCode(403);
           }
        }

        $restaurantTicket = $ticket->getRestaurantTicket();

        $restaurantTicket->setNumberPlace($data['numberPlace']);
        $restaurantTicket->setReservationTime(new \DateTime($data['reservationTime']));

        $restaurantTicketManager->save($restaurantTicket);

        $ticket = $restaurantTicket->getTicket();
        $ticket->setModificationDate(new \DateTime());
        $ticketManager->save($ticket);

        return $this->json("ticket updated", $status = 200, $headers = [], $context = []);
    }
}
