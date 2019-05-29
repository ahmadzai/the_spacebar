<?php
namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 *
 */
class ArticleController extends AbstractController
{

    /**
  * @Route("/", name="app_homepage")
  */
  public function homepage(ArticleRepository $repository)
  {

      $articles = $repository->findAllPublishedOrderByNewest();

      return $this->render("article/homepage.html.twig",
          [
              'articles' => $articles,
          ]);

  }

    /**
     * @Route("/news/{slug}", name="article_show")
     * @param $article
     * @param SlackClient $slack
     * @return Response
     */
  public function show(Article $article, SlackClient $slack)
  {

      if($article->getSlug() === "wazir") {

          $slack->sendMessage($article->getSlug(), "This message from Little Techie Dev");
      }


      $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
      ];

      return $this->render("article/show.html.twig", [
        'article' => $article,
        'comments' => $comments,
      ]);
  }

  /**
  * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"Post"})
  */
  public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $em)
  {
    //return new JsonResponse(['heart' => rand(5, 100)]);
      $article->incrementHeartCount();
      $em->flush();

      $logger->info("Article is being hearted");

      return $this->json(['heart' => $article->getHeartCount()]);
  }


}
