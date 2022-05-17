<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Movie;
use App\Form\MovieTypeFormType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

class MoviesController extends AbstractController
{
    private $em;
    private $movieRepository;
    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository)
    {
        $this->em=$em;
        $this->movieRepository=$movieRepository;
    }
    #[Route('/movies', name: 'index',methods:['GET','HEAD'])]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $this->em->getRepository(Movie::class)->findAll();
        // $movies = $this->em->getRepository(Movie::class)->findBy([],['id'=>'DESC']);
        // $movies = $this->em->getRepository(Movie::class)->findOneBy(['id'=>1,'title'=>'Iron man'],
        // ['id'=>'DESC']);
        // $movies->count([]);
        // dd($movies);
      
    return $this->render('movies/index.html.twig',['movies'=>$movies]);
    }

    #[Route('/movie/{id}',name:'show',methods:['GET','HEAD'])]

    public function show($id) : Response
    {
        $movie=$this->em->getRepository(Movie::class)->find($id);
      
    
        return $this->render('movies/show.html.twig',['movie'=>$movie]);
    }

        
    #[Route('/movies/create',name:'create',methods:['GET','HEAD','POST','PUT'])]

    public function create(Request $request) : Response
    {
       $movie=new Movie();
       $form=$this->createForm(MovieTypeFormType::class,$movie);
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()){
           $newMovie=$form->getData();
          $imagePath=$form->get('imagePath')->getData();
          if($imagePath){
              $newFileName=uniqid(). '.'. $imagePath->guessExtension();
              try{
              $imagePath->move(
                  $this->getParameter('kernel.project_dir'). '/public/uploads',
                  $newFileName
              );
            }
            catch(FileException $e){
                return new Response($e->getMessage());

            }
         
            $newMovie->setImagePath('/uploads/'. $newFileName);
        
        }
        $this->em->persist($newMovie);
        $this->em->flush();
        return $this->redirectToRoute('index');

       }
    
        return $this->render('movies/create.html.twig',[
            'form'=>$form->createView()
        ]);

    }
    #[Route('/movies/edit/{id}',name:'edit_movie')]
    public function edit($id,Request $request) : Response
    
    {
        $movie=$this->em->getRepository(Movie::class)->find($id);
        $form=$this->createForm(MovieTypeFormType::class,$movie);
        $form->handleRequest($request);
        $imagePath=$form->get('imagePath')->getData();
        if($form->isSubmitted() && $form->isValid()){
            
            $movie->setTitle($form->get('title')->getData());
            $movie->setDescription($form->get('description')->getData());
            $movie->setRealeaseYear($form->get('realeaseYear')->getData());
     
            
            if($imagePath){
                //handle uploads
               
             
                if($movie->getImagePath()!==null){
                   
                  
                 
                    if(file_exists(
                        $this->getParameter('kernel.project_dir').'/public/'. $movie->getImagePath()
                      
                    )){
                      
                    
                     
                        $newFileName=uniqid(). '.'. $imagePath->guessExtension();
                        try{
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir'). '/public/uploads',
                                $newFileName
                            );
                          }
                          catch(FileException $e){
                              return new Response($e->getMessage());
              
                          }
                       
                          $movie->setImagePath('/uploads/'. $newFileName);
                       
                          $this->em->persist($movie);
                          $this->em->flush();
                    
                          return $this->redirectToRoute('index');
                        
                    }

                }
            }
            else{
            $movie->setTitle($form->get('title')->getData());
            $movie->setDescription($form->get('description')->getData());
            $movie->setRealeaseYear($form->get('realeaseYear')->getData());
         $this->em->persist($movie);
         $this->em->flush();
         return $this->redirectToRoute('index');
            }

        }
        return $this->render('movies/edit.html.twig',[
        'form'=>$form->createView(),
        'movie'=>$movie]);


    }
    #[Route('movies/delete/{id}', name:'delete', methods:['DELETE','GET'])]
    public function delete($id) : Response
    {
        $movie=$this->movieRepository->find($id);
     
      $this->em->remove($movie);
      $this->em->flush();
      return $this->redirectToRoute('index');

    }
    

}
