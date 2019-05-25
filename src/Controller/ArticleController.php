<?php
namespace App\Controller;

use App\Entity\Article;
use App\Service\MarkdownHelper;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
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
  public function homepage()
  {

    return $this->render("article/homepage.html.twig");

  }

    /**
     * @Route("/news/{slug}", name="article_show")
     * @param $slug
     * @param MarkdownHelper $markdownHelper
     * @param SlackClient $slack
     * @return Response
     */
  public function show($slug, MarkdownHelper $markdownHelper, SlackClient $slack, EntityManagerInterface $em)
  {

      if($slug === "wazir") {

          $slack->sendMessage($slug, "This message from Little Techie Dev");
      }

      $repository = $em->getRepository(Article::class);
      $article = $repository->findOneBy(['slug'=>$slug]);
      if (!$article) {
          throw $this->createNotFoundException(sprintf("No article for slug %s", $slug));
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
  public function toggleArticleHeart($slug)
  {
    //return new JsonResponse(['heart' => rand(5, 100)]);
    return $this->json(['heart' => rand(5, 100)]);
  }


}
