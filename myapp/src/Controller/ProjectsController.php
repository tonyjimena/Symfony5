<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Projects;
use App\Form\ProjectsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProjectsRepository;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\FileUploader;

class ProjectsController extends AbstractController
{
    private $projectsRepository;
    //private $entityManager;

    public function __construct(ProjectsRepository $projectsRepository)
    {
        $this->projectsRepository = $projectsRepository;
        //$this->getDoctrine()->getManager() = $entityManager;
    }

    /**
    * @Route("/projects", name="projects_index")
    */

    public function projects()
    {

        //$em = $this->getDoctrine()->getManager();

        $projects = $this->projectsRepository->findAll();

        $project = new Projects();

        $form = $this->createForm(ProjectsFormType::class, $project);

        return $this->render('front/projects.html.twig', [
            'controller_name' => 'ProjectsController',
            'projects' => $projects,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/projects/view/{id}", name="projects_view")
    */
    public function viewProjects($id)
    {
        //$em = $this->getDoctrine()->getManager();
        $projects = $this->projectsRepository->find($id);

        return $this->render('projects/projects_view.html.twig', [
            'controller_name' => 'ProjectsController',
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/projects/new", name="projects_new")
     */

    public function createProjects(Request $request, FileUploader $fileUploader): Response
    {
        // you can fetch the em via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(emInterface $em)
        $em = $this->getDoctrine()->getManager();

        $project = new Projects();

        $form = $this->createForm(ProjectsFormType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projects = $form->getData();
             /** @var UploadedFile $imageFile */
                
            $imageFile = $form['image']->getData();
                if ($imageFile) {
                    $imageFileName = $fileUploader->upload($imageFile);
                    $projects->setImage($imageFileName);
                }
        $em->persist($projects);
        /*
        $projects->setName($projects['name']);
        $projects->setInfo($projects['info']);
        $projects->setPlace($projects['place']);
        */
        $em->flush();
        /*return new Response('Saved new projects with id '.$projects->getId());*/
        return $this->redirectToRoute('projects_index');
        }
        /*$projects = new Projects();
        $projects->setName($_POST['name']);
        $projects->setInfo($_POST['info']);
        $projects->setPlace($_POST['place']);
        // tell Doctrine you want to (eventually) save the projects (no queries yet)
        $em->persist($projects);
        // actually executes the queries (i.e. the INSERT query)
        $em->flush();*/
        /*return new Response('Saved new projects with id '.$projects->getId());*/
        return $this->redirectToRoute('projects_index');
    }

    /**
     * @Route("/projects/remove/{id}", name="projects_remove")
     */
    public function removeProjects($id): Response
    { 
        //$repository = $this->getDoctrine()->getRepository(Projects::class);
        
        $projects = $this->projectsRepository->find($id);
        // you can fetch the em via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(emInterface $em)
        $em = $this->getDoctrine()->getManager();
        
        $imagefile = $projects->getImage();

        //Eliminamos la imagen del servidor
        unlink('/app/myapp/public/uploads/images/'.$imagefile); 

        $em->remove($projects);
        
        $em->flush();
        /*return new Response('Saved new projects with id '.$projects->getId());*/
        return $this->redirectToRoute('projects_index');
    }

     /**
     * @Route("/projects/edit/{id}", name="projects_edit")
     */
    public function editProjects($id)
    {
        
        //$em = $this->getDoctrine()->getManager();
        
        $projects = $this->projectsRepository->find($id);
        
        $form = $this->createForm(ProjectsFormType::class, $projects, [
            'action' => $this->generateUrl('projects_update', array('id' => $id)),
        ]);

        return $this->render('projects/projects_edit.html.twig', [
            'controller_name' => 'ProjectsController',
            'projects' => $projects,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/projects/update/{id}", name="projects_update")
     */
    public function updateProjects(Request $request, FileUploader $fileUploader, $id): Response
    {  

        //$repository = $this->getDoctrine()->getRepository(Projects::class);

        $projects = $this->projectsRepository->find($id);
        // you can fetch the em via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(emInterface $em)
        
        $em = $this->getDoctrine()->getManager();
        
        $form = $this->createForm(ProjectsFormType::class, $projects);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $projects = $form->getData();
            
            /** @var UploadedFile $imageFile */
               
           $imageFile = $form['image']->getData();
                
           if ($imageFile) {
            
                $imageFileName = $fileUploader->upload($imageFile);
            
                $projects->setImage($imageFileName);
            }

        $em->persist($projects);
        
        $em->flush();
        /*return new Response('Saved new projects with id '.$projects->getId());*/
        return $this->redirectToRoute('projects_index');
        }

        return $this->redirectToRoute('projects_index');
    }
}

