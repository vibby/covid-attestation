<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttestationForm extends AbstractType
{
    private Justifications $justifications;

    public function __construct(Justifications $justifications)
    {
        $this->justifications = $justifications;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('justifications', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'Motif de sortie',
                'choices' => $this->justifications->getChoices(),
            ])
            ->add('userData', UserForm::class, [
                'label' => false,
                'attr' => ['style' => 'display:none;'],
            ])
            ->add('offset', ChoiceType::class, [
                'label' => 'Décalage',
                'choices' => ['Maintenant' => 0, '10 minutes' => 10, '20 minutes'  => 20, ],
            ])
            ->add('save', SubmitType::class, [
                'label' => "Générer votre attestation",
            ]);

        $builder->get('offset')
            ->addModelTransformer(new CallbackTransformer(
                function ($offsetAsInterval) {
                    // transform the array to a string
                    return $offsetAsInterval ? $offsetAsInterval->format('%i') : '';
                },
                function ($offsetAsString) {
                    // transform the string back to an array
                    return new \DateInterval(sprintf('PT%dM', $offsetAsString));
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AttestationCommand::class,
        ]);
    }
}
