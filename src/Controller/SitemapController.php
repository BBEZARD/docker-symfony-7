<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SitemapController extends AbstractController
{
    #[Route(path: '/sitemap.xml', name: 'sitemap', defaults: ['_format' => 'xml'])]
    public function index(Request $request, ArticlesRepository $articlesRepository, CategoriesRepository $categoriesRepository, TranslatorInterface $translator): Response
    {
        $hostname = $request->getSchemeAndHttpHost();
        $urls = [];

        $urls[] = [
            'loc' => $this->generateUrl('home'),
            'lastmod' => '2022-11-21',
        ];
        $urls[] = ['loc' => $this->generateUrl('about')];

        foreach ($articlesRepository->findBy(['status' => 'PUBLISHED']) as $article) {
            $images = [
                'loc' => $article->getMainPicture(),
                'title' => $article->getSlug(),
            ];

            $urls[] = [
                'loc' => $this->generateUrl('article_show', [
                    'categories_slug' => $article->getCategories()->first()->getSlug(),
                    'slug' => $article->getSlug(),
                ]),
                'lastmod' => $article->getUpdatedAt()->format('Y-m-d'),
                'image' => $images,
            ];
        }

        foreach ($categoriesRepository->findAll() as $category) {
            $urls[] = [
                'loc' => '/articles/'.$category->getSlug(),
            ];
        }

        $response = new Response($this->renderView('sitemap/index.html.twig', [
            'urls' => $urls,
            'hostname' => $hostname,
        ]), \Symfony\Component\HttpFoundation\Response::HTTP_OK);

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
