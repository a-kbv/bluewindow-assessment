<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $brands = [
            [
                'name' => 'Mad casino',
                'image' => 'https://www.opnminded.com/content/cms-images/b154d6445b509e4019745aeffed55cb733ed3f6d-600x240.webp',
                'rating' => 5
            ],
            [
                'name' => 'Robocat',
                'image' => 'https://www.opnminded.com/content/cms-images/72b7f3b9db10e4e8bf29c90d4c8ab981e9aa8456-600x240.webp',
                'rating' => 5
            ],
            [
                'name' => 'Spinsy Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/98d3a00c294c721c914fe61c0f7ef75dbb5da638-600x240.webp',
                'rating' => 5
            ],
            [
                'name' => 'Talismania Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/25a772c9a3b21fba4e3747fbe864f8a010782010-600x240.webp',
                'rating' => 5
            ],
            [
                'name' => 'Legendplay Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/fcd92746d6eb7fcf11aa856db6f9c4826e6ff2d6-600x240.webp',
                'rating' => 5
            ],
        ];

        foreach ($brands as $brandData) {
            $brand = new Brand();
            $brand->setName($brandData['name']);
            $brand->setImage($brandData['image']);
            $brand->setRating($brandData['rating']);

            $manager->persist($brand);
        }

        $manager->flush();
    }
}
