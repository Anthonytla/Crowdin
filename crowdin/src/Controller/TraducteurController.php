<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\TraductionSource;
use App\Entity\TraductionTarget;
use App\Form\TraductionTargetType;
use App\Service\Traducteur\TraducteurService;
use App\Service\TraductionTarget\TraductionTargetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraducteurController extends AbstractController
{
    public function __construct(TraducteurService $traducteurService, TraductionTargetService $traductionTargetService)
    {
        $this->traducteurService = $traducteurService;
        $this->traductionTargetService = $traductionTargetService;
    }

    /**
     * @Route("/traducteur", name="traducteur")
     */
    public function random(): Response
    {
        $project_with_lang = $this->traducteurService->rand_gen();
        
        if ($project_with_lang)
            return $this->redirectToRoute('traducteur_project', [
                'id' => $project_with_lang->getId(),
            ]);
        else
            return $this->render('traducteur/index.html.twig', [
                'project' => null,
            ]);
    }

    /**
     * @Route("/traducteur/project/{id}", name="traducteur_project")
     */
    public function show_rand_project(Project $project): Response
    {
        return $this->render('traducteur/index.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/traducteur/translate/{id}", name="translate")
     */
    public function translate(Request $request, Project $project)
    {
        $target = new TraductionTarget();
        $form = $this->createForm(TraductionTargetType::class, $target, ['user' => $this->getUser(), 'project' => $project]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($target->getSource()->getBlocked() == 0)
                $this->traductionTargetService->createTraductionTarget($target);

            return $this->redirectToRoute('traducteur_project', ['id' => $project->getId()]);
        }
        $sources = $this->traducteurService->getSourcesTarget($project);
        //$sources = $this->getDoctrine()->getManager()->getRepository(TraductionSource::class)->findBy(['project' => $project]);
        return $this->render('traducteur/new.html.twig', [
            'id' => $project->getId(),
            'sources' => $sources[0],
            'blocked' => $sources[1],
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/traducteur/edit/{id}", name="traducteur_edit")
     */
    public function edit(Request $request, TraductionTarget $traductionTarget)
    {
        
        $form = $this->createForm(TraductionTargetType::class, $traductionTarget, ['user' => $this->getUser(), 'project' => $traductionTarget->getSource()->getProject()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->traducteurService->updateTarget();

            return $this->redirectToRoute('traducteur_show', ['id' => $traductionTarget->getSource()->getId()]);
        }
        $sources = $this->traducteurService->getSourcesTarget($traductionTarget->getSource()->getProject());

        return $this->render('traducteur/edit.html.twig', [
            'target' => $traductionTarget,
            'sources' => $sources[0],
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("traducteur/{id}", name="traducteur_delete", methods={"POST"})
     */
    public function delete(Request $request, TraductionTarget $traductionTarget): Response
    {
        if ($this->isCsrfTokenValid('delete' . $traductionTarget->getId(), $request->request->get('_token'))) {
            $this->traducteurService->deleteTarget($traductionTarget);
        }

        return $this->redirectToRoute('traducteur_show', ['id' => $traductionTarget->getSource()->getId()]);
    }

    /**
     * @Route("/traducteur/{id}", name="traducteur_show")
     */
    public function show(TraductionSource $traductionSource)
    {
        return $this->render('traducteur/show.html.twig', ['source' => $traductionSource]);
    }

}
