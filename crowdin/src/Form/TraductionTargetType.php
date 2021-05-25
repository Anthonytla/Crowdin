<?php

namespace App\Form;

use App\Entity\Lang;
use App\Entity\Project;
use App\Entity\TraductionSource;
use App\Entity\TraductionTarget;
use App\Entity\User;
use App\Repository\TraductionSourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraductionTargetType extends AbstractType
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $project = $options['project'];
        $langs = $user->getLangs();
        $tab = [];
        foreach ($langs as $lang)
            $tab[$lang->getCode()] = $lang;

        $builder
            ->add('source_target', TextareaType::class, [
                'mapped' => false,
                'label' => 'Content for translation',
            ])
            ->add('source', EntityType::class, [
                'class' => TraductionSource::class,
                'choice_label' => 'source',
                'query_builder' => function (TraductionSourceRepository $tr) use ($project) {
                    return $tr->createQueryBuilder("p")->where('p.project = :project')
                        ->setParameter('project', $project);
                },
            ])
            ->add('lang', ChoiceType::class, [
                'choices' => $tab,
                'label' => 'Language'
            ])
            ->add('target', TextareaType::class, [
                'label' => 'Translated content'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TraductionTarget::class,
        ]);
        $resolver->setRequired('project', Project::class);
        $resolver->setRequired('user', User::class);
    }
}
