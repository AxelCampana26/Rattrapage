<?php

namespace App\DataFixtures;
use App\Entity\Contact;
use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new Produit();
        $product->setName("NOm du produit")
         ->setPicture("https://i.pinimg.com/originals/ed/bf/ad/edbfad6bf79e808a880762bcb068f634.jpg")
         ->setDescription("description du produit")
         ->setPromo(true)
         ->setCreated(new \DateTime());

        $Contact = new Contact();
        $Contact -> setEmail("vv")
         ->setSubject("")
         ->setmessage("")
         ->setContactDate(new \DateTime())
         ->setCreated(new \DateTime());

         $manager->persist($product);
         $manager->persist($Contact);

         $manager->flush();
    }
}
