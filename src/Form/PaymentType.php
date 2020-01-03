<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;

class PaymentType extends AbstractType
{
    /** @var UrlGeneratorInterface $router */
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->router->generate('confirm_payment'))
            ->add('card', CardType::class, ['required' => true])
            ->add('amount', HiddenType::class, ['required' => true], ['constraints' => [new Length(['value' => 3])]])
            ->add('currency', HiddenType::class, ['required' => true])
            ->add('confirm', SubmitType::class)
        ;
    }
}