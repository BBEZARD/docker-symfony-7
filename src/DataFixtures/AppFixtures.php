<?php

namespace App\DataFixtures;

use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\User;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Commerce($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $admin = new User();

        $hash = $this->encoder->hashPassword($admin, 'password');

        $admin
            ->setEmail('bruno.bezard@gmail.com')
            ->setPassword($hash)
            ->setFullname('Bruno Bezard')
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $categories = [];

        for ($c = 0; $c < 6; ++$c) {
            $category = new Categories();
            $category
                ->setName($faker->department)
                ->setDescription($faker->paragraph(3))
            ;
            $manager->persist($category);

            $categories[] = $category;
        }

        for ($a = 0; $a < mt_rand(5, 10); ++$a) {
            $article = new Articles();
            $article
                ->setTitle($faker->productName)
                ->setShortDescription($faker->paragraph(3))
                ->setContent($faker->paragraph(10))
                ->setMainPicture($faker->imageUrl(400, 400, true))
                ->setCreatedAt($faker->dateTimeBetween('-6 months'))
            ;

            if ($faker->boolean(90)) {
                $article->setStatus(Articles::STATUS_PUBLISHED);
            }

            for ($i = 0; $i < mt_rand(1, 4); ++$i) {
                $article
                    ->addCategory($faker->randomElement($categories));
            }

            $manager->persist($article);
        }

        $manager->flush();
    }
}
