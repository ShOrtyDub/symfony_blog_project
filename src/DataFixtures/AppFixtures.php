<?php

namespace App\DataFixtures;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Commentaires;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $faker;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->truncate($manager);
        $this->teamFixtures($manager);
        $this->usersFixtures($manager);
        $this->categoriesFixtures($manager);
        $this->articlesFixtures($manager);
        $this->commentairesFixtures($manager);

        $manager->flush();
    }

    protected function teamFixtures($manager): void
    {
        $team = new Team;
        $team->setEmail('test@test.com');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $team,
            '123'
        );
        $team->setPassword($hashedPassword);
        $team->setRoles(['ROLE_ADMIN']);
        $team->setNom('Rakoto');
        $team->setPrenom('Mathieu');

        $manager->persist($team);
        $manager->flush();
    }

    protected function usersFixtures($manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $user[$i] = new User;
            $user[$i]->setEmail('user'.$i.'@test.com');
            $user[$i]->setNom($this->faker->lastName());
            $user[$i]->setPrenom($this->faker->firstName());
            $hashedPassword = $this->passwordHasher->hashPassword($user[$i], '123');
            $user[$i]->setPassword($hashedPassword);
            $user[$i]->setRoles(['ROLE_VISITOR']);
            $manager->persist($user[$i]);
        }
        $manager->flush();
    }

    protected function categoriesFixtures($manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $categories[$i] = new Categories;
            $categories[$i]->setNom($this->faker->firstName());
            $manager->persist($categories[$i]);
        }
        $manager->flush();
    }

    protected function articlesFixtures($manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $articles[$i] = new Articles;
            $articles[$i]->setTitre($this->faker->city());
            $articles[$i]->setAuteur($this->faker->lastName());
            $articles[$i]->setDate($this->faker->dateTime());
            $articles[$i]->setTexte($this->faker->text());
            $articles[$i]->setFKCategories($this->getRandomReference(Categories::class, $manager));
            $articles[$i]->setFKTeam($this->getReferencedObject(Team::class, 1, $manager));
            $articles[$i]->setFKUser($this->getRandomReference(User::class, $manager));
            $articles[$i]->setLogo('https://loremflickr.com/640/480/superhero');
            $manager->persist($articles[$i]);
        }
        $manager->flush();
    }

    protected function commentairesFixtures($manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $commentaires[$i] = new Commentaires;
            $commentaires[$i]->setAuteur($this->faker->lastName());
            $commentaires[$i]->setDateHeure($this->faker->dateTime());
            $commentaires[$i]->setTexte($this->faker->text());
            $commentaires[$i]->setCommentaire($this->faker->word());
            $commentaires[$i]->setStatus($this->faker->boolean());
            $commentaires[$i]->setFKUser($this->getRandomReference(User::class, $manager));
            $commentaires[$i]->setFKArticles($this->getRandomReference(Articles::class, $manager));

            $manager->persist($commentaires[$i]);
        }
        $manager->flush();
    }


    protected function getReferencedObject(string $className, int $id, object $manager)
    {
        return $manager->find($className, $id);
    }

    protected function getRandomReference(string $className, object $manager)
    {
        $list = $manager->getRepository($className)->findAll();
        return $list[array_rand($list)];
    }

    protected function truncate($manager): void
    {
        /**
         * @var Connection db
         */
        $db = $manager->getConnection();

        $db->beginTransaction();

        $sql = '
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE team;
        TRUNCATE user;
        TRUNCATE category;
        TRUNCATE articles;
        TRUNCATE commentaires;
        SET FOREIGN_KEY_CHECKS =1;';

        $db->prepare($sql);
        $db->executeQuery($sql);

        $db->commit();
        $db->beginTransaction();
    }
}
