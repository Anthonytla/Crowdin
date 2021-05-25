<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\TraductionSource;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraductionSourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['userId'];
        $builder
            ->add('source', TextType::class, ['required' => true])
            ->add('project', EntityType::class, [
                'required' => true,
                'class' => Project::class,
                'placeholder' => 'Choose a project',
                'choice_label' => 'name',
                'query_builder' => function (ProjectRepository $er) use($user){
                    return $er->createQueryBuilder("p")
                        ->where("p.userId = :user")
                        ->setParameter('user', $user);
                },
            ])
            ->add('target', TextareaType::class, [
                'trim' => true
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TraductionSource::class,
        ]);
        $resolver->setRequired('userId', User::class);
    }
}
