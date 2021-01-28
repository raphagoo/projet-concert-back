<?php


namespace App\BL;


use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager
{
    /**
     * CategoryManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /*** @var EntityManagerInterface l'interface entity manager* nécessaire à la manipulation des opérations en base*/
    protected $em;


    /**
     * @return Category[]
     */
    public function getCategories(){
        return $this->em->getRepository(Category::class)->findAll();
    }

    /**
     * @param $idCategory
     * @return Category|null
     */
    public function findCategoryById($idCategory){
        return $this->em->getRepository(Category::class)->find($idCategory);
    }

    /**
     * @param Category $category
     * @return Category
     */
    public function save(Category $category){

        $this->em->persist($category);
        $this->em->flush();
        return $category;
    }

    /**
     * @param $category
     */
    public function deleteCategory($category)
    {
        $this->em->remove($category);
        $this->em->flush();
    }
}
