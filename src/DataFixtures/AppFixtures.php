<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\DBAL\Connection;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;



class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger, Connection $connection)
    {
        $this->slugger = $slugger;
        $this->connection = $connection;

    }

    private function truncate() // Discussion en SQL
    {
        // Désactivation des contraintes 
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        // Let's go tronquage
        $this->connection->executeQuery('TRUNCATE TABLE product');
        $this->connection->executeQuery('TRUNCATE TABLE category');
        $this->connection->executeQuery('TRUNCATE TABLE user');
    }

    
    public function load(ObjectManager $manager): void
    {
        // Truncate des tables manuellement pour revenir à ID = 1
        $this->truncate();

        // Création instance de Faker, et en FR
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker)); //? Extension pour faker pour des photos via picsum

        $admin = new User;
        $admin
            ->setUsername("admin")
            ->setEmail("admin@gmail.com")
            ->setPassword("$2y$13\$OtcPGxMEQ7rZ4MnnN5g8cuwG22lCvSRWYnqzryzy4jG/Y70eyzPwS") // MDP "admin"
            ->setRoles(['ROLE_ADMIN'])
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setPhone1($faker->phoneNumber());
        $manager->persist($admin);

        for ($u=0; $u < 5; $u++)
        { 
            $user = new User;
            $user
                ->setUsername("user$u")
                ->setEmail("user$u@gmail.com")
                ->setPassword("$2y$13\$uM56.C2mpYqMv8m5ydyOIuvfkinVCcuQ0IJ5A37ILfVcMShBlQnpm") // MDP "user"
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setPhone1($faker->phoneNumber());

            $manager->persist($user);
        }


        for ($c = 0; $c < 4; $c++)
        {
            $category = new Category;
            $category
                ->setName($faker->company())
                ->setPicture($faker->imageUrl(200, 200, true))
                ->setSlug(strtolower($this->slugger->slug($category->getName()))); //? le service slugger converti EN slug, le NAME de la CATEGORIE

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(2, 5); $p++)
            {
                $product = new Product;
                $product
                    ->setName($faker->sentence(3))
                    ->setPrice(mt_rand(75, 25000))
                    // ->setSlug($this->slugger->slug($product->getName()));  //! Warning, slug PascalCase !
                    ->setSlug(strtolower($this->slugger->slug($product->getName()))) //? "strtolower" = permet d'avoir les slugs en miniscule uniquement
                    ->setCategory($category)
                    ->setDescription($faker->Text(100))
                    ->setPicture($faker->imageUrl(200, 200, true));
    
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
