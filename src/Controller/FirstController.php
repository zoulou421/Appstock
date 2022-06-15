<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FirstController extends AbstractController
{
   /** #[Route('/first', name: 'first')]
    public function index(): Response
    {
        return $this->render('first/index', [
            'name' => 'Beby',
            'firstname' => 'Verone'
            
        ]);
    }**/
    
    
   // #[Route('/sayHello/{name}/{firstname}', name: 'say.hello')]
    public function sayHello(Request $request, $name, $firstname): Response
    {
        return $this->render('first/index.html.twig', [
            'name' => $name,
            'firstname' => $firstname,
            'path' => 'tim.jpg',
            
        ]);
    }
    
    #[Route('/multi/{entier1}/{entier2}', 
            name: 'multiplication', 
            requirements:[
                  'entier1' =>'\d+',
                  'entier2' =>'\d+'
                         ])]
    public function multiplication($entier1, $entier2): Response
    {
        $result= $entier1*$entier2;
        return new Response(content:"<h1>$result</h1>");
    }
    
    #[Route('/template', name: 'template')]
    public function template(): Response
    {
        return $this->render('template.html.twig');
    }
    
}
