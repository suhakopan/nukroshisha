<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UsersType;
use App\Repository\AddressRepository;
use App\Repository\Admin\SettingRepository;
use App\Repository\Admin\SubCategoryRepository;
use App\Repository\OrdersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/apppanel", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login2.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login2(AuthenticationUtils $authenticationUtils,SettingRepository $settingRepository): Response
    {
        $cats = $this->categorytree();
        $data = $settingRepository->findAll();
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig',
            [
                'setting' => $data,
                'last_username' => $lastUsername,
                'error' => $error,
                'cats' => $cats,
            ]);
    }

    /**
     * @Route("/{id}/edit", name="password_edit", methods={"GET","POST"})
     */
    public function edit($id,Request $request, User $user, SettingRepository $settingRepository,AddressRepository $addressRepository,OrdersRepository $ordersRepository): Response
    {
        $adres = $addressRepository->findBy(['UserID' =>$id]);
        $data = $settingRepository->findAll();
        $cats = $this->categorytree();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('editpass-token');
        if ($this->isCsrfTokenValid('pass-auth', $submittedToken)) {
            if ($form->isSubmitted()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Şifreniz başarıyla değişmiştir. Bundan sonraki giriş işleminizi yeni şifrenizle yapabilirsiniz');
                return $this->redirectToRoute('profile');
            }
        }

        return $this->render('home/profile.html.twig', [
            'setting' => $data,
            'adres' => $adres,
            'orders' => $ordersRepository->findAll(),
            'cats' => $cats,
            'form' => $form->createView(),
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

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        //throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

}
