<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Entree;
use App\Form\EntreeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;

#[Route('/Entree'),isGranted('ROLE_USER')]
class EntreeController extends AbstractController
{
    #[Route('/liste', name: 'app_entree_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        //$em=$this->getDoctrine()->getManager(); 
       $em = $doctrine->getManager();
       $e=new Entree();
       $form= $this->createForm(EntreeType::class, $e,
       array('action'=> $this->generateUrl(route:'app_entree_add'))
       );
       $data['form']= $form->createView();
       $data['entrees']= $em->getRepository(Entree::class)->findAll();
         
        return $this->render('entree/liste.html.twig',$data);
    }
    
     #[Route('/add', name: 'app_entree_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $e=new Entree();
        $form = $this->createForm(EntreeType::class, $e);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $e= $form->getData();
            $em = $doctrine->getManager();
            $em->persist($e);
            $em->flush();
             $this->addFlash(type:'success', message: "Une entrée a été effectué  avec succès");
            //mise à jour du produit
            $p=$em->getRepository(Produit::class)->find($e->getProduit()->getId());
            $stock=$p->getQtestock()+$e->getQtite();
            $p->setQteStock($stock);
            $em->flush();
            $this->addFlash(type:'success', message: "Votre stock de produit a été mis à jour");
         }
        return $this->redirectToRoute(route:'app_entree_liste');
    }

}
