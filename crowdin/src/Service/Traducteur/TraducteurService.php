<?php

namespace App\Service\Traducteur;

use App\Entity\Project;
use App\Entity\TraductionSource;
use App\Entity\TraductionTarget;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TraducteurService
{
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function rand_gen()
    {
        $user = $this->security->getUser();
        $projects = $this->em->getRepository(Project::class)->findAll();
        $project_lang = array();
        $codes_arr = array_map(function($lang) {
            return $lang->getCode();
        }, $user->getLangs()->toArray());
        foreach ($projects as $project) {
            if (in_array($project->getLang()->getCode(), $codes_arr))
                array_push($project_lang, $project);
        }
        $len = count($project_lang);
        if ($len > 0)
            return $project_lang[rand(0, $len - 1)];
        else
            return null;
    }

    public function updateTarget() 
    {
        $this->em->flush();
    }

    public function deleteTarget(TraductionTarget $target)
    {
        $this->em->remove($target);
        $this->em->flush();
    }

    public function getSourcesTarget(Project $project)
    {
        $traductionSources = $this->em->getRepository(TraductionSource::class)->findBy(['project' => $project]);
        $data = [];
        $block_data = [];
        foreach ($traductionSources as $traduction){
            $data[$traduction->getId()] = $traduction->getTarget();
            $block_data[$traduction->getId()] = $traduction->getBlocked();
        }
        $tab = [0 => $data, 1 => $block_data];
        return $tab;
    }

}