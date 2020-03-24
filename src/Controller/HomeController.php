<?php

namespace App\Controller;


use App\Entity\Address;
use App\Entity\Admin\Message;
use App\Entity\User;
use App\Form\AddressType;
use App\Form\Admin\MessageType;
use App\Form\UsersType;
use App\Repository\AddressRepository;
use App\Repository\Admin\ProductRepository;
use App\Repository\Admin\SettingRepository;
use App\Repository\Admin\SubCategoryRepository;
use App\Repository\Admin\ImageRepository;
use App\Repository\OrdersRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SettingRepository $settingRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT * FROM Product where Image1 is not null';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $cats = $this->categorytree();
        $data = $settingRepository->findAll();
        return $this->render('home/index.html.twig', [
            'setting' => $data,
            'cats' => $cats,
            'slider' => $result,
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(SettingRepository $settingRepository)
    {
        $cats = $this->categorytree();
        $data = $settingRepository->findAll();
        return $this->render('home/about.html.twig', [
            'setting' => $data,
            'cats' => $cats,
        ]);
    }

    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     */
    public function contact(SettingRepository $settingRepository,Request $request):Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class,$message);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');
        if($form->isSubmitted())
        {
            if($this->isCsrfTokenValid('form-message',$submittedToken)){
                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();
                $this->addFlash('success','Mesajınız başarılı bir şekilde gönderildi');
                return $this->redirectToRoute('contact');
            }
        }
        $cats = $this->categorytree();
        $data = $settingRepository->findAll();
        return $this->render('home/contact.html.twig', [
            'setting' => $data,
            'cats' => $cats,
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(SettingRepository $settingRepository,AddressRepository $addressRepository,OrdersRepository $ordersRepository)
    {
        $user = $this->getUser();
        $id = $user->getid();
        $address = $addressRepository->findBy(['UserID' =>$id]);
        $cats = $this->categorytree();
        $data = $settingRepository->findAll();
        return $this->render('home/profile.html.twig', [
            'orders' => $ordersRepository->findBy(['UserID'=>$id]),
            'setting' => $data,
            'cats' => $cats,
            'adres' => $address,
        ]);
    }

    /**
     * @Route("/category/{catid}", name="category_products", methods="GET")
     */
    public function CategoryProducts($catid,SubCategoryRepository $categoryRepository,SettingRepository $settingRepository)
    {
        $data = $settingRepository->findAll();

        $cats = $this->categorytree();
        $data2 = $categoryRepository->findBy(
            ['id' => $catid]
        );
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT P.*,I.image FROM product P,image I WHERE type= :catid and P.id=I.product_id GROUP BY P.id';
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('catid',$catid);
        $statement->execute();
        $products = $statement->fetchAll();
        return $this->render('home/product.html.twig',[
            'data' => $data2,
            'products' => $products,
            'cats' => $cats,
            'setting' => $data
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_detail", methods="GET")
     */
    public function ProductDetail($id,ProductRepository $productRepository,ImageRepository $imageRepository,SettingRepository $settingRepository)
    {
        $data = $settingRepository->findAll();
        $data2 = $productRepository->findBy(
            ['id' => $id]
        );

        $images = $imageRepository->findBy(
            ['productId' => $id]
        );
        $cats = $this->categorytree();

        return $this->render('home/single.html.twig',[
            'setting' => $data,
            'product' => $data2,
            'cats' => $cats,
            'images' => $images
        ]);
    }

    /**
     * @Route("/register", name="new_user",methods="GET|POST")
     */
    public function register(Request $request,SettingRepository $settingRepository,UserRepository $userRepository):Response
    {
        $data = $settingRepository->findAll();
        $cats = $this->categorytree();
        $adres = new Address();
        $user = new User();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('register-auth', $submittedToken)) {
            if ($form->isSubmitted()) {
                $emaildata = $userRepository->findBy(['email' =>$user->getEmail()]);
                if($emaildata == null){

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $userid = $user->getId();
                    $form2 = $this->createForm(AddressType::class,$adres);
                    $form2->handleRequest($request);
                    $adres->setUserID($userid);
                    $em->persist($adres);
                    $em->flush();
                    $this->addFlash('success','Yeni üyelik kaydınız oluşturuldu. Giriş yapabilirsiniz!');
                    return $this->redirectToRoute('app_login');
                }
                else{
                    $this->addFlash('error', "Bu mail adresi sistemimizde zaten kayıtlıdır. Lütfen başka bir mail adresi ile deneyiniz");
                }

            }
        }
        return $this->render('home/register.html.twig', [
            'form' => $form->createView(),
            'setting' => $data,
            'cats' => $cats
        ]);
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
