<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT count(*) as count FROM User';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
        $users = $this ->getDoctrine()
            ->getRepository(User::class    )
            ->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'count_users' => $result,
        ]);
    }
}
