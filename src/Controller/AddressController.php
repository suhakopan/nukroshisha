<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Repository\Admin\SettingRepository;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/address")
 */
class AddressController extends AbstractController
{
    /**
     * @Route("/", name="address_index", methods={"GET"})
     */
    public function index(AddressRepository $addressRepository): Response
    {
        return $this->render('address/index.html.twig', [
            'addresses' => $addressRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="address_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('address_index');
        }

        return $this->render('address/new.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_show", methods={"GET"})
     */
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="address_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Address $address,$id,SettingRepository $settingRepository,AddressRepository $addressRepository,OrdersRepository $ordersRepository): Response
    {
        $user = $this->getUser();
        $id = $user->getid();
        $adres = $addressRepository->findBy(['UserID' =>$id]);
        $data = $settingRepository->findAll();
        $cats = $this->categorytree();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('adres-token');
        if ($this->isCsrfTokenValid('edit-adres', $submittedToken)) {
            if ($form->isSubmitted()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Adres bilgileriniz güncellendi.');
                return $this->redirectToRoute('profile', [
                    'id' => $id,
                ]);
            }
        }
        return $this->render('home/profile.html.twig', [
            'setting' => $data,
            'cats' => $cats,
            'address' => $address,
            'orders' => $ordersRepository->findAll(),
            'adres' => $adres,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Address $address): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('address_index');
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
