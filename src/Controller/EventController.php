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
}
