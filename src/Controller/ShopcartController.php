<?php

namespace App\Controller;

use App\Entity\Shopcart;
use App\Form\ShopcartType;
use App\Repository\Admin\SettingRepository;
use App\Repository\Admin\ImageRepository;
use App\Repository\ShopcartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/shopcart")
 */
class ShopcartController extends AbstractController
{
    /**
     * @Route("/", name="shopcart_index", methods={"GET"})
     */
    public function index(ShopcartRepository $shopcartRepository,SettingRepository $settingRepository,ImageRepository $imageRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        //$sql = "SELECT S.id as sid,S.user_id,P.id,P.title,P.sprice,I.image,sum(S.quantity) as quantity FROM product P,image I,shopcart S WHERE s.product_id=p.id and I.product_id=p.id and S.user_id= :userid GROUP BY p.id";
        $sql = "SELECT distinct S.id as sid,S.user_id,S.product_id,p.title,p.sprice,sum(s.quantity) as quantity FROM Shopcart as S, Product as P where s.product_id=p.id and user_id=:userid group by p.id";
        $statement = $em->getConnection()->prepare( $sql);
        $statement->bindValue('userid',$user->getid());
        $statement->execute();

        $sql2 = "SELECT s.product_id,i.image from shopcart as s, image as i where s.product_id= i.product_id and user_id=:user group by s.product_id";
        $statement2 = $em->getConnection()->prepare( $sql2);
        $statement2->bindValue('user',$user->getid());
        $statement2->execute();

        $shopcarts = $statement->fetchAll();
        $image = $statement2->fetchAll();

        $cats = $this->categorytree();
        $data = $settingRepository->findAll();
        return $this->render('shopcart/index.html.twig', [
            'shopcarts' => $shopcarts,
            'setting' => $data,
            'images' => $image,
            'cats' => $cats,
        ]);
    }

    /**
     * @Route("/new", name="shopcart_new", methods={"GET","POST"})
     */
    public function new(Request $request,SettingRepository $settingRepository): Response
    {
        $user=$this->getUser()->getid();
        $shopcart = new Shopcart();
        $form = $this->createForm(ShopcartType::class, $shopcart);
        $form->handleRequest($request);
        $shopcart->setUserID($user);
        $submittedToken = $request->request->get('token');
        $cats = $this->categorytree();
        $data = $settingRepository->findAll();
        if($this->isCsrfTokenValid('add-item', $submittedToken)) {
            if ($form->isSubmitted()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($shopcart);
                $entityManager->flush();

                return $this->redirectToRoute('shopcart_index');
            }
        }

        return $this->render('shopcart/new.html.twig', [
            'shopcart' => $shopcart,
            'form' => $form->createView(),
            'setting' => $data,
            'cats' => $cats,
        ]);
    }

    /**
     * @Route("/{id}", name="shopcart_show", methods={"GET"})
     */
    public function show(Shopcart $shopcart,ImageRepository $imageRepository,$id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT image from Image WHERE product_id = :pid group by product_id';
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('pid',$id);
        $statement->execute();
        $image = $statement->fetchAll();
        return $image;
    }

    /**
     * @Route("/{id}/edit", name="shopcart_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Shopcart $shopcart): Response
    {
        $form = $this->createForm(ShopcartType::class, $shopcart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('shopcart_index', [
                'id' => $shopcart->getId(),
            ]);
        }

        return $this->render('shopcart/edit.html.twig', [
            'shopcart' => $shopcart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="shopcart_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Shopcart $shopcart): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($shopcart);
        $entityManager->flush();
        $this->addFlash('success','Ürün sepetinizden silinmiştir.');
        return $this->redirectToRoute('shopcart_index');
    }

    public function categorytree($parent = 1, $user_tree_array = '')
    {
        if (!is_array($user_tree_array))
            $user_tree_array = array();

        $em = $this->getDoctrine()->getManager();
        $sql1 = "SELECT * FROM category where Status=1";
        $st1 = $em->getConnection()->prepare($sql1);
        $st1->execute();
        $rs1 = $st1->fetchAll();

        if(count($rs1)>0){
            foreach ($rs1 as $row2)
            {
                $user_tree_array[] = "<li class=\"has-dropdown\"> <a href='#'>".$row2['title']."</a>";
                $sql = "SELECT * FROM  sub_category WHERE category_id=".$row2['id'];
                $statement = $em->getConnection()->prepare( $sql);
                $statement->execute();
                $result = $statement->fetchAll();
                if(count($result) > 0 )
                {
                    $user_tree_array[] = "<ul class=\"dropdown\">";
                    foreach ($result as $row){
                        $user_tree_array[] = "<li> <a href='/category/".$row['id']."'>".$row['title']."</a></li>";
                    }
                    $user_tree_array[] ="</ul>";
                }
                $user_tree_array[] ="</li>";
            }
        }
        return $user_tree_array;
    }
}
