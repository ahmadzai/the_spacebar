<?php


namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ArticleAdminController
 * @package App\Controller
 */
class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new", name="app_article_new")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article Created! Knowledge is power!');

            return $this->redirectToRoute("admin_article_list");
        }

        return $this->render("article_admin/new.html.twig", [
            'articleForm' => $form->createView(),
            'test' => 'this is a test variable'
        ]);

    }

    /**
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/article/{id}/edit", name="admin_article_edit")
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ArticleFormType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article Updated! Knowledge is power!');

            return $this->redirectToRoute("admin_article_edit", [
                'id' => $article->getId()
            ]);
        }

        return $this->render("article_admin/edit.html.twig", [
            'articleForm' => $form->createView(),
            'test' => 'this is a test variable'
        ]);

    }

    /**
     * @param ArticleRepository $repository
     * @return Response
     * @Route("/admin/article/", name="admin_article_list")
     */
    public function list(ArticleRepository $repository)
    {
        $articles = $repository->findAll();
        return $this->render('article_admin/list.html.twig', [
           'articles' => $articles
        ]);
    }

}