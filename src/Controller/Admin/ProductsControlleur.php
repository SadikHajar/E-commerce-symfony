<?php
namespace App\Controller\Admin;
use App\Entity\Images;
use App\Entity\Productss;
use App\Repository\ProductssRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductsFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
#[Route('/admin/produits',name:'admin_products_')]

class ProductsController extends AbstractController
{
    #[Route('/',name:'index')]
    public function index(ProductssRepository $productsRepository): Response
    {
        $produits = $productsRepository->findAll();
        return $this->render('admin/products/index.html.twig', compact('produits'));
    }
    #[Route('/ajout',name:'add')]
    public function add(Request $request,EntityManagerInterface $em,SluggerInterface $slugger,PictureService $pictureService):Response
    {   
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        $product = new Productss();
        $productForm =$this->createForm(ProductsFormType::class , $product);
        $productForm->handleRequest($request);
        if($productForm->isSubmitted() && $productForm->isValid()){
            //images
            $images= $productForm->get('images')->getData();
            foreach($images as $image){
                // On définit le dossier de destination
                $folder = 'products';

                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Images();
                $img->setName($fichier);
                $product->addImage($img);}
            

            $slug=$slugger->slug($product->getName());
            $product->setSlug($slug);
            $prix=$product->getPrice()* 100;
            $product->setPrice($prix);
            $em->persist($product);
            $em->flush();
             
            return $this->redirectToRoute('admin_products_index'); 

        }
       return $this->renderForm('admin/products/add.html.twig',compact('productForm')); 
    }
    #[Route('/edition/{id}',name:'edit')]
    public function edit(Productss $product,Request $request,EntityManagerInterface $em,SluggerInterface $slugger):Response
    {   
        $this->denyAccessUnlessGranted('PRODUCT_EDIT',$product);
        $prix=$product->getPrice()/100;
        $product->setPrice($prix);
        //$product = new Productss();
        $productForm =$this->createForm(ProductsFormType::class , $product);
        $productForm->handleRequest($request);
        if($productForm->isSubmitted() && $productForm->isValid()){
            $slug=$slugger->slug($product->getName());
            $product->setSlug($slug);
            $prix=$product->getPrice()* 100;
            $product->setPrice($prix);
            $em->persist($product);
            $em->flush();
            
            return $this->redirectToRoute('admin_products_index'); 

        }
       return $this->renderForm('admin/products/edit.html.twig',compact('productForm')); 
        
    }
    #[Route('/suppression/{id}', name: 'delete')]
public function delete(Productss $product, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
    
    // Delete the product from the database
    $entityManager->remove($product);
    $entityManager->flush();
    
    return $this->redirectToRoute('admin_products_index');
}


}
?>