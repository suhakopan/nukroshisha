<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetail;
use App\Form\OrdersType;
use App\Repository\AddressRepository;
use App\Repository\Admin\SettingRepository;
use App\Repository\OrdersDetailRepository;
use App\Repository\OrdersRepository;
use App\Repository\ShopcartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orders")
 */
class OrdersController extends AbstractController
{
    /**
     * @Route("/", name="orders_index", methods={"GET"})
     */
    public function index(OrdersRepository $ordersRepository,SettingRepository $settingRepository): Response
    {
        $setting = $settingRepository->findAll();
        $cats = $this->categorytree();
        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findAll(),
            'setting' => $setting,
            'cats' => $cats,
        ]);
    }

    /**
     * @Route("/new", name="orders_new", methods={"GET","POST"})
     */
    public function new(Request $request,ShopcartRepository $shopcartRepository,SettingRepository $settingRepository,AddressRepository $addressRepository): Response
    {
        $data = $settingRepository->findAll();
        $cats = $this->categorytree();
        $order = new Orders();
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        $user = $this->getUser()->getid();
        $total = $shopcartRepository->getUserShopTotal($user);
        $submittedToken = $request->request->get('token');
        if($this->isCsrfTokenValid('form-order',$submittedToken)){
                $entityManager = $this->getDoctrine()->getManager();

                $order->setUserID($user);
                $order->setTotal($total);
                $order->setUserID($user);
                $entityManager->persist($order);
                $entityManager->flush();

                $orderid = $order->getId();
                $shopcart = $shopcartRepository->getUserShopCart($user);
                foreach ($shopcart as $item){

                    $orderdetail = new OrdersDetail();
                    $orderdetail->setOrderID($orderid);
                    $orderdetail->setProductID($item["ProductID"]);
                    $orderdetail->setQuantity($item["Quantity"]);

                    $entityManager->persist($orderdetail);
                    $entityManager->flush();
                }
                $entityManager=$this->getDoctrine()->getManager();
                $query = $entityManager->createQuery('DELETE FROM App\Entity\Shopcart s WHERE s.UserID=:userid')->setParameter('userid',$user);
                $query->execute();
                return $this->redirectToRoute('orders_index');
            }

            return $this->render('orders/new.html.twig', [
                'setting' => $data,
                'cats' => $cats,
                'order' => $order,
                'total' => $total,
                'form' => $form->createView(),
                'adres' => $addressRepository->findBy(['UserID' => $user]),
            ]);


    }

    /**
     * @Route("/{id}", name="orders_show", methods={"GET"})
     */
    public function show(Orders $order,SettingRepository $settingRepository,OrdersRepository $ordersRepository, OrdersDetailRepository $detailRepository,AddressRepository $addressRepository): Response
    {
        $data = $settingRepository->findAll();
        $cats = $this->categorytree();
        $orderid = $order->getId();
        $user = $this->getUser();
        $id = $user->getid();
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT p.id,p.Title,p.SPrice,od.Quantity,(p.SPrice*od.Quantity) as toplam FROM Orders o,Orders_detail od,Product p WHERE o.id=od.order_id and od.product_id=p.id and od.order_id=:orderid";
        $statement = $em->getConnection()->prepare( $sql);
        $statement->bindValue('orderid',$orderid);
        $statement->execute();
        $orderdetail = $statement->fetchAll();
        $address = $addressRepository->findBy(['UserID' =>$id]);
        return $this->render('orders/show.html.twig', [
            'order' => $order,
            'orders' => $ordersRepository->findBy(['UserID'=>$id]),
            'orderdetail' => $orderdetail,
            'setting' => $data,
            'cats' => $cats,
            'adres' => $address,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="orders_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Orders $order): Response
    {
        $form = $this->createForm(OrdersType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orders_index', [
                'id' => $order->getId(),
            ]);
        }

        return $this->render('orders/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orders_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Orders $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('orders_index');
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
