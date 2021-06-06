<?php

namespace App\Controller;

use App\Entity\Lang;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\TraductionSource;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\LangRepository;
use App\Service\Project\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    /**
     * @Route("/", name="project_index", methods={"GET"})
     */
    public function index(Request $request, ProjectRepository $projectRepository): Response
    {
        $page = $request->query->has('page') ? $request->get('page') : 1;
        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findByUserWithPagination($this->getUser(), $page),
        ]);
    }

    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $project = new Project();
        $user = $this->getUser();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$this->projectService->createProject($project, $user, $langrepository, $form->get('languageList')->getData());
            $file = $form['upload_file']->getData();
            $this->projectService->createProject($project, $file);

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_show", methods={"GET"})
     */
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/traductions/{id}", name="traductions_show", methods={"GET"})
     */
    public function show_traductions(TraductionSource $traductionSource): Response
    {
        return $this->render('traduction_source/show.html.twig', [
            'source' => $traductionSource,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project): Response
    {
        $form = $this->createFormBuilder($project)
                ->add('isDeleted')
                ->add('name')
                ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->projectService->updateProject();

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_delete", methods={"POST"})
     */
    public function delete(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->request->get('_token'))) {
            $this->projectService->deleteProject($project);
        }

        return $this->redirectToRoute('project_index');
    }

}
