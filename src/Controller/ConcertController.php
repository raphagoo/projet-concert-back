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
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, SerializerHelper $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;

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

        $priceMax = $concert->getPriceMax();
        $percentage = $concert->getPercentage();

        $capacity = $concert->getEvent()->getSalle()->getCapacity();

        $seatsByCategory = floor($capacity / $concert->getCategoryNumber());
        $seatsByLine = floor($seatsByCategory / 3);

        $category = 1;
        for($i = 0; $i < $concert->getCategoryNumber(); $i++) {
            $lineIndex = 0;
            for ($j = 0; $j + $seatsByLine < $seatsByCategory; $j = $j + $seatsByLine) {
                $line = ["A", "B", "C", "D", "E"];
                for ($k = 1; $k < $seatsByLine + 1; $k++) {
                    $seat = new Seat();
                    $seat->setConcert($concert);
                    $seat->setCategory($category);
                    $seat->setLetter($line[$lineIndex]);
                    $seat->setNumber($k);
                    $seat->setPrice($priceMax);
                    $seatManager->save($seat);
                }
                $priceMax = round($priceMax * (100 - $percentage) / 100);
                $lineIndex++;
            }
            $category++;
        }
        return $this->serializer->prepareResponse($concert, "concert_details");
        //return $this->json($json, $status = 200, $headers = [], $context = []);
    }

    /**
     * @Route("/concert/{idConcert}", name="getConcert")
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

}
