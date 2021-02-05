<?php


namespace App\DataFixtures;


use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Entity\Category;
use App\Entity\Concert;
use App\Entity\Event;
use App\Entity\Parking;
use App\Entity\Restaurant;
use App\Entity\Salle;
use App\Entity\User;
use App\Repository\ConcertRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function createArticleCategory($name, $manager){
        $category = new ArticleCategory();
        $category->setName($name);
        $manager->persist($category);
        $manager->flush();
        return $category;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $adminExists = $manager->getRepository(User::class)->findOneBy(['email' => 'admin.admin@fixture.fr']);
        $userExists = $manager->getRepository(User::class)->findOneBy(['email' => 'user.user@fixture.fr']);
        if(!$adminExists) {
            // create admin
            $user = new User();
            $user->setEmail('admin.admin@fixture.fr');
            $user->setFirstName('Admin');
            $user->setLastName('Fixtures');
            $user->setPhoneNumber('0658784512');
            $user->setCountry('France');
            $user->setZipCode('13100');
            $user->setAddress('789, parlà');
            $user->setCity('Aix-en-Provence');
            $user->setGender('F');
            $user->setBirthDate(new DateTime('2011-01-01T15:03:01.012345Z'));
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword('password');
            $password = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();
        }
        if(!$userExists) {
            // create user
            $user = new User();
            $user->setEmail('user.user@fixture.fr');
            $user->setFirstName('User');
            $user->setLastName('Fixtures');
            $user->setPhoneNumber('0658784512');
            $user->setCountry('France');
            $user->setZipCode('13100');
            $user->setAddress('789, parlà');
            $user->setCity('Aix-en-Provence');
            $user->setGender('M');
            $user->setRoles(['ROLE_USER']);
            $user->setBirthDate(new DateTime('1998-01-01T15:03:01.012345Z'));
            $user->setPassword('password');
            $password = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();
        }

        $categoryExists = $manager->getRepository(Category::class)->findOneBy(['name' => 'Rock']);
        if(!$categoryExists) {
            //create category
            $category = new Category();
            $category->setName('Rock');
            $manager->persist($category);
            $manager->flush();
        }

        $infoSalles = [['name' => 'Aix-en-Provence', 'capacity' => 150], ['name' => 'Bourges', 'capacity' => 100], ['name' => 'Cannes', 'capacity' => 200], ['name' => 'Dunkerque', 'capacity' => 100], ['name' => 'Echirolles', 'capacity' => 150]];

        foreach ($infoSalles as $infoSalle) {
            $parkingExists = $manager->getRepository(Parking::class)->findOneBy(['name' => 'Parking ' . $infoSalle['name']]);
            if(!$parkingExists) {
                $parking = new Parking();
                $parking->setName('Parking ' . $infoSalle['name']);
                $parking->setPrice(30);
                $manager->persist($parking);
                $manager->flush();
            }

            $restaurantExists = $manager->getRepository(Restaurant::class)->findOneBy(['name' => 'Restaurant ' . $infoSalle['name']]);
            if(!$restaurantExists) {
                $restaurant = new Restaurant();
                $restaurant->setName('Restaurant ' . $infoSalle['name']);
                $restaurant->setPrice(30);
                $manager->persist($restaurant);
                $manager->flush();
            }

            $salleExists = $manager->getRepository(Salle::class)->findOneBy(['city' => $infoSalle['name']]);
            if (!$salleExists) {
                $salle = new Salle();
                $salle->setName('Salle ' . $infoSalle['name']);
                $salle->setCity($infoSalle['name']);
                $salle->setAddress('123, qqpart');
                $salle->setCapacity($infoSalle['capacity']);
                $salle->setPhoneNumber('0578986564');
                if ($parkingExists) {
                    $salle->setParking($parkingExists);
                } else {
                    $salle->setParking($parking);
                }
                if ($restaurantExists) {
                    $salle->setRestaurant($restaurantExists);
                } else {
                    $salle->setRestaurant($restaurant);
                }
                $manager->persist($salle);
                $manager->flush();
            }

            // create 20 events! Bam!
            for ($i = 1; $i < 3; $i++) {
                $event = new Event();
                if($salleExists){
                    $event->setSalle($salleExists);
                }
                else {
                    $event->setSalle($salle);
                }
                $event->setName($infoSalle['name'] . ' - Event ' . $i);
                $event->setArtistName('Artiste' . $i);
                $event->setArtistDescription('Lorem Ipsum');
                $event->setParking(true);
                $event->setRestaurant(true);
                if($categoryExists){
                    $event->addCategory($categoryExists);
                }
                else {
                    $event->addCategory($category);
                }

                $manager->persist($event);

                for($j = 0; $j < 2; $j++) {
                    $concert = new Concert();

                    $dateTime = new DateTime('2021-02-08T20:00:00.000000Z');
                    $dateTime = $dateTime->add(new \DateInterval('P' . $j . 'D'));

                    $concert->setArtistDescription('Lorem Ipsum');
                    $concert->setCategoryNumber(3);
                    $concert->setDate($dateTime);
                    $concert->setEvent($event);
                    $concert->setOpeningTime($dateTime);
                    $concert->setPercentage(5);
                    $concert->setPriceMax(200);

                    $dateTime = $dateTime->add(new \DateInterval('PT2H'));
                    $concert->setTime($dateTime);

                    $manager->persist($concert);

                    $manager->getRepository(Concert::class)->createSeats($concert);
                }
            }
            $manager->flush();
        }

        // create a few article category
        $this->createArticleCategory("concert", $manager);
        $this->createArticleCategory("events", $manager);
        $this->createArticleCategory("information", $manager);
        $this->createArticleCategory("advertising", $manager);
        $categoryList = $manager->getRepository(ArticleCategory::class)->findAll();
        $j = 0;

        // create 20 articles! Bam!
        $nbExisting = count($manager->getRepository(Article::class)->findAll());

        for ($i = $nbExisting; $i < $nbExisting + 20; $i++) {
            $article = new Article();
            $article->setTitle('Article '.$i);
            if($adminExists){
                $article->setAuthor($adminExists);
            }
            else {
                $article->setAuthor($user);
            }
            $article->setImage('fixtureUrl');
            $article->setText('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ornare dui ultricies orci maximus ultricies. Vestibulum sit amet maximus neque. Nam magna eros, pretium vitae sapien et, dignissim malesuada lorem. Aenean est purus, varius vitae aliquet eget, pretium et odio. Sed bibendum maximus molestie. In ac enim vel sapien ultricies eleifend. In rutrum luctus mauris tincidunt consequat. Nullam elementum lobortis est. Curabitur pharetra nibh augue, rutrum fringilla tortor consequat at. Aenean dictum libero nec sapien imperdiet, at bibendum mi dictum. Nunc ornare ac enim euismod efficitur. Sed et arcu massa. Mauris aliquet nec tellus eu pulvinar.');

            if($j >= count($categoryList)){$j = 0;}
            $article->setCategory($categoryList[$j]);
            $j++;

            $manager->persist($article);
        }

        $manager->flush();

    }

}
