<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Setting;
use App\Form\Admin\SettingType;
use App\Repository\Admin\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/setting")
 */
class SettingController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="admin_setting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Setting $setting): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $sql2 = 'SELECT count(*) as count FROM User';
        $statement2 = $entityManager->getConnection()->prepare($sql2);
        $statement2->execute();
        $result2 = $statement2->fetchAll();
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Site ayarları güncellendi');
            return $this->redirectToRoute('admin_setting_edit', [
                'id' => $setting->getId(),
            ]);
        }

        return $this->render('admin/setting/edit.html.twig', [
            'setting' => $setting,
            'form' => $form->createView(),
            'count_users' => $result2,
        ]);
    }


}
