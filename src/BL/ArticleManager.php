<?php


namespace App\BL;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class ArticleManager
{
    /**
     * ArticleManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Article[]
     */
    public function getArticles(){
        return $this->em->getRepository(Article::class)->findAll();
    }

    /**
     * @param $idArticle
     * @return Article|null
     */
    public function findArticleById($idArticle){
        return $this->em->getRepository(Article::class)->find($idArticle);
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function save(Article $article){

        $this->em->persist($article);
        $this->em->flush();
        return $article;
    }

    /**
     * @param $article
     */
    public function deleteArticle($article)
    {
        $this->em->remove($article);
        $this->em->flush();
    }
}
