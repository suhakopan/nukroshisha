<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Message;
use App\Form\Admin\MessageType;
use App\Repository\Admin\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/", name="admin_message_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $entityManager->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        return $this->render('admin/message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
            'count_users' => $result2,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_message_show", methods={"GET"})
     */
    public function show(Message $message,$id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $entityManager->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        $sql = "Update Message set Status=1 where Id=:id";
        $statement = $entityManager->getConnection()->prepare( $sql);
        $statement->bindValue('id',$id);
        $statement->execute();
        return $this->render('admin/message/show.html.twig', [
            'message' => $message,
            'count_users' => $result2,
        ]);
    }

}
