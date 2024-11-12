<?php

namespace App\Controller;

use App\Entity\Mufas;
use App\Form\RemoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mufa/calculo')]
class RemoController extends AbstractController
{
    #[Route('/', name: 'app_remo_search')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mufa = new Mufas();
        
        // Obtener opciones para zonal
        $zonalOptions = $entityManager->getRepository(Mufas::class)->findUniqueZonal();
        
        $zonalChoices = [];
        foreach ($zonalOptions as $option) {
            $zonalChoices[$option['zonal']] = $option['zonal'];
        }

        $form = $this->createForm(RemoType::class, $mufa, [
            'zonal_choices' => $zonalChoices
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
           
            //if ($form->isValid()) {
            
            
                try {
                    
                    $formData = $request->request->all();
                    
      
                    $distancia_corte = $formData['remo']['distancia_corte'];

                    $zonal = $formData['remo']['zonal'];
                    $sitio = $formData['remo']['sitio'];
                    $cable = $formData['remo']['cable'];

                    $mufas = $entityManager->getRepository(Mufas::class)->findAllMufasWithZonalSitioCable($zonal, $sitio, $cable);

                    if ($mufas) {  

                        $lower = null;
                        $upper = null;
                        $minDiffLower = PHP_FLOAT_MAX;
                        $minDiffUpper = PHP_FLOAT_MAX;

                        foreach ($mufas as $mufa) {
                            $diff = $distancia_corte - $mufa->getDistanciaOptica();
                            if ($diff > 0 && $diff < $minDiffLower) {
                                $lower = $mufa;
                                $minDiffLower = $diff;
                            } elseif ($diff < 0 && abs($diff) < $minDiffUpper) {
                                $upper = $mufa;
                                $minDiffUpper = abs($diff);
                            } elseif ($diff == 0) {
                                $lower = $mufa;
                                $upper = $mufa;
                                break;
                            }
                        }
                    }
                                       
                    return $this->redirectToRoute('app_remo_inter_mufas', [
                        'id_lower'=> $lower?->getId() ?? 'No Existe', 
                        'id_upper'=>$upper?->getId() ?? 'No Existe' 
                    ], Response::HTTP_SEE_OTHER);    
                } 
                catch (\Exception $e) {
                    $this->addFlash('error', 'Error al procesar el formulario: ' . $e->getMessage());
                }
        }
        return $this->render('remo/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/intermufas/{id_lower}/{id_upper}', name: 'app_remo_inter_mufas')]
    public function indintermufas(Request $request, $id_lower, $id_upper,EntityManagerInterface $entityManager): Response
    {   
       
        if($id_lower!='No Existe'){
        $mufa_lower = $entityManager->getRepository(Mufas::class)->findOneBy(['id'=>$id_lower]);
        } else{
            $mufa_lower = array(
                'zonal'=>'-',
                'sitio' => '-',
                'cable' => '-',
                'distanciaOptica' => '-',
                'referencia' => '-',
                'codigoMufa' =>'-',
                'latitud' =>'0',
                'longitud' =>'0'

            );
        }
        if($id_upper!='No Existe'){
        $mufa_upper = $entityManager->getRepository(Mufas::class)->findOneBy(['id'=>$id_upper]);
        } else {
            $mufa_upper = array(
                'zonal'=>'-',
                'sitio' => '-',
                'cable' => '-',
                'distanciaOptica' => '-',
                'referencia' => '-',
                'codigoMufa' =>'-',
                'latitud' =>'0',
                'longitud' =>'0'

            );
        }

        return $this->render('remo/inter_mufas.html.twig',[
            'mufa_lower'=> $mufa_lower, 
            'mufa_upper'=> $mufa_upper
        ]);
    }
        


    #[Route('/remo/get-sitios/{zonal}', name: 'app_remo_get_sitios', methods: ['GET'])]
    public function getSitios(string $zonal, EntityManagerInterface $entityManager): JsonResponse
    {
        $sitios = $entityManager->getRepository(Mufas::class)->findSitiosInZonal($zonal);
        $sitioChoices = [];
        foreach ($sitios as $sitio) {
            $sitioChoices[] = $sitio['sitio'];
        }

        return new JsonResponse($sitioChoices);
    }

    #[Route('/remo/get-cables/{zonal}/{sitio}', name: 'app_remo_get_cables', methods: ['GET'])]
    public function getCables(string $zonal, string $sitio, EntityManagerInterface $entityManager): JsonResponse
    {
        $cables = $entityManager->getRepository(Mufas::class)->findCablesInSitiosAndZonal($zonal,$sitio);

        $cableChoices = [];
        foreach ($cables as $cable) {
            $cableChoices[] = $cable['cable'];
        }

        return new JsonResponse($cableChoices);
    }
}