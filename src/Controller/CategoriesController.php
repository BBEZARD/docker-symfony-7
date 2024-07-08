<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoryType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoriesController extends AbstractController
{
    protected $header;
    protected $footer;
    protected $category;
    protected CategoriesRepository $categoriesRepository;
    protected TranslatorInterface $translator;

    public function __construct(CategoriesRepository $categoriesRepository, TranslatorInterface $translator)
    {
        $header = Yaml::parseFile(__DIR__.'/../Entity/header.yaml');
        $this->header = $header;

        $footer = Yaml::parseFile(__DIR__.'/../Entity/footer.yaml');
        $this->footer = $footer;

        $category = Yaml::parseFile(__DIR__.'/../Entity/category.yaml');
        $this->category = $category;

        $this->categoriesRepository = $categoriesRepository;

        $this->translator = $translator;
    }

 #[Route(path: '/admin/categories', name: 'categories_list')]
 public function categoriesList(): Response
 {
     $categories = $this->categoriesRepository->findBy([], ['name' => 'ASC']);

     return $this->render('category/categories_list.html.twig', [
         'header' => $this->header,
         'table' => $this->category,
         'categories' => $categories,
         'footer' => $this->footer,
     ]);
 }

    #[Route(path: '/admin/category/create', name: 'category_create')]
 public function create(Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
     $category = new Categories();

     $form = $this->createForm(CategoryType::class, $category);

     $form->handleRequest($request);

     if ($form->isSubmitted()) {
         if ($form->isValid()) {
             $em->persist($category);
             $em->flush();
             $this->addFlash('success', sprintf($this->translator->trans('category.created.done', [], 'messages', 'fr_FR'), $category->getName()));

             return $this->redirectToRoute('categories_list', [
             ]);
         } else {
             $this->addFlash('danger', 'category.created.error');
         }
     }

     $formView = $form->createView();

     return $this->render('category/category_create.html.twig', [
         'formView' => $formView,
         'title' => $this->category,
         'header' => $this->header,
         'footer' => $this->footer,
     ]);
 }

    #[Route(path: '/admin/category/{id}/edit', name: 'category_edit')]
 public function edit(int $id, Request $request, EntityManagerInterface $em): RedirectResponse|Response
    {
     $category = $this->categoriesRepository->find($id);

     $form = $this->createForm(CategoryType::class, $category);

     $form->handleRequest($request);

     if ($form->isSubmitted()) {
         if ($form->isValid()) {
             $em->flush();
             $this->addFlash('success', sprintf($this->translator->trans('category.updated.done', [], 'messages', 'fr_FR'), $category->getName()));

             return $this->redirectToRoute('categories_list', [
             ]);
         } else {
             $this->addFlash('danger', 'category.updated.error');
         }
     }

     $formView = $form->createView();

     return $this->render('category/category_edit.html.twig', [
         'category' => $category,
         'title' => $this->category,
         'formView' => $formView,
         'header' => $this->header,
         'footer' => $this->footer,
     ]);
 }

    #[Route(path: '/admin/category/{id}/delete', name: 'category_delete')]
 public function delete(int $id, EntityManagerInterface $em): Response
 {
     $category = $this->categoriesRepository->find($id);

     if (!$category) {
         throw $this->createNotFoundException(sprintf($this->translator->trans('category.delete.error'), $id));
     }

     $em->remove($category);
     $em->flush();

     $this->addFlash('success', $this->translator->trans('category.deleted.done'));

     return $this->redirectToRoute('categories_list');
 }
}
