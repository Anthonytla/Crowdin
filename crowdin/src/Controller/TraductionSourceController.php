<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\TraductionSource;
use App\Form\TraductionSourceType;
use App\Service\TraductionSource\TraductionSourceService;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraductionSourceController extends AbstractController
{

    public function __construct(TraductionSourceService $traductionSourceService)
    {
        $this->traductionSourceService = $traductionSourceService;
    }

    /**
     * @Route("/traduction/source", name="traduction_source")
     */
    public function index(): Response
    {
        return $this->render('traduction_source/index.html.twig', [
            'controller_name' => 'TraductionSourceController',
        ]);
    }

    /**
     * @Route("project/traduction/source/new", name="traduction_source_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $traduction = new TraductionSource();
        $form = $this->createForm(TraductionSourceType::class, $traduction, ['userId' => $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->traductionSourceService->createTraductionSource($traduction);

            return $this->redirectToRoute('project_index');
        }

        return $this->render('traduction_source/new.html.twig', [
            'traduction' => $traduction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("project/traduction/source/edit/{id}", name="traduction_source_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TraductionSource $traductionSource)
    {
        $form = $this->createForm(TraductionSourceType::class, $traductionSource, ['userId' => $traductionSource->getProject()->getUserId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->traductionSourceService->updateTraductionSource();

            return $this->redirectToRoute('traductions_show', ['id' => $traductionSource->getId()]);
        }

        return $this->render('traduction_source/edit.html.twig', [
            'source' => $traductionSource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("project/traduction/source/{id}", name="traduction_source_delete", methods={"POST"})
     */
    public function delete(Request $request, TraductionSource $traductionSource): Response
    {
        if ($this->isCsrfTokenValid('delete' . $traductionSource->getId(), $request->request->get('_token'))) {
            $this->traductionSourceService->deleteTraductionSource($traductionSource);
        }

        return $this->redirectToRoute('project_index');
    }

    /**
     * @Route("project/traductions/{id}/block", name = "source_block", methods={"POST"})
     */
    public function block(Request $request, TraductionSource $source): Response
    {
        if ($this->isCsrfTokenValid('block' . $source->getId(), $request->get('_token'))) {
            $this->traductionSourceService->blockSource($source);
        }

        return $this->redirectToRoute('project_index');
    }

    /**
     * @Route("project/traductions/{id}/unblock", name = "source_unblock", methods={"POST"})
     */
    public function unblock(Request $request, TraductionSource $source): Response
    {
        if ($this->isCsrfTokenValid('unblock' . $source->getId(), $request->get('_token'))) {
            $this->traductionSourceService->unblockSource($source);
        }

        return $this->redirectToRoute('project_index');
    }
}
