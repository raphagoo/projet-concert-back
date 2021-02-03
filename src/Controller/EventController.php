<?php


namespace App\Controller;


use App\BL\CategoryManager;
use App\BL\ConcertManager;
use App\BL\EventManager;
use App\BL\SalleManager;
use App\Entity\Concert;
use App\Entity\Event;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
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
     * @Route ("/event", name="createEvent", methods={"POST"})
     * @param Request $request
     * @param EventManager $eventManager
     * @param SalleManager $salleManager
     * @return Response
     */
    public function createEvent(Request $request, EventManager $eventManager, SalleManager $salleManager){
        $json = $request->getContent();

        $data = json_decode($request->getContent(), true);

        $idSalle = $data['salleId'];

        $event = new Event();
        $event = $this->serializer->deserializeRequest($json,Event::class, $event);
        $event->setSalle($salleManager->findSalleById($idSalle));

        $eventManager->save($event);

        return $this->json($json, $status = 200, $headers = [], $context = []);
    }

    /**
     * @Route("/event/search", name="searchEvent", methods={"GET"})
     * @param SalleManager $salleManager
     * @param EventManager $eventManager
     * @param CategoryManager $categoryManager
     * @return Response
     */
    public function searchEvent(SalleManager $salleManager, EventManager $eventManager, CategoryManager $categoryManager){
        $salles = $salleManager->getSalles();
        $events = $eventManager->getEvents();
        $categories = $categoryManager->getCategories();

        return $this->serializer->prepareResponse(['salles' => $salles, 'categories' => $categories, 'events' => $events], 'event_search');
    }

    /**
     * @Route("/event/latest", name="getLatestEvent", methods={"GET"})
     * @param EventManager $eventManager
     * @return Response
     */
    public function getLatestEvent(EventManager $eventManager){
        $event = $eventManager->getLatestEvent();
        return $this->serializer->prepareResponse($event, 'event_details');

    }

    /**
     * @Route("/event/{idEvent}", name="getEvent")
     * @param $idEvent
     * @param EventManager $eventManager
     * @return Response
     */
    public function getEvent($idEvent, EventManager $eventManager){
        $event = $eventManager->findEventById($idEvent);

        if(!$event) {
            return (new JsonResponse('event not found'))->setStatusCode(404);
        }

        return $this->serializer->prepareResponse($event, "event_details");
    }
}
