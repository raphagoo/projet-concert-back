<?php


namespace App\Controller;


use App\BL\ArticleCategoryManager;
use App\BL\ArticleManager;
use App\Entity\Article;
use App\Helpers\SerializerHelper;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ArticleController
{

    /**
     * ArticleController constructor.
     * @param EntityManagerInterface $em
     */
    private LoggerInterface $logger;
    private SerializerHelper $serializer;
    private Security $security;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, Security $security, LoggerInterface $logger,
                                SerializerHelper $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * @Route ("/articles", name="createArticle", methods={"POST"})
     * @param Request $request
     * @param ArticleCategoryManager $categoryManager
     * @return Response
     */
    public function createArticle(Request $request, ArticleCategoryManager $categoryManager){
        $data = json_decode($request->getContent(), true);

        try {
            $title = $data['title'];
            $text = $data['text'];
            $categoryId = $data['category'];
            $image = $data['image'];
        } catch (ErrorException $e){
            $message = "A required field is missing (required : title, image, text, category)";
            throw new BadRequestHttpException($message);
        }
        $user = $this->security->getUser();

        $category = $categoryManager->findArticleCategoryById($categoryId);
        if(!$category) {
            return (new JsonResponse('category not found'))->setStatusCode(404);
        }

        $article = new Article();
        $article->setAuthor($user);
        $article->setTitle($title);
        $article->setText($text);
        $article->setCategory($category);
        $article->setImage($image);

        $this->em->persist($article);
        $this->em->flush();

        return $this->serializer->prepareResponse($article, 'article');
    }


    /**
     * @Route ("/articles", name="listArticles", methods={"GET"})
     * @param ArticleManager $articleManager
     * @return Response
     */
    public function listArticles(ArticleManager $articleManager){
        $articles = $articleManager->getArticles();

        return $this->serializer->prepareResponse($articles, 'article');
    }

    /**
     * @Route("/article/{idArticle}", name="getArticle", methods={"GET"})
     * @param $idArticle
     * @param ArticleManager $articleManager
     * @return Response
     */
    public function getArticle($idArticle, ArticleManager $articleManager){
        $article = $articleManager->findArticleById($idArticle);

        if(!$article) {
            return (new JsonResponse('article not found'))->setStatusCode(404);
        }

        return $this->serializer->prepareResponse($article, "article");
    }
}