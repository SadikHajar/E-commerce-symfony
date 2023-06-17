<?php

namespace App\Controller\Admin;

use App\Repository\CategoriesRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\CategoriesFormType;

#[Route('/admin/categories', name: 'admin_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findBy([], ['categoryOrder' => 'asc']);

        return $this->render('admin/categories/index.html.twig', compact('categories'));
    }
    #[Route('/ajout',name:'add')]
    public function add(Request $request,EntityManagerInterface $em,SluggerInterface $slugger):Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $category=new Categories();
        $categoryForm=$this->createForm(CategoriesFormType::class,$category);
        $categoryForm->handleRequest($request);
        
        if($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $slug=$slugger->slug($category->getName());
            $category->setSlug($slug);
            $categoryOrder=$category->getCategoryOrder();
            $category->setCategoryOrder($categoryOrder);
            
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('admin_categories_index'); 


        }
        
        return $this->renderForm('admin/categories/add.html.twig',compact('categoryForm')); 
    }
   
 
}