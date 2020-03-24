<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Product;
use App\Form\Admin\ProductType;
use App\Repository\Admin\CategoryRepository;
use App\Repository\Admin\ProductRepository;
use App\Repository\Admin\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="admin_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {


        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT P.id,P.title,P.keywords,S.title as baslik,P.description,P.amount,P.pprice,P.sprice,P.detail,i.image,P.status from product P,sub_category S,image i WHERE P.type = S.id and p.id=i.product_id GROUP BY P.id ORDER BY P.id';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $em->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        return $this->render('admin/product/index.html.twig', [
            'result' => $result,
            'count_users' => $result2,
        ]);
    }

    /**
     * @Route("/new", name="admin_product_new", methods={"GET","POST"})
     */
    public function new(Request $request,SubCategoryRepository $categoryRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $entityManager->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        $catlist = $categoryRepository->findAll();
        $catname = $categoryRepository->findBy(
            ['id' => 0]
        );
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success','Ürün başarıyla kaydedildi.');
            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/product/new.html.twig', [
            'catname' => $catname,
            'catlist' => $catlist,
            'product' => $product,
            'form' => $form->createView(),
            'count_users' => $result2,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $entityManager->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
            'count_users' => $result2,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product,SubCategoryRepository $categoryRepository): Response
    {
        $catlist = $categoryRepository->findAll();
        $catname = $categoryRepository->findBy(
            ['id' => $product->getType()]
        );
        $entityManager = $this->getDoctrine()->getManager();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $entityManager->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Kayıt güncellendi.');
            return $this->redirectToRoute('admin_product_index', [
                'id' => $product->getId(),

            ]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'count_users' => $result2,
            'catname' => $catname,
            'catlist' => $catlist,
        ]);
    }

    /**
     * @Route("/{id}/iedit", name="admin_product_iedit", methods={"GET","POST"})
     */
    public function iedit(Request $request, Product $product): Response
    {

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_product_index', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('admin/product/image_edit.html.twig', [
            'product' => $product,
            'id' => $product->getId(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/iupdate", name="admin_product_iupdate", methods={"GET","POST"})
     */
    public function iupdate(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($request->files->get('imagename')) {
            $file = $request->files->get('imagename');
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'), $fileName
                );
            } catch (FileException $e) {

            }
            $img1 = $product->getImage1();
            $product->setImage1($fileName);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin_product_iedit', [
            'id' => $product->getId(),
        ]);
    }

    /**
     * @Route("/{id}/idel", name="admin_product_idel", methods={"GET"})
     */
    public function del(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $product->setImage1(null);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_product_iedit');
        }
        return $this->render('admin/product/image_edit.html.twig', [
            'product' => $product,
            'id' => $product->getId(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
            return $this->redirectToRoute('admin_product_iedit', [
                'id' => $product->getId(),
            ]);
        }
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
