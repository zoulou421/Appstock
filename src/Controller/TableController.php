<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TableController extends AbstractController
{
    #[Route('/table', name: 'app_table')]
    public function index(): Response
    {
        return $this->render('table/index.html.twig');
    }
    
    #[Route('/table/users', name: 'table.users')]
    public function users(): Response
    {
        $users =[
            ['firstname'=>'Bonevy', 'name'=>'Beby', 'age'=>31],
            ['firstname'=>'Laurore', 'name'=>'Beby', 'age'=>30],
            ['firstname'=>'Gemmima', 'name'=>'Beby', 'age'=>2],
            ['firstname'=>'ALbetina', 'name'=>'Beby', 'age'=>27]
        ];
        return $this->render('table/users.html.twig',[    
             'users' => $users
        ]);
    }
}
