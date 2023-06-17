<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductssRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Categories $category,ProductssRepository $productssRepository,Request $request): Response
    {
      
        $page=$request->query->getInt('page',1);
        $productss=$productssRepository->findProductsPaginated(1,$category->getslug(),2 );
          
        return $this->render('categories/list.html.twig', compact('category','productss'));
        
    }
}