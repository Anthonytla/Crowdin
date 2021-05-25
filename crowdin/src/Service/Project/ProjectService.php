<?php

namespace App\Service\Project;

use App\Entity\Lang;
use App\Entity\TraductionSource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Security\Core\Security;

class ProjectService
{
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function createProject($project, $file)
    {
        $title = $file->getClientOriginalName();
        $dot_pos1 = strpos($title, '.');
        $dot_pos2 = strpos($title, '.', $dot_pos1 + 1);
        $code = substr($title, $dot_pos1 + 1, $dot_pos2 - 1 - $dot_pos1);
        $lang = new Lang();
        $lang->setCode($code);
        $lang->setName(Languages::getName($code));
        $project->setLang($lang);
        $csv_file = file($file);
        foreach ($csv_file as $line) {
            $data = str_getcsv($line, ';');
            $traduction = new TraductionSource();
            $traduction->setSource($data[0]);
            $traduction->setTarget($data[1]);
            $project->addSource($traduction);
        }
        $user = $this->security->getUser();
        $user->addProject($project);
        $this->em->persist($project);
        $this->em->flush();
    }

    public function updateProject()
    {
        $this->em->flush();
    }

    public function deleteProject($project)
    {
        $this->em->remove($project);
        $this->em->flush();
    }
}
