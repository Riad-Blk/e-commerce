<?php

namespace App\Controller\Admin;

use App\Service\PdoManager;
use App\Form\CategoryProduitsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminCategoryProduitsController extends AbstractController
{
    /**
     * @Route("/admin/categories/produits", name="admin_category_produits")
     */
    public function index(Request $request, PdoManager $pdo): Response
    {

        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(CategoryProduitsType::class, null);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //dump($form->getData()['category']);
            //dump($form->getData()['produit']);

            $result = $pdo->select('
                SELECT * 
                FROM produits_category 
                WHERE produits_id = '.$form->getData()['produit']->getId().' 
                AND category_id = '.$form->getData()['category']->getId().'
           ');

            
            if (!$result) {
                $pdo->insert('produits_category', [
                    'produits_id' => $form->getData()['produit']->getId(),
                    'category_id' => $form->getData()['category']->getId(), 
                ]);
            } else {
                $pdo->update('produits_category', [
                    'produits_id' => $form->getData()['produit']->getId(),
                    'category_id' => $form->getData()['category']->getId(), 
                ], [
                    'produits_id' => $result[0]['produits_id'],
                    'category_id' => $result[0]['category_id'],
                ]);
                $this->addFlash('message', 'La catégorie et le produit ont été mis à jour avec succès');
            }

            return $this->redirectToRoute('admin_category_produits');

        }

        return $this->render('admin/category_produits/index.html.twig', [
            'controller_name' => 'AdminCategoryProduitsController',
            'form' => $form->createView(),
        ]);
    }
}
