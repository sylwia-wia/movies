<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('The Dark Night');
        $movie->setReleaseYear(2008);
        $movie->setDescription('To jets opis ciemnej nocy');
        $movie->setImagePath('https://cdn.pixabay.com/photo/2020/07/02/19/36/marvel-5364165_960_720.jpg');

        //Add Data to Pivot table
        $movie->addActor($this->getReference('actor_1'));
        $movie->addActor($this->getReference('actor_2'));

        $manager->persist($movie);

        $movie = new Movie();
        $movie->setTitle('Batman');
        $movie->setReleaseYear(2000);
        $movie->setDescription('To jest opis filmu Batman');
        $movie->setImagePath('https://cdn.pixabay.com/photo/2021/06/18/11/22/batman-6345897_960_720.jpg');

        //Add Data to Pivot table
        $movie->addActor($this->getReference('actor_3'));
        $movie->addActor($this->getReference('actor_4'));
        $manager->persist($movie);

        $manager->flush();

    }
}
