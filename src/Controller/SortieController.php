<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;

#[Route('/Sortie'),isGranted('ROLE_USER')]
class SortieController extends AbstractController
{
    #[Route('/liste', name: 'app_sortie_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
        //$em=$this->getDoctrine()->getManager(); 
       $em = $doctrine->getManager();
       $s=new Sortie();
       $form= $this->createForm(SortieType::class, $s,
       array('action'=> $this->generateUrl(route:'app_sortie_add'))
       );
       $data['formulaire']= $form->createView();
       $data['sorties']= $em->getRepository(Sortie::class)->findAll();
         
        return $this->render('sortie/liste.html.twig',$data);
    }
    
     #[Route('/add', name: 'app_sortie_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $s=new Sortie();
        $form = $this->createForm(SortieType::class, $s);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $em = $doctrine->getManager();
            $s= $form->getData();
            $p=$em->getRepository(Produit::class)->find($s->getProduit()->getId());
            if($p->getQtestock() < $s->getQte()) {
              $this->addFlash(type:'error', message: "Votre stock est inférieur à la quantité de sortie");
            }else{
                $em->persist($s);
                $em->flush();
                 $this->addFlash(type:'success', message: "Une vente a été réalisé  avec succès");
                 //mise à jour du produit
                 $stock=$p->getQtestock()-$s->getQte();
                 $p->setQteStock($stock);
                 $em->flush();
                 $this->addFlash(type:'success', message: "Votre stock de produit a été mis à jour");
            }
         }
        return $this->redirectToRoute(route:'app_sortie_liste');
    }

}
