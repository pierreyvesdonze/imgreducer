<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class ImageUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', FileType::class, [
            'label'    => 'Ajouter des images',
            'multiple' => false,
            'mapped'   => false,
            'required' => false,
            'attr'     => [
                'class' => 'add-img-gallery',
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

        $builder->add(
            'save',
            SubmitType::class,
            [
                "label" => "Envoyer",
                'attr' => [
                    'class' => 'custom-button',
                ],
            ]
        );
    }
}
