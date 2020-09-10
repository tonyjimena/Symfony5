<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Projects;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ProjectsRepository;
use App\Form\ProjectsFormType;


class ApiController extends AbstractController
{

    private $projectsRepository;

    public function __construct(ProjectsRepository $projectsRepository)
    {
        $this->projectsRepository = $projectsRepository;
    }

    /**
    * @Route("/projectsajax", name="ajax_index")
    */

    public function ajaxindex()
    {
        $project = new Projects();

        $form = $this->createForm(ProjectsFormType::class, $project);

        return $this->render('projects/index.html.twig', [
            'controller_name' => 'ProjectsController',
            'form' => $form->createView(),
        ]);
    }
    /**
    * @Route("/api/projects", options={"expose"=true}, name="projects_json", methods={"GET"})
    */

    public function indexjson(/*Request $request*/): JsonResponse
    {        

        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository(Projects::class)->findAll();

        $data = [];
            foreach($projects as $project) {
                $data[] = [
                    'id' => $project->getId(),
                    'name' => $project->getName(),
                    'info' => $project->getInfo(),
                    'place' => $project->getPlace(),
                    'image' => $project->getImage()
                ];

            }
            return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
    * @Route("/api/find", options={"expose"=true}, name="projects_json_id", methods={"GET"})
    */

    public function findIdAjax(/*Request $request*/): JsonResponse
        {      
            if ($_GET) {
                $id = $_GET['id'];
                $project = $this->projectsRepository->find($id);

                $data = [];                   
                        $data[] = [
                            'id' => $project->getId(),
                            'name' => $project->getName(),
                            'info' => $project->getInfo(),
                            'place' => $project->getPlace(),
                            'image' => $project->getImage()
                        ];
            }else {
                $data = $this->projectsRepository->lastId();
            }
                return new JsonResponse($data, Response::HTTP_OK);
            
        }

    /**
    * @Route("/api/projects/delete/{id}", options={"expose"=true}, name="projects_json_delete", methods={"GET"})
    */

    public function deleteProjectjson($id): JsonResponse
        {
            $repository = $this->getDoctrine()->getRepository(Projects::class);
            $projects = $repository->find($id);

            $em = $this->getDoctrine()->getManager();
            
            $imagefile = $projects->getImage();
            if ($imagefile){
            unlink('/app/myapp/public/uploads/images/'.$imagefile);
            }
            $em->remove($projects);
            
            $em->flush();
            return new JsonResponse(['status'=>'Eliminado'], Response::HTTP_OK);
        }

    /**
    * @Route("/api/projects/new", name="ajax_add")
    */

    public function new(Request $request): JsonResponse
    {
        $uploadDir = 'uploads/images/'; 
        $response = array( 
            'status' => 0, 
            'message' => 'Form submission failed, please try again.' 
        ); 
 
        // If form is submitted 
        if(isset($_POST['name']) || isset($_POST['info']) || isset($_POST['file'])){ 
            // Get the submitted form data 
            $name = $_POST['name'];
            $info = $_POST['info'];
            $place = $_POST['place']; 
            
            // Check whether submitted data is not empty 
            if(!empty($name) && !empty($info)){ 

                    $uploadStatus = 1; 
                    
                    // Upload file 
                    $uploadedFile = ''; 
                    if(!empty($_FILES["file"]["name"])){ 
                        
                        // File path config 
                        $fileName = basename($_FILES["file"]["name"]);

                        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $fileName);
                        $fileName = $safeFilename.'-'.uniqid().'.'.$fileName;
                        
                        $targetFilePath = $uploadDir . $fileName; 
                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                        
                        // Allow certain file formats 
                        $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
                        if(in_array($fileType, $allowTypes)){ 
                            // Upload file to the server 
                            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
                                $uploadedFile = $fileName; 
                            }else{ 
                                $uploadStatus = 0; 
                                $response['message'] = 'Sorry, there was an error uploading your file.'; 
                            } 
                        }else{ 
                            $uploadStatus = 0; 
                            $response['message'] = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.'; 
                        } 
                    } 
                    
                    if($uploadStatus == 1){ 

                        $id = $this->projectsRepository->saveProject($name, $info, $place, $uploadedFile);

                        $project = $this->projectsRepository->find($id);

                        $data = [];
                   
                        $data[] = [
                            'id' => $project->getId(),
                            'name' => $project->getName(),
                            'info' => $project->getInfo(),
                            'place' => $project->getPlace(),
                            'image' => $project->getImage()
                        ];

                        return new JsonResponse($data, Response::HTTP_CREATED);
                    } 
                } 
            }
            else{ 
                $response['message'] = 'Please fill all the mandatory fields (name and info).'; 
            }    
    }

    /**
    * @Route("/api/projects/edit/{id}", name="edit_api")
    */

    public function editjson($id, Request $request): JsonResponse
    {
        $project = $this->projectsRepository->find($id);
        empty($_POST['name']) ? true : $project->setName($_POST['name']);
        empty($_POST['info']) ? true : $project->setInfo($_POST['info']);
        empty($_POST['place']) ? true : $project->setPlace($_POST['place']);
            
            $uploadDir = 'uploads/images/'; 

            $uploadStatus = 1; 
            
            // Upload file 
            $uploadedFile = '';

            if(!empty($_FILES["file"]["name"])){ 
                
                // File path config 
                $fileName = basename($_FILES["file"]["name"]);

                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $fileName);
                $fileName = $safeFilename.'-'.uniqid().'.'.$fileName;
                
                $targetFilePath = $uploadDir . $fileName; 
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                
                // Allow certain file formats 
                $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
                if(in_array($fileType, $allowTypes)){ 
                    // Upload file to the server 
                    if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
                        $uploadedFile = $fileName; 
                    }else{ 
                        $uploadStatus = 0; 
                        $response['message'] = 'Sorry, there was an error uploading your file.'; 
                    } 
                }else{ 
                    $uploadStatus = 0; 
                    $response['message'] = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.'; 
                } 
            } 
            
            if(($uploadStatus == 1) && (!empty($uploadedFile))){

                $imagefile = $project->getImage();
                if ($imagefile){
                unlink('/app/myapp/public/uploads/images/'.$imagefile);
                }
                
                $project->setImage($uploadedFile);
            }
                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush();

                
                $updateProject = $this->projectsRepository->find($id);

                $data = [];
           
                $data[] = [
                    'id' => $updateProject->getId(),
                    'name' => $updateProject->getName(),
                    'info' => $updateProject->getInfo(),
                    'place' => $updateProject->getPlace(),
                    'image' => $updateProject->getImage()
                ];

                return new JsonResponse($data, Response::HTTP_OK);
    }
}