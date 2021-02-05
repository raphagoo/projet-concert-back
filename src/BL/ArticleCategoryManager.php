<?php


namespace App\BL;


use App\Entity\ArticleCategory;
use Doctrine\ORM\EntityManagerInterface;

class ArticleCategoryManager
{
    /**
     * ArticleCategoryManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return ArticleCategory[]
     */
    public function getCategories(){
        return $this->em->getRepository(ArticleCategory::class)->findAll();
    }

    /**
     * @param $idArticleCategory
     * @return ArticleCategory|null
     */
    public function findArticleCategoryById($idArticleCategory){
        return $this->em->getRepository(ArticleCategory::class)->find($idArticleCategory);
    }

    /**
     * @param ArticleCategory $category
     * @return ArticleCategory
     */
    public function save(ArticleCategory $category){

        $this->em->persist($category);
        $this->em->flush();
        return $category;
    }

    /**
     * @param $category
     */
    public function deleteArticleCategory($category)
    {
        $this->em->remove($category);
        $this->em->flush();
    }
}
