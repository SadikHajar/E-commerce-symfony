<?php
namespace App\Controller;
use App\Entity\Productss;
use App\Repository\ProductssRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/cart",name="cart_")
     */
class CartController extends AbstractController{
    /**
     * @Route("/",name="index")
     */
    public function index(SessionInterface $session,ProductssRepository $productssRepository){
        $panier=$session->get("panier",[]);
        $dataPanier = [];
        $total=0;
        foreach($panier as $id=>$quantite){
               $product=$productssRepository->find($id);
               $dataPanier[]=[
                "produit"=>$product,
                "quantite"=>$quantite
               ];
               $total+=$product->getPrice()*$quantite;
        }
        $isEmpty = empty($dataPanier);
        return $this->render('cart/index.html.twig',compact("dataPanier","total","isEmpty"));
    }

    /**
     * @Route("/add/{id}",name="add")
     */

    public function add(Productss $product,SessionInterface $session){
        $panier=$session->get("panier",[]);
        $id=$product->getId();
        if(!empty($panier[$id])){
            $panier[$id]++;

        }else{
            $panier[$id]=1;
        }
        $session->set("panier",$panier);
        return $this->redirectToRoute("cart_index");
    

    }

    /**
     * @Route("/remove/{id}",name="remove")
     */

     public function remove(Productss $product,SessionInterface $session){
        $panier=$session->get("panier",[]);
        $id=$product->getId();
        if(!empty($panier[$id])){
            if($panier[$id]>1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
            

        }
        $session->set("panier",$panier);
        return $this->redirectToRoute("cart_index");
    

    }

    /**
     * @Route("/delete/{id}",name="delete")
     */

     public function delete(Productss $product,SessionInterface $session){
        $panier=$session->get("panier",[]);
        $id=$product->getId();
        if(!empty($panier[$id])){
            
                unset($panier[$id]);
            
            

        }
        $session->set("panier",$panier);
        return $this->redirectToRoute("cart_index");
    

    }

}