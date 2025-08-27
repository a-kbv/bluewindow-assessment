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
                'name' => 'Mad Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/b154d6445b509e4019745aeffed55cb733ed3f6d-600x240.webp',
                'rating' => 5,
                'countryCode' => 'BG'
            ],
            [
                'name' => 'Robocat Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/72b7f3b9db10e4e8bf29c90d4c8ab981e9aa8456-600x240.webp',
                'rating' => 5,
                'countryCode' => 'GB'
            ],
            [
                'name' => 'Spinsy Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/98d3a00c294c721c914fe61c0f7ef75dbb5da638-600x240.webp',
                'rating' => 5,
                'countryCode' => 'DE'
            ],
            [
                'name' => 'Talismania Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/25a772c9a3b21fba4e3747fbe864f8a010782010-600x240.webp',
                'rating' => 5,
                'countryCode' => 'BG'
            ],
            [
                'name' => 'Legendplay Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/fcd92746d6eb7fcf11aa856db6f9c4826e6ff2d6-600x240.webp',
                'rating' => 5,
                'countryCode' => 'CA'
            ],
            [
                'name' => 'Royal Vegas Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/0fdf91013606b4b7c03bb2f40ba8ebaecd75123e-600x240.webp',
                'rating' => 4,
                'countryCode' => 'GB'
            ],
            [
                'name' => 'Golden Palace Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/37dd40ae4b9a5931a1aa5353ea11e5dede1ac728-600x240.webp',
                'rating' => 4,
                'countryCode' => 'DE'
            ],
            [
                'name' => 'Lucky Star Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/436bbfceef76c9aa33689ba108665157a0a093dd-600x240.webp',
                'rating' => 3,
                'countryCode' => 'BG'
            ],
            [
                'name' => 'Casino Sofia',
                'image' => 'https://www.opnminded.com/content/cms-images/a479babd7c86e4ec339f045db153a3248037ec0e-600x240.webp',
                'rating' => 4,
                'countryCode' => 'BG'
            ],
            [
                'name' => 'Varna Palace Casino',
                'image' => 'https://www.opnminded.com/content/cms-images/e7139b6fab43defa27dd2da166e9918d0d257791-600x240.webp',
                'rating' => 4,
                'countryCode' => 'BG'
            ]
        ];

        foreach ($brands as $brandData) {
            $brand = new Brand();
            $brand->setName($brandData['name']);
            $brand->setImage($brandData['image']);
            $brand->setRating($brandData['rating']);
            $brand->setCountryCode($brandData['countryCode']);

            $manager->persist($brand);
        }

        $manager->flush();
    }
}
