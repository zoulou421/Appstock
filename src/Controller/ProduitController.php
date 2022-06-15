<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/produit'),isGranted('ROLE_USER')]
class ProduitController extends AbstractController
{
    /*#[Route('/liste', name: 'app_produit_liste')]
    public function index(): Response
    {
        $em= $this->getDoctrine()->getManager();
        $data['produits']=$em->getRepository(Produit::class)->findAll();
        return $this->render('produit/liste.html.twig',$data);
    }*/
  #[Route('/liste',name:'app_produit_liste')]
    public function index(ManagerRegistry $doctrine): Response
    {
      // $repository =$doctrine->getRepository(persistentObject: Produit::class);
       //$p =new Produit();//instanciation de la classe
       //$form = $this->createForm(Produit::class, $p);
       
       //$produits=$repository->findAll();
      // return $this->render('produit/liste.html.twig', [
      //      'produits' => $produits,
      //  ]);
        //$em=$this->getDoctrine()->getManager(); 
       $em = $doctrine->getManager();
       $p=new Produit();
       $form= $this->createForm(ProduitType::class, $p,
       array('action'=> $this->generateUrl(route:'app_produit_add'))
       );
       $data['form']= $form->createView();
       $data['produits']= $em->getRepository(Produit::class)->findAll();
       return $this->render('produit/liste.html.twig', $data);
    }

    #[Route('/get/{id}', name: 'app_produit_get')]
    public function getProduit(): Response
    {
        return $this->render('produit/liste.html.twig');
    }


    #[Route('/add', name: 'app_produit_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $p=new Produit();
        $form = $this->createForm(ProduitType::class, $p);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $p= $form->getData();
            $p->setUser($p->getUser());
            $em = $doctrine->getManager();
            $em->persist($p);
            $em->flush();
            $this->addFlash(type:'success', message: "Produit ajoutée avec succès");
        }
        return $this->redirectToRoute(route:'app_produit_liste');
    }


   #[Route('/delete2/{id}',name:'app_produit_delete2')]
    public function deleteProduit2(Produit $produit=null, ManagerRegistry $doctrine):RedirectResponse{
         if($produit){
            $manager = $doctrine->getManager();
            $manager->remove($produit);
            $manager->flush();
            $this->addFlash(type:'success', message: "Produit supprimée avec succès");
         }else{
             $this->addFlash(type:'error', message:"Produit inexistante!");
         }
         return $this->redirectToRoute(route:'app_produit_liste');
    }
    
    #[Route('/delete/{id}',name:'app_produit_delete')]
    public function deleteProduit($id, ManagerRegistry $doctrine):RedirectResponse{

            $em = $doctrine->getManager();
            $produit =$em->getRepository(Produit::class)->find($id);
            if($produit!=null){
               $em->remove($produit);
               $em->flush();
               $this->addFlash(type:'success', message: "Produit supprimée avec succès");
            }else{
             $this->addFlash(type:'error', message:"Produit inexistant!");
            }
         return $this->redirectToRoute(route:'app_produit_liste');
    }



    #[Route('/edit/{id}',name:'app_produit_edit')]
    public function edit($id, ManagerRegistry $doctrine){

            $em = $doctrine->getManager();
            $p =$em->getRepository(Produit::class)->find($id);

            $form= $this->createForm(ProduitType::class, $p,
            array('action'=> $this->generateUrl(route:'app_produit_add'))
            );
            $data['form']= $form->createView();
            $data['produits']= $em->getRepository(Produit::class)->findAll();
         return $this->render('produit/liste.html.twig',$data);

     }


     #[Route('/update/{id}',name:'app_produit_update')]
    public function update($id,  ManagerRegistry $doctrine, Request $request){

        $p=new Produit();
        $form = $this->createForm(ProduitType::class, $p);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $p= $form->getData();
            $p->setUser($p->getUser());
            $em = $doctrine->getManager();
            $produit = $em->getRepository(Produit::class)->find($p->getId());
            
            $produit->setLibelle($p->getLibelle());
            $produit->setQtestock($p->getQtestock());
            
            $em->flush();
            $this->addFlash(type:'success', message: "Produit mis à jour avec succès");
        }
        return $this->render('produit/add.html.twig', [
            'form'=>$form->createView()
        ]);

     }


    #[Route('/edit/{id?0}', name: 'app_produit_edit2')]
    public function editProduit(Produit $produit=null, ManagerRegistry $doctrine, Request $request): Response
    {
      $this->denyAccessUnlessGranted("ROLE_ADMIN");//is the as isGranted at url
      $entityManager = $doctrine->getManager();
           $new =false;
        if(!$produit){
           $new =true;
           $produit = new Produit(); 
        }
        $form = $this->createForm(PersonneType::class, $produit);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            if($new){
               $message= "La produit a été ajoutée avec succès";  
               $produit->setCreatedBy($this->getUser());
            } else {
              $message= "Le produit a été mis à jour avec succès"; 
            }
           $entityManager->persist($produit);
           $entityManager->flush();
           
           $this->addFlash(type:'success',message:$produit->getName().$message);
           
           return $this->redirectToRoute('app_produit_liste');
        }else 
        {
        return $this->render('produit/add.html.twig', [
            'form'=>$form->createView()
        ]);
        }
        
        
    }
    
}
