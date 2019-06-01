<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Article;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends BaseFixture implements DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(100, "fake-comments", function($count) {

            $comment = new Comment();
            $comment->setContent(
              $this->faker->boolean ? $this->faker->paragraph: $this->faker->sentences(2, true)
            );
            $comment->setAuthorName($this->faker->name);
            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));

            $comment->setArticle(
                $this->getRandomReference('fake-articles')
            );

            $comment->setIsDeleted($this->faker->boolean(20));

            return $comment;

        });
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [ArticleFixtures::class];
    }
}
