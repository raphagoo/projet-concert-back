<?php


namespace App\Controller;


use App\BL\CategoryManager;
use App\BL\ConcertManager;
use App\BL\EventManager;
use App\BL\SalleManager;
use App\BL\SeatManager;
use App\Entity\Concert;
use App\Entity\Salle;
use App\Entity\Seat;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConcertController extends AbstractController
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
     * @Route ("/concert", name="createConcert", methods={"POST"})
     * @param Request $request
     * @param EventManager $eventManager
     * @param ConcertManager $concertManager
     * @param SeatManager $seatManager
     * @return Response
     */
    public function createConcert(Request $request, EventManager $eventManager, ConcertManager $concertManager, SeatManager $seatManager){
        $json = $request->getContent();

        $data = json_decode($request->getContent(), true);

        $idEvent = $data['eventId'];

        $concert = new Concert();
        $concert = $this->serializer->deserializeRequest($json,Concert::class, $concert);
        $concert->setEvent($eventManager->findEventById($idEvent));

        $concert = $concertManager->save($concert);

        $this->em->getRepository(Concert::class)->createSeats($concert);

        return $this->serializer->prepareResponse($concert, "concert_details");
        //return $this->json($json, $status = 200, $headers = [], $context = []);
    }

    /**
     * @Route("/concert/{idConcert}", name="getConcert", methods={"GET"})
     * @param $idConcert
     * @param ConcertManager $concertManager
     * @return Response
     */
    public function getConcert($idConcert, ConcertManager $concertManager){
        $concert = $concertManager->findConcertById($idConcert);

        if(!$concert) {
            return (new JsonResponse('concert not found'))->setStatusCode(404);
        }

        return $this->serializer->prepareResponse($concert, "concert_details");
    }

    /**
     * @Route ("/concert", name="listConcert", methods={"GET"})
     * @param ConcertManager $concertManager
     * @return Response
     */
    public function listConcert(ConcertManager $concertManager){
        $concerts = $concertManager->getConcerts();

        return $this->serializer->prepareResponse($concerts, 'concert_list');
    }

}
