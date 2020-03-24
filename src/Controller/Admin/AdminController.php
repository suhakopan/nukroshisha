<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT count(*) as count FROM User';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'count_users' => $result,
        ]);
    }

    /**
     * @Route("/admin/orders/{slug}", name="admin_orders_index")
     */
    public function orders($slug, OrdersRepository $ordersRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT count(*) as count FROM User';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $orders=$ordersRepository->findBy(['Status' => $slug]);

        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders,
            'count_users' => $result,
        ]);
    }

    /**
     * @Route("/admin/orders/show/{id}", name="admin_orders_show", methods="GET")
     */
    public function show($id,Orders $orders,OrdersRepository $ordersRepository):Response
    {
        $em = $this->getDoctrine()->getManager();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $em->getConnection()->prepare($sql2);
        $statement2->execute();
        $result = $statement2->fetchAll();
        $sql = "SELECT p.id,p.Title,p.SPrice,od.Quantity,(p.SPrice*od.Quantity) as toplam FROM Orders o,Orders_detail od,Product p WHERE o.id=od.order_id and od.product_id=p.id and od.order_id=:orderid";
        $statement = $em->getConnection()->prepare( $sql);
        $statement->bindValue('orderid',$id);
        $statement->execute();
        $orderdetail = $statement->fetchAll();

        return $this->render('admin/orders/show.html.twig', [
            'count_users' => $result,
            'order' => $orders,
            'orderdetail' => $orderdetail,
        ]);
    }

    /**
     * @Route("/admin/orders/{id}/update", name="admin_orders_update", methods="POST")
     */
    public function order_update($id,Orders $orders,Request $request):Response
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT count(*) as count FROM User';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();

        $shipInfo = $request->get("shipinfo");
        $status = $request->get("status");
        $em = $this->getDoctrine()->getManager();
        $sql2 = "UPDATE orders SET Ship_info=:shipinfo,Status=:status WHERE id=:id";
        $statement2 = $em->getConnection()->prepare($sql2);
        $statement2->bindValue('shipinfo',$shipInfo);
        $statement2->bindValue('status',$status);
        $statement2->bindValue('id',$id);
        $statement2->execute();
        $this->addFlash('success','Sipariş Bilgileri Güncellenmiştir');

        return $this->redirectToRoute('admin_orders_show',array('id' => $id));

        return $this->render('admin/orders/show.html.twig', [
            'count_users' => $result,
        ]);
    }

}
