<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Query\AST\QuantifiedExpression;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PublicController extends AbstractController

{
    /**
     * @Route("/index.html.twig", name="index")
     */
    public function index(ProduitRepository $repo): Response
    {
        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
            'produits' => $repo->findAll()
        ]);
    }

    /** 
    * @Route("/Products", name="products_list")
    */
    public function products_list(ProduitRepository $repo)
    {
        return $this->render('public/products_list.html.twig', [
            'produits' => $repo->findAll()
        ]);
    }

    /**
    * @Route("/Products/{id}", name="product_fiche")
    */
    public function product_fiche($id, ProduitRepository $repo)
    {
        return $this->render('public/product_fiche.html.twig',[
            'produit'=>$repo->find($id)
        ]);
    }

    /**
     * @Route("/Contact", name="contact")
     */
    public function contact(Request $request, EntityManagerInterface $manager)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreated(new \DateTime());

            $contact->setContactDate(new \DateTime());


            $manager->persist($contact);
            $manager->flush();

            return $this->redirectToRoute('index');
        }
        return $this->render('public/Contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/Panier", name="panier")
    */
    public function panier(SessionInterface $session,ProduitRepository $produitRepository)
    {
        $panier = $session->get('panier', []);
         $global = [];

        foreach($panier as $id => $quantity) {
            $global[] = [
                'produit' => $produitRepository->find($id),
                'quantity' => $quantity
            ];
        }

        return $this->render('public/panier.html.twig', [
            'items' => $global
        
            ]);
    }

    /**
    * @Route("/Panier/ajouter/{id}", name="ajouterpanier")
    */
    public function ajouterpanier($id, SessionInterface $session)
    {
        
        $panier = $session->get('panier', []);
        $panier[$id] = 1;
        $session->set('panier', $panier);

        return $this->redirectToRoute("panier");
            
    }

    /**
    * @Route("/Panier/effacer/{id}", name="effacerpanier")
    */
    public function effacerpanier($id, SessionInterface $session)
    {
        
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("panier");
            
    }

}
