<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      $movie=new Movie();
      $movie->setTitle('The Dark Knight');
      $movie->setImagePath('https://cdn.pixabay.com/photo/2021/05/22/04/27/batman-6272544_960_720.jpg');
      $movie->setRealeaseYear(2008);
      $movie->setDescription('great movie');
      //Add Data to Pivot Table
      $movie->addActor($this->getReference('actor_1'));
      $movie->addActor($this->getReference('actor_2'));
      $manager->persist($movie);
      $movie2=new Movie();
      $movie2->setTitle('Iron man');
      $movie2->setImagePath('https://cdn.pixabay.com/photo/2021/07/20/14/59/iron-man-6480952_960_720.jpg');
      $movie2->setRealeaseYear(2009);
      $movie2->setDescription('great movie');
       //Add Data to Pivot Table
      $movie2->addActor($this->getReference('actor_3'));
      $movie2->addActor($this->getReference('actor_4'));
      $manager->persist($movie2);
      $manager->flush();
    
    }
}
