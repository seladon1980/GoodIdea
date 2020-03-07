<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @var
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        for ($j = 0; $j < 10; $j++) {
            $this->loadBlogPosts($manager);
        }
        $manager->flush();
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        for ($j = 0; $j < 10000; $j++) {
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setCreatedAt($this->faker->dateTime);
            $blogPost->setLikesCount($this->faker->numberBetween(3, 200));
            $blogPost->setStatus($this->faker->randomElement([
                BlogPost::STATUS_REJECTED,
                BlogPost::STATUS_IN_PROGRESS,
                BlogPost::STATUS_COMPLETED,
                BlogPost::STATUS_NEW,
            ]));
            $manager->persist($blogPost);
        }
        $manager->flush();
    }
}
