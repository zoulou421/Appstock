<?php

namespace App\Controller;
use App\Service\UploaderService;
use App\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Form\PersonneType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use \Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\MailerService;
use App\Service\PdfService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use \App\Event\AddPersonneEvent;
use App\Event\ListAllPersonneEvent;

#[Route('/personne'),isGranted('ROLE_USER')]
class PersonneController extends AbstractController
{
    public function __construct(private EventDispatcherInterface $dispatcher) {
        
    }
    #[Route('/',name:'personne.list')]
    public function index(ManagerRegistry $doctrine): Response
    {
       //helpers=new \App\Service\Helpers();
       //($helpers->sayCc());
       
       $repository =$doctrine->getRepository(persistentObject: Personne::class);
       $personnes=$repository->findAll();
       return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }
    
    #[Route('/alls/{age}/{ageMin}/{ageMax}',name:'personne.list.age')]
    public function personneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository =$doctrine->getRepository(persistentObject: Personne::class);
        $personnes=$repository->findPersonneByInterval($ageMin, $ageMax);
        
        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
        ]);
    }
    
    #[Route('/stats/{age}/{ageMin}/{ageMax}',name:'personne.list.age.stats')]
    public function StatsPersonneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository =$doctrine->getRepository(persistentObject: Personne::class);
        $stats=$repository->statsPersonneByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/stats.html.twig', [
            'stats' => $stats[0],
            'ageMin' => $ageMin,
            'ageMax' => $ageMax
        ]);
    }   
    
    #[
        Route('/alls/{page?1}/{nbre?12}',name:'personne.list.alls'),
       isGranted("ROLE_USER")
    ]
    public function indexAlls(ManagerRegistry $doctrine, $page, $nbre): Response
    {
       $repository =$doctrine->getRepository(persistentObject: Personne::class);
       $nbrePersonne = $repository->count([]);
       //$nbrePage= ($nbrePersonne % $nbre)==0 ? $nbrePersonne /$nbre : ceil(num: $nbrePersonne /$nbre); ou bien
       $nbrePage= ceil(num: $nbrePersonne /$nbre);

       $personnes=$repository->findBy([], [], limit: $nbre, offset: ($page-1)*$nbre);//findBy([])=findAll()
       
       $listAllPersonneEvent = new ListAllPersonneEvent(count($personnes));
       $this->dispatcher->dispatch($listAllPersonneEvent, ListAllPersonneEvent::LIST_ALL_PERSONNE_EVENT);
       return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
           'isPaginated'=>true,
           'nbrePage'=> $nbrePage,
           'page'=> $page
        ]);
    }
    
     
   /* #[Route('/add', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $perso= new Personne();
        
        $perso->setFirstname(firstname:"Laurore");
        $perso->setName(name:"BEBY");
        $perso->setAge(age:"31");
        
        $entityManager->persist($perso);
        $entityManager->flush();
        return $this->render('personne/index.html.twig', [
            'perso' => $perso,
        ]);
    }*/
    #[Route('/add', name: 'personne.add')]
    public function addPersonne(ManagerRegistry $doctrine, Request $request): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");//is the as isGranted at url
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updateAt');
        
        $form->handleRequest($request);
        if($form->isSubmitted()){
           $entityManager->persist($personne);
           $entityManager->flush();
           $this->addFlash(type:'success', message: "Personne ajoutée avec succès");
           return $this->redirectToRoute('personne.list');
        } 
        else 
        {
        return $this->render('personne/add-personne.html.twig', [
            'formpersonne'=>$form->createView()
        ]);
        }
    }
    
    #[Route('/edit/{id?0}', name: 'personne.edit')]
    public function editPersonne(Personne $personne=null, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger,
          UploaderService $uploaderService, MailerService $mailer ): Response
    {
      $this->denyAccessUnlessGranted("ROLE_ADMIN");//is the as isGranted at url
      $entityManager = $doctrine->getManager();
           $new =false;
        if(!$personne){
           $new =true;
           $personne = new Personne(); 
        }
        $form = $this->createForm(PersonneType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updateAt');
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //DEBUT AJOUT PORTION POUR IMAGE
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('photo')->getData();//you can rename $brochureFile to $photo

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {

                $directory=$this->getParameter('personnes_directory');
                $personne->setImage($uploaderService->uploadFile($brochureFile, $directory));
            }

            //FIN AJOUT PORTION POUR IMAGE
            if($new){
               $message= "La personne a été ajoutée avec succès";  
               $personne->setCreatedBy($this->getUser());
            } else {
              $message= "La personne a été mis à jour avec succès"; 
            }
           $entityManager->persist($personne);
           $entityManager->flush();
           
           if($new){
             //on a crée un evenement
             $addPersonneEvent = new AddPersonneEvent($personne);  
             //on va le dispatcher
             $this->dispatcher->dispatch($addPersonneEvent, AddPersonneEvent::ADD_PERSONNE_EVENT);
           }
           $this->addFlash(type:'success',message:$personne->getName().$message);
           /*contenu suivant a elevé enlevé ici:
            * $mailMessage=$personne->getFirstname().' '.$personne->getName().' '.$message;
           $this->addFlash(type:'success',message:$personne->getName().$message);
           $mailer->sendEmail(content: $mailMessage);
            */
           return $this->redirectToRoute('personne.list');
        }else 
        {
        return $this->render('personne/add-personne.html.twig', [
            'formpersonne'=>$form->createView()
        ]);
        }
        
        
    }
    
    #[Route('/{id<\d+>}', name:'personne.detail')]
   /* public function detail(ManagerRegistry $doctrine, $id): Response
    {
       $repository =$doctrine->getRepository(persistentObject: Personne::class);
       $personne=$repository->find($id);
       
       if(!$personne){
           $this->addFlash(type:'error', message: "la personne d'id $id n'existe pas");
           return $this->redirectToRoute(route:"personne.list");
          
       }
       return $this->render('personne/detail.html.twig', [ 'personne'=>$personne]);
    } UNE AUTRE FACON DE GERER LA METHODE find($id). cela s'appelle du PARAM CONVERTER:convertisseur de paramètre*/
     public function detail(Personne $personne=null): Response
    {
       if(!$personne){
           $this->addFlash(type:'error', message: "la personne n'existe pas");
           return $this->redirectToRoute(route:"personne.list");
          
       }
       return $this->render('personne/detail.html.twig', [ 
           'personne'=>$personne,
            'photo'=> $personne->getImage()
               ]);
    }
    
    #[Route('/delete/{id}',name:'personne.delete')]
    public function deletePersonne(Personne $personne=null, ManagerRegistry $doctrine):RedirectResponse{
         if($personne){
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();
            $this->addFlash(type:'success', message: "Personne supprimée avec succès");
         }else{
             $this->addFlash(type:'error', message:"Personne inexistante!");
         }
         return $this->redirectToRoute(route:'personne.list.alls');
    }
    
#[Route('/update/{id}/{name}/{firstname}/{age}',name:'personne.update')]
    public function updatePersonne(Personne $personne=null, $name,$firstname,$age,ManagerRegistry $doctrine):RedirectResponse{
         if($personne){
            $manager = $doctrine->getManager();
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);
            $manager->persist($personne);
            $manager->flush();
            $this->addFlash(type:'success', message: "Personne modifiée avec succès");
         }else{
             $this->addFlash(type:'error', message:"Personne inexistante!");
         }
         return $this->redirectToRoute(route:'personne.list.alls');
    }
    
    #[Route('/pdf/{id}', name: 'personne.pdf')]
     public function generatePdfPersonne(Personne $personne=null, PdfService $pdf):Response{
        $html= $this->render('personne/detail.html.twig', [
            'personne'=> $personne
        ]);
        $pdf->showPdfFile($html);
    }
}
