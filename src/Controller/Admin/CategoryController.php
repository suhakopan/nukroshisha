<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Category;
use App\Entity\Users;
use App\Form\Admin\CategoryType;
use App\Repository\Admin\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="admin_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT count(*) as count FROM User';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'count_users' => $result,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT count(*) as count FROM User';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Kayıt güncellendi.');
            return $this->redirectToRoute('admin_category_index', [
                'id' => $category->getId(),

            ]);
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'count_users' => $result,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_category_delete", methods={"GET|POST"})
     */
    public function delete($id,CategoryRepository $categoryRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $sql = "Update Category set Status=0 where id=:id";
        $statement = $em->getConnection()->prepare( $sql);
        $statement->bindValue('id',$id);
        $statement->execute();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $em->getConnection()->prepare($sql2);
        $statement2->execute();
        $result = $statement2->fetchAll();
        $this->addFlash('delete','Kayıt pasif hale getirildi.');
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'count_users' => $result,
            ]);
    }

    /**
     * @Route("/admin/category/new", name="admin_category_new", methods="GET|POST")
     */
    public function new(Request $request):Response
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT count(*) as count FROM User';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $kategori = new Category();
        $form =$this->createForm(CategoryType::class, $kategori);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($kategori);
            $em->flush();
            $this->addFlash('success','Kayıt başarılı bir şekilde tamamlandı.');
            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render('admin/category/new.html.twig',
            [
                'form' => $form->createView(),
                'count_users' => $result,
            ]);
    }
}
