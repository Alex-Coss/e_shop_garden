<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ItemPurchase;
use App\Entity\Purchase;
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
        // Désactivation des contraintes TEMPORAIREMENT
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        // Let's go tronquage
        $this->connection->executeQuery('TRUNCATE TABLE product');
        $this->connection->executeQuery('TRUNCATE TABLE category');
        $this->connection->executeQuery('TRUNCATE TABLE user');
        // rajout au fur et à mesure les infos de truncate
        $this->connection->executeQuery('TRUNCATE TABLE purchase');
        $this->connection->executeQuery('TRUNCATE TABLE item_purchase');
    }

    
    public function load(ObjectManager $manager): void
    {
        // Truncate des tables manuellement pour revenir à ID = 1
        $this->truncate();

        // Création instance de Faker, et en FR
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker)); //? Extension pour faker pour des photos via picsum


        // !  -_-_-_-_-_  NEW USER _-_-_-_-_-


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

            // Je lie l'user à l'user de la PURCHASE
            $users[] = $user;

            $manager->persist($user);
        }



        $products = [];



        // !  -_-_-_-_-_  NEW CATEGORY _-_-_-_-_-


        for ($c = 0; $c < 4; $c++)
        {
            $category = new Category;
            $category
                ->setName($faker->company())
                ->setPicture($faker->imageUrl(200, 200, true))
                ->setSlug(strtolower($this->slugger->slug($category->getName()))); //? le service slugger converti EN slug, le NAME de la CATEGORIE

            $manager->persist($category);



        // !  -_-_-_-_-_  NEW PRODUCT _-_-_-_-_-



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

                    $products[] = $product;
    
                $manager->persist($product);
            }
        }


        // !  -_-_-_-_-_  NEW PURCHASE _-_-_-_-_-



        for ($p=0; $p < mt_rand(10, 20); $p++)
        { 
            $purchase = new Purchase;

            $purchase
                ->setFullName($faker->name())
                ->setAddress($faker->streetAddress())
                ->setZipCode($faker->postcode())
                ->setCity($faker->city())
                ->setPhone1($faker->phoneNumber())
                // Faker va chercher dans les $users (voir $user) et il va en choisir aléatoirement pour générer des fixtures
                ->setUser($faker->randomElement($users))
                ->setPurchaseAt($faker->dateTimeBetween('-6 months'))
                ->setTotal(mt_rand(2000, 50000));

                $selectedProducts = $faker->randomElements($products, mt_rand(3, 5));

            //!  -_-_-_-_-_  NEW ITEM_PURCHASE _-_-_-_-_-

                foreach ($selectedProducts as $product)
                {
                    $itemPurchase = new ItemPurchase;
                    $itemPurchase
                        ->setProduct($product)
                        ->setProductName($product->getName())
                        ->setProductPrice($product->getPrice())
                        ->setPurchase($purchase)
                        ->setQuantity(mt_rand(1, 5))
                        ->setTotal($itemPurchase->getProductPrice() * $itemPurchase->getQuantity());
                    
                        $manager->persist($itemPurchase);
                }
                
            if ($faker->boolean(75)) //75% de chance que le status de la commande soit payée, et plus en attente (pending)
            {
                $purchase->setStatus(Purchase::STATUS_PAID);
            } // pas de ELSE, car dans l'entity, il a été décidé que par défaut, ce serait PENDING

            $manager->persist($purchase);
        }

        $manager->flush();
    }
}
