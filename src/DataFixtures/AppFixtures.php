<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;



class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker)); //? Extension pour faker pour des photos via picsum

        
        for ($c = 0; $c < 5; $c++)
        {
            $category = new Category;
            $category
                ->setNameC($faker->company())
                ->setSlugC(strtolower($this->slugger->slug($category->getNameC()))); //? le service slugger converti EN slug, le NAME de la CATEGORIE

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(7, 8); $p++)
            {
                $product = new Product;
                $product
                    ->setNameP($faker->sentence(3))
                    ->setPriceP(mt_rand(75, 25000))
                    // ->setSlug($this->slugger->slug($product->getName()));  //! Warning, slug PascalCase !
                    ->setSlugP(strtolower($this->slugger->slug($product->getNameP()))) //? "strtolower" = permet d'avoir les slugs en miniscule uniquement
                    ->setCategory($category)
                    ->setDescriptionP($faker->Text(100))
                    ->setPictureP($faker->imageUrl(200, 200, true));
    
                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
