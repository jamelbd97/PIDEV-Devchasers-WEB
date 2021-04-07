<?php

namespace App\Form;

use App\Entity\EvenementZO;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('debut')
            ->add('fin')
            ->add('descp')
            ->add('all_day')
            ->add('id_user')
            ->add('captcha', CaptchaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EvenementZO::class,
        ]);
    }
}
