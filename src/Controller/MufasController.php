<?php

namespace App\Controller;

use App\Entity\Mufas;
use App\Form\MufasType;
use App\Form\MufasUploadType;
use App\Repository\MufasRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/mufas')]
final class MufasController extends AbstractController
{
    #[Route(name: 'app_mufas_index', methods: ['GET'])]
    public function index(MufasRepository $mufasRepository): Response
    {
        return $this->render('mufas/index.html.twig', [
            'mufas' => $mufasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mufas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mufa = new Mufas();
        $form = $this->createForm(MufasType::class, $mufa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mufa);
            $entityManager->flush();

            return $this->redirectToRoute('app_mufas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mufas/new.html.twig', [
            'mufa' => $mufa,
            'form' => $form,
        ]);
    }

    #[Route('/upload', name: 'app_mufas_upload', methods: ['GET', 'POST'])]
    public function upload(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MufasUploadType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            $csvFile = $form->get('file')->getData();
                     
            if ($csvFile) {
                try {
                    $this->processUploadedFile($csvFile, $entityManager);
                    $this->addFlash('success', 'Los datos del CSV han sido procesados e insertados correctamente.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Ocurrió un error al procesar el archivo: ' . $e->getMessage());
                }
            } else{
                $this->addFlash('error', 'Ocurrió sin data ');
            }
                
            return $this->redirectToRoute('app_mufas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mufas/upload.html.twig', [
            'form' => $form,
        ]);
    }


    private function processUploadedFile(UploadedFile $file, EntityManagerInterface $entityManager): void
    {
        $content = file_get_contents($file->getPathname());
        $rows = explode("\n", $content);

        // Eliminamos la primera fila si contiene encabezados
        array_shift($rows);

        foreach ($rows as $row) {
            $data = str_getcsv($row);
            dump($data);
            dump($row);
           
            if (count($data) >= 8) {
                $mufa = new Mufas();
                $mufa->setZonal($data[0] ?? null);
                $mufa->setSitio($data[1] ?? null);
                $mufa->setCable($data[2] ?? null);
                $mufa->setCodigoMufa($data[3] ?? null);
                $mufa->setDistanciaOptica(is_numeric($data[4]) ? (float)$data[4] : null);
                $mufa->setLatitud(is_numeric($data[5]) ? (float)$data[5] : null);
                $mufa->setLongitud(is_numeric($data[6]) ? (float)$data[6] : null);
                $mufa->setReferencia($data[7] ?? null);
                if ($mufa->getZonal() && $mufa->getSitio()){
                    $entityManager->persist($mufa);
                }
            }
        }

        $entityManager->flush();
    }


    #[Route('/{id}', name: 'app_mufas_show', methods: ['GET'])]
    public function show(Mufas $mufa): Response
    {
        return $this->render('mufas/show.html.twig', [
            'mufa' => $mufa,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mufas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mufas $mufa, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MufasType::class, $mufa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mufas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mufas/edit.html.twig', [
            'mufa' => $mufa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mufas_delete', methods: ['POST'])]
    public function delete(Request $request, Mufas $mufa, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mufa->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($mufa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_mufas_index', [], Response::HTTP_SEE_OTHER);
    }


}
