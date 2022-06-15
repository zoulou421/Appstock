<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/todo')]
class TodoController extends AbstractController
{
    
   #[Route('/', name: 'todo')]
    public function index(Request $request): Response
    {
       $session = $request->getSession();
       
       if(!$session->has(name:'todos')){
           $todos=[
               'achat' => 'Acheter une clé',
               'cours'=> 'finaliser mon cours',
               'correction'=> 'corriger mes examens'
           ];
           
           $session->set('todos', $todos);
       }
       
       //Afficher du tableau de todo
        return $this->render('todo/index.html.twig');
        $this->addFlash(type:'info', message:"Liste des todos vient d'être réinitlialisée");
    }
    
    
    #[Route('/add/{name?test}/{content?test}', name: 'todo.add')]
    public function addTodo(Request $request, $name, $content):RedirectResponse
    {
       $session = $request->getSession();
       if($session->has('todos')){
          $todos=$session->get('todos');
           if(isset($todos[$name])){
               $this->addFlash(type:'error', message:"Le todo d'id $name existe déjà dans la liste");
               //return $this->redirectToRoute('todo');
           }else{
               $todos[$name] = $content;
               $session->set('todos', $todos);
               $this->addFlash(type:'success', message:"le todo d'id $name a été ajouté avec succès-");
           }
       }else{
           $this->addFlash(type:'error', message:"Liste des todos n'est pas encore réinitlialisée");
       }
       
       return $this->redirectToRoute('todo');
    }
    
    
    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content):RedirectResponse
    {
       $session = $request->getSession();
       if($session->has('todos')){
          $todos=$session->get('todos');
           if(!isset($todos[$name])){
               $this->addFlash(type:'error', message:"Le todo d'id $name n'existe pas dans la liste");
               //return $this->redirectToRoute('todo');
           }else{
               $todos[$name] = $content;
               $session->set('todos', $todos);
               $this->addFlash(type:'success', message:"le todo d'id $name a été modifié avec succès-");
           }
       }else{
           $this->addFlash(type:'error', message:"Liste des todos n'est pas encore réinitlialisée");
       }
       
       return $this->redirectToRoute('todo');
    }
    
    
    #[Route('/delete/{name}/', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name):RedirectResponse
    {
       $session = $request->getSession();
       if($session->has('todos')){
          $todos=$session->get('todos');
           if(!isset($todos[$name])){
               $this->addFlash(type:'error', message:"Le todo d'id $name n'existe pas dans la liste");
               //return $this->redirectToRoute('todo');
           }else{
               unset($todos[$name]);
               $session->set('todos', $todos);
               $this->addFlash(type:'success', message:"le todo d'id $name a été supprimé avec succès-");
           }
       }else{
           $this->addFlash(type:'error', message:"Liste des todos n'est pas encore réinitlialisée");
       }
       
       return $this->redirectToRoute('todo');
    }
    
    
    #[Route('/reset', name: 'todo.reset')]
    public function resetTodo(Request $request):RedirectResponse
    {
       $session = $request->getSession();
       $session->remove(name:'todos');
       return $this->redirectToRoute('todo');
    }
}
