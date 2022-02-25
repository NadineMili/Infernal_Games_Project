<?php

namespace App\Form;

use App\Entity\Tournament;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

  

class TournamentType extends AbstractType 
{
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('Name')
                ->add('Description')
                ->add('Game')
                ->add('countTeams')
                ->add('prize')
                ->add('picture', FileType::class, [
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/pdf'
                            ],
                            'mimeTypesMessage' => 'Please upload a png or jpeg image',
                        ])
                    ]
                ])
            
            ;
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }
}
