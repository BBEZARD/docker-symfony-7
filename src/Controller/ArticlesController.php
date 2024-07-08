<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticleType;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArticlesController extends AbstractController
{
    protected $header;
    protected $footer;
    protected $title;
    protected $cookie;
    protected TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $header = Yaml::parseFile(__DIR__.'/../Entity/header.yaml');
        $this->header = $header;

        $title = Yaml::parseFile(__DIR__.'/../Entity/articles.yaml');
        $this->title = $title;

        $footer = Yaml::parseFile(__DIR__.'/../Entity/footer.yaml');
        $this->footer = $footer;

        $cookie = Yaml::parseFile(__DIR__.'/../Entity/cookie.yaml');
        $this->cookie = $cookie;

        $this->translator = $translator;
    }

 #[Route(path: '/admin/articles', name: 'articles_list')]
 public function articlesList(ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository): Response
 {
     $articles = $articlesRepository->findBy([], ['createdAt' => 'DESC']);
     $categories = $categoriesRepository->findBy([], ['name' => 'ASC']);

     return $this->render('article/articles_list.html.twig', [
         'controller_name' => 'ArticlesController',
         'header' => $this->header,
         'articles' => $articles,
         'title' => $this->title,
         'categories' => $categories,
         'footer' => $this->footer,
     ]);
 }

 #[Route(path: '/articles', name: 'articles')]
 public function articlesLatest(ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository): Response
 {
     $articles = $articlesRepository->findBy([], ['createdAt' => 'DESC']);
     $categories = $categoriesRepository->findBy([], ['name' => 'ASC']);

     return $this->render('article/articles_latest.html.twig', [
         'controller_name' => 'ArticlesController',
         'header' => $this->header,
         'articles' => $articles,
         'title' => $this->title,
         'categories' => $categories,
         'cookie' => $this->cookie,
         'footer' => $this->footer,
     ]);
 }

 #[Route(path: '/articles/{slug}', name: 'articles_category', priority: -1)]
 public function category(string $slug, CategoriesRepository $categoryRepository): Response
 {
     $category = $categoryRepository->findOneBy([
         'slug' => $slug,
     ]);

     if (!$category) {
         throw $this->createNotFoundException(sprintf($this->translator->trans('article.category.error'), $slug));
     }

     return $this->render('article/articles_category.html.twig', [
         'controller_name' => 'ArticlesController',
         'header' => $this->header,
         'category' => $category,
         'title' => $this->title,
         'cookie' => $this->cookie,
         'footer' => $this->footer,
     ]);
 }

 #[Route(path: '/articles/{categories_slug}/{slug}', name: 'article_show')]
 public function show(string $slug, ArticlesRepository $articlesRepository): Response
 {
     $article = $articlesRepository->findOneBy([
         'slug' => $slug,
     ]);

     if (!$article | (!$this->isGranted('ROLE_ADMIN') && $article->getStatus() === Articles::STATUS_DRAFT)) {
         throw $this->createNotFoundException(sprintf($this->translator->trans('article.show.error'), $slug));
     }

     return $this->render('article/article_show.html.twig', [
         'controller_name' => 'ArticlesController',
         'header' => $this->header,
         'article' => $article,
         'title' => $this->title,
         'cookie' => $this->cookie,
         'footer' => $this->footer,
     ]);
 }

    #[Route(path: '/admin/article/create', name: 'article_create')]
 public function create(Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
     $article = new Articles();

     $form = $this->createForm(ArticleType::class, $article);

     $form->handleRequest($request);

     if ($form->isSubmitted()) {
         if ($form->isValid()) {
             $em->persist($article);
             $em->flush();
             $this->addFlash('success', sprintf($this->translator->trans('article.created.done'), $article->getTitle()));

             return $this->redirectToRoute('article_show', [
                 'categories_slug' => $article->getCategories()->first()->getSlug(),
                 'slug' => $article->getSlug(),
             ]);
         } else {
             $this->addFlash('danger', 'article.created.error');
         }
     }

     $formView = $form->createView();

     return $this->render('article/article_create.html.twig', [
         'formView' => $formView,
         'header' => $this->header,
         'title' => $this->title,
         'footer' => $this->footer,
     ]);
 }

    #[Route(path: '/admin/article/{id}/edit', name: 'article_edit')]
 public function edit(int $id, ArticlesRepository $articlesRepository, Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
     $article = $articlesRepository->find($id);

     $form = $this->createForm(ArticleType::class, $article);

     $form->handleRequest($request);

     if ($form->isSubmitted()) {
         if ($form->isValid()) {
             $em->flush();
             $this->addFlash('success', sprintf($this->translator->trans('article.updated.done'), $article->getTitle()));

             return $this->redirectToRoute('article_show', [
                 'categories_slug' => $article->getCategories()->first()->getSlug(),
                 'slug' => $article->getSlug(),
             ]);
         } else {
             $this->addFlash('danger', 'article.updated.error');
         }
     }

     $formView = $form->createView();

     return $this->render('article/article_edit.html.twig', [
         'article' => $article,
         'title' => $this->title,
         'header' => $this->header,
         'footer' => $this->footer,
         'formView' => $formView,
     ]);
 }

    #[Route(path: '/admin/article/{id}/delete', name: 'article_delete')]
 public function delete(int $id, ArticlesRepository $articlesRepository, EntityManagerInterface $em): Response
 {
     $article = $articlesRepository->find($id);

     if (!$article) {
         throw $this->createNotFoundException(sprintf($this->translator->trans('article.delete.error'), $id));
     }

     $em->remove($article);
     $em->flush();

     $this->addFlash('success', $this->translator->trans('article.deleted.done'));

     return $this->redirectToRoute('articles_list');
 }
}
