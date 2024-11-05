<?php

namespace App\Controller;

use App\Entity\AdminTech;
use App\Form\AdminTechType;
use App\Repository\AdminTechRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/tech')]
final class AdminTechController extends AbstractController
{
    #[Route(name: 'app_admin_tech_index', methods: ['GET'])]
    public function index(AdminTechRepository $adminTechRepository): Response
    {
        return $this->render('admin_tech/index.html.twig', [
            'admin_teches' => $adminTechRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_tech_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adminTech = new AdminTech();
        $form = $this->createForm(AdminTechType::class, $adminTech);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adminTech);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_tech_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_tech/new.html.twig', [
            'admin_tech' => $adminTech,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_tech_show', methods: ['GET'])]
    public function show(AdminTech $adminTech): Response
    {
        return $this->render('admin_tech/show.html.twig', [
            'admin_tech' => $adminTech,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_tech_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdminTech $adminTech, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminTechType::class, $adminTech);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_tech_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_tech/edit.html.twig', [
            'admin_tech' => $adminTech,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_tech_delete', methods: ['POST'])]
    public function delete(Request $request, AdminTech $adminTech, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adminTech->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adminTech);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_tech_index', [], Response::HTTP_SEE_OTHER);
    }
}
