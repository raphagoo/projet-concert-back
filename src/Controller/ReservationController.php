<?php


namespace App\Controller;


use App\BL\ReservationManager;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController
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
     * @Route("/reservation/{idReservation}", name="getReservation")
     * @param $idReservation
     * @param ReservationManager $reservationManager
     * @return Response
     */
    public function getReservation($idReservation, ReservationManager $reservationManager){
        $reservation = $reservationManager->findReservationById($idReservation);

        if(!$reservation) {
            return (new JsonResponse('reservation not found'))->setStatusCode(404);
        }

        return $this->serializer->prepareResponse($reservation, 'reservation_details');
    }
}
