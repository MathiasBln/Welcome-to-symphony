<?php


namespace App\Controller;

use App\Entity\Category;
use App\Form\ProgramSearchType;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("category/")
 */
class CategoryController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("", name="categ_index")
     * @return Response A response instance
     */
    public function add(Request $request): Response
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $data = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->flush();
        }

        return $this->render('category/index.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}