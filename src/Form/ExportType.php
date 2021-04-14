<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Export from date:',
                'html5' => false,
                'attr' => [
                    'id' => 'dateFrom',
                ]
            ])
            ->add('dateTo', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Export to date:',
                'html5' => false,
                'attr' => [
                    'id' => 'dateTo',
                ]
            ])
            ->add('type', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices'  => [
//                    'PDF' => 'pdf',
                    'CSV' => 'csv',
                    'Excel' => 'xlsx',
                ],
            ])
            ->add('export', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-secondary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
