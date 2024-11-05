<?php

namespace App\Controller;

use App\Form\EditCouchDbUserType;
use App\Form\UsuarioCouchDbType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CouchDbService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/user')]
class ApiCouchdbController extends AbstractController
{
    #[Route('/all', name: 'app_all_users', methods: ['GET'])]
    public function findUsers(Request $request, CouchDbService $couchDbService)
    {
        $query = ["selector" => [ "type"=> "user" ] ];
        $result = $couchDbService->findUser($query);
        $users = $result['docs'] ?? [];
        return $this->render('api_couchdb/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/show/{id}', name: 'app_show_user', methods: ['POST','GET'])]
    public function findUser($id, Request $request, CouchDbService $couchDbService)
    {
        $query = ["selector" => [ "type"=> "user", "_id"=> $id ] ];
        $result = $couchDbService->findUser($query);
        $user = $result['docs'] ?? [];    
        return $this->render('api_couchdb/show.html.twig', [
            'user' => $user[0],
        ]);
    }

    #[Route('/delete/{id}/{rev}', name: 'app_delete_user', methods: ['POST','GET'])]
    public function deleteUser($id,$rev, Request $request, CouchDbService $couchDbService)
    {

        $result = $couchDbService->deleteUser($id,$rev);
        if($result['ok']){
            $this->addFlash('success', 'Usuario Eliminado!');
            return $this->redirectToRoute('app_all_users', [], Response::HTTP_SEE_OTHER);
        }else{
            $this->addFlash('warning', 'No se logra eliminar al usuario!');
        }
    }

    #[Route('/new', name: 'app_new_user', methods: ['POST','GET'])]
    public function newUser(Request $request, CouchDbService $couchDbService, ParameterBagInterface $params): Response
    {
        $form = $this->createForm(UsuarioCouchDbType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){ 
       //     if ($this->isCsrfTokenValid('couchdb'.  $form->get('_token')->getData(), $request->request->get('_token'))){

            
            $data = [
                "full_name" => $form->get('full_name')->getData(),
                "rut" => $form->get('rut')->getData(),
                "name" => $form->get('username')->getData(),
                "password" =>$form->get('password')->getData(),
                "company" => "GTDManquehue",
                "sso_roles" => ["ROLE_USER", "ROLE_TECNICO"],
                "roles" => [],
                "type" => "user",
            ];  
            $result = $couchDbService->newUser($data); 
            if(!array_key_exists('error', $result)){
                $this->addFlash('success', 'Usuario Agregado con exito!');
                return $this->redirectToRoute('app_all_users', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('warning', 'No logramos crear el usuario intente nuevamente!');
                return $this->redirectToRoute('app_all_users', [], Response::HTTP_SEE_OTHER);
            }
        }
   // }
        return $this->render('api_couchdb/new.html.twig',[
            'form'=> $form,
        ]);
    }

   #[Route('/edit/{id}', name: 'app_edit_user', methods: ['POST','GET'])]
    public function editUser($id, Request $request, CouchDbService $couchDbService, ParameterBagInterface $params): Response
    {
       
        $query = ["selector" => [ "type"=> "user", "_id"=> $id ] ];
        $data=$couchDbService->findUser($query);
        $data=($data['docs']['0']);
        $form = $this->createForm(EditCouchDbUserType::class, $data);
        $form->handleRequest($request);
     
      
        if ($form->isSubmitted() && $form->isValid()){  
           // if ($this->isCsrfTokenValid('couchdb'.  $form->get('_token')->getData(), $request->request->get('_token'))){        
            $data["password"]  = $form->get('password')->getData();
            $result = $couchDbService->editUser($data); 
            
            if(!array_key_exists('error', $result)){
                $this->addFlash('success', 'Usuario editado con exito!');
                return $this->redirectToRoute('app_all_users', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('warning', 'No logramos editar el usuario intente nuevamente!');
                return $this->redirectToRoute('app_all_users', [], Response::HTTP_SEE_OTHER);
            }
        }
   // }
        
        return $this->render('api_couchdb/edit.html.twig',[
            'form'=> $form,
            'name'=> $data['name'],
        ]);
    }
}
