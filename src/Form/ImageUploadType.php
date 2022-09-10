<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class ImageUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', FileType::class, [
            'label'    => 'Ajouter une image',
            'multiple' => false,
            'mapped'   => false,
            'required' => true,
            'attr'     => [
                'class' => 'add-img',
            ],
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/jpg',
                        'image/png'
                    ],
                ])
            ],
        ]);

        $builder->add('width', ChoiceType::class, [
            'required' => true,
            'label'    => "Choisissez la largeur de l'image souhaitée",
            'choices' => [
                '1920p' => '1920',
                '1280p' => '1280',
                '1024p' => '1024',
                '800p'  => '800',
                '768p'  => '768',
                '720p'  => '720',
                '640p'  => '640',
                '320p'  => '320'
            ]
            ]);

        $builder->add(
            'save',
            SubmitType::class,
            [
                "label" => "Télécharger",
                'attr' => [
                    'class' => 'custom-btn dl-btn',
                ],
            ]
        );
    }
}
