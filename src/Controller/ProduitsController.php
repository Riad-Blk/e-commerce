<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchForm;

use App\Repository\ProduitsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/produits", name="produits_index")
     */
    public function index(ProduitsRepository $Repository, Request $request): Response
    {   
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class,$data);
        $form->handleRequest($request);
        
        $produits =$Repository->findSearch($data);
            return $this->render('produits/index.html.twig', [
            'produits' =>  $produits,
            'controller_name' => 'ProduitsController',
            'form'=> $form->createView()
        ]);
    }

}
