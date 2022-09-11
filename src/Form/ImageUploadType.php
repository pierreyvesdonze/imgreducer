<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class ImageUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image', FileType::class, [
            'label'    => 'Charger une image',
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
                        'image/png',
                        'image/gif',
                        'image/webp'
                    ],
                ])
            ],
        ]);

        $builder->add('width', IntegerType::class, [
            'required' => true,
            'label'    => "Entrer la largeur désirée",
            'attr' => [
                'min' => 24,
                'max' => 1920
            ]
            ]);

        $builder->add(
            'save',
            SubmitType::class,
            [
                "label" => "Uploader",
                'attr' => [
                    'class' => 'custom-btn',
                ],
            ]
        );
    }
}
