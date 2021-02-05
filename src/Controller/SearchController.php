<?php


namespace App\Controller;


use App\Helpers\SerializerHelper;
use App\Repository\ConcertRepository;
use App\Repository\SalleRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * ArticleController constructor.
     */
    private LoggerInterface $logger;
    private SerializerHelper $serializer;
    public function __construct(LoggerInterface $logger, SerializerHelper $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;

    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     * @param Request $request
     * @param SalleRepository $salleRepository
     * @return Response
     */
    public function search(Request $request, SalleRepository $salleRepository)
    {
        $data = json_decode($request->getContent(), true);

        if(!isset($data['toSearch'])){
            return (new JsonResponse('toSearch argument missing'))->setStatusCode(400);
        }

        $toSearch = $data['toSearch'];


        $results = $salleRepository->search($toSearch);

        return $this->serializer->prepareResponse(['events' => $results['events'], 'articles' => $results['articles']], 'search');
    }
}
