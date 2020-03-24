<?php

namespace App\Controller\Admin;

use App\Entity\Admin\SubCategory;
use App\Form\Admin\SubCategoryType;
use App\Repository\Admin\SubCategoryRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sub/category")
 */
class SubCategoryController extends AbstractController
{
    /**
     * @Route("/", name="admin_sub_category_index", methods={"GET"})
     */
    public function index(SubCategoryRepository $subCategoryRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT S.id,S.title as baslik,S.description,S.keywords,C.title from sub_category S,category c WHERE s.category_id = c.id';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $em->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        return $this->render('admin/sub_category/index.html.twig', [
            'result' => $result,
            'count_users' => $result2,
        ]);
    }

    /**
     * @Route("/new", name="admin_sub_category_new", methods={"GET","POST"})
     */
    public function new(Request $request,\App\Repository\Admin\CategoryRepository $categoryRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $entityManager->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        $catList = $categoryRepository->findAll();
        $subCategory = new SubCategory();
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager->persist($subCategory);
            $entityManager->flush();
            $this->addFlash('success','Kayıt başarılı.');
            return $this->redirectToRoute('admin_sub_category_index');
        }

        return $this->render('admin/sub_category/new.html.twig', [
            'sub_category' => $subCategory,
            'form' => $form->createView(),
            'catlist' => $catList,
            'count_users' => $result2,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="admin_sub_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SubCategory $subCategory,\App\Repository\Admin\CategoryRepository $categoryRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $entityManager->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        $catList = $categoryRepository->findAll();
        $catname = $categoryRepository->findBy(
            ['id' => $subCategory->getCategoryID()]
        );
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Kayıt güncellendi.');
            return $this->redirectToRoute('admin_sub_category_index', [
                'id' => $subCategory->getId(),
            ]);
        }

        return $this->render('admin/sub_category/edit.html.twig', [
            'catlist' => $catList,
            'sub_category' => $subCategory,
            'count_users' => $result2,
            'catname' => $catname,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="admin_sub_category_delete", methods={"GET","POST"})
     */
    public function delete( SubCategory $subCategory): Response
    {

        //if ($this->isCsrfTokenValid('delete'.$subCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subCategory);
            $entityManager->flush();
        $this->addFlash('delete','Kayıt silindi.');
        //}

        return $this->redirectToRoute('admin_sub_category_index');
    }
}
