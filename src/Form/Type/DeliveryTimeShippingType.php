<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Form\Type;

use Context;
use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use WeboDeliveryTime\ChoiceProvider\ShippingChoicesProvider;
use WeboDeliveryTime\Constraint\UniqueFieldConstraint;
use WeboDeliveryTime\Entity\WeboDeliveryTimeShipping;

class DeliveryTimeShippingType extends CommonAbstractType
{
    private $shippingChoicesProvider;
    private $context;

    public function __construct(
        ShippingChoicesProvider $shippingChoicesProvider,
        Context $context
    ) {
        $this->shippingChoicesProvider = $shippingChoicesProvider;
        $this->context = $context;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $shippingChoices = $this->shippingChoicesProvider->getShippingChoices($this->context->language->id);

        $data = $builder->getData();
        $isEditMode = !empty($data['id_carrier_reference']);

        $builder
            ->add('id_carrier_reference', ChoiceType::class, [
                'label' => 'Metoda dostawy',
                'choices' => $shippingChoices,
                'constraints' => [
                    new NotBlank(),
                    new UniqueFieldConstraint([
                        'entityClass' => WeboDeliveryTimeShipping::class,
                        'fieldName' => 'idCarrierReference',
                        'message' => 'Istnieje już zdefiniowany czas dostawy dla tego przewoźnika.',
                    ]),
                ],
                'disabled' => $isEditMode,
            ])
            ->add('delivery_time', TextType::class, [
                'label' => 'Czas wysyłki w dniach roboczych',
                'constraints' => [
                    new NotBlank(),
                    new Range([
                        'min' => 1,
                        'max' => 30,
                    ]),
                ],
            ]);
    }
}