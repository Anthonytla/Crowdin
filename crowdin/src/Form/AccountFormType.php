<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;

class AccountFormType extends AbstractType
{
    public function __construct(Security $security, EntityManagerInterface $em, ProjectRepository $projectrepository)
    {
        $this->security = $security;
        $this->em = $em;
        $this->pr = $projectrepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();
        $projects_2 = $user->getProjects();
        $tab = [];
        foreach ($projects_2 as $project) {
            $tab[$project->getName()] = $project->getId();
        }
        $builder
            ->add('username')
            ->add('email')
            ->add('languages', LanguageType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('projects', ChoiceType::class, [
                'choices' => $tab,
                'required' => false,
                'mapped' => false,
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Please enter your password',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('newPassword', PasswordType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Please enter your password',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'error' => false,
        ]);
    }
}
