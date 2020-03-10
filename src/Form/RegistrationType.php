<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('username')
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('picture', FileType::class, [
                'label' => 'Picture (JPG file)',


               // make it optional so you don't have to re-upload the PDF file
               // every time you edit the Product details
               'required' => false,

               // unmapped fields can't define their validation using annotations
               // in the associated entity, so you can use the PHP constraint classes
               'constraints' => [
                   new File([
                       'maxSize' => '1024k'
                       ])
               ],
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
