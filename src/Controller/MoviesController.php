<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Movie;

class MoviesController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
    }
    #[Route('/movies', name: 'app_movies',methods:['GET','HEAD'])]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $this->em->getRepository(Movie::class)->findAll();
        // $movies = $this->em->getRepository(Movie::class)->findBy([],['id'=>'DESC']);
        // $movies = $this->em->getRepository(Movie::class)->findOneBy(['id'=>1,'title'=>'Iron man'],
        // ['id'=>'DESC']);
        // $movies->count([]);
        // dd($movies);
    return $this->render('index.html.twig',['movies'=>$movies]);
    }

}
