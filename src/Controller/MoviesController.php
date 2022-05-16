<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies', name: 'app_movies',methods:['GET','HEAD'])]
    public function index(): Response
    {
        $movies = [
            'Avengers: Endgame',
            'Inception',
            'Loki',
            'Black Widow'
        ];
    return $this->render('index.html.twig',['movies'=>$movies]);
    }

}
