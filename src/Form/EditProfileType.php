<?php

namespace App\Form;

use App\Controller\UserController;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class ,[
                'label'=>'Nom utilisateur',
            ])
            ->add('password',PasswordType::class, [
                'label'=>'Nouveau mot de passe',
                'required'=>false,
                "empty_data"=>''
            ])
            ->add('email', TextType::class, [
                'label'=>'Adresse email',
            ])
            ->add('description')
            ->add('photo')
            ->add('edit', SubmitType::class, [
                'label' => 'Sauvegarder les modifications'
            ])
            ->setMethod("POST")
            ->setAction('edit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
