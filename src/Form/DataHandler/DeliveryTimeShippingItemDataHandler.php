<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Form\DataHandler;

use AwChristmasCalendar\Entity\ChristmasCalendarItem;
use AwChristmasCalendar\Entity\ChristmasCalendarItemLang;
use AwChristmasCalendar\Handler\File\FileEraser;
use AwChristmasCalendar\Handler\File\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShopBundle\Entity\Repository\LangRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WeboDeliveryTime\Entity\WeboDeliveryTimeShipping;

class DeliveryTimeShippingItemDataHandler implements FormDataHandlerInterface
{
    /**
     * @var EntityRepository
     */
    private $deliveryTimeProductRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(
        EntityRepository $deliveryTimeProductRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->deliveryTimeProductRepository = $deliveryTimeProductRepository;
        $this->entityManager = $entityManager;
    }

    public function create(array $data)
    {
        $deliveryTimeShippingItem = new WeboDeliveryTimeShipping();

        $deliveryTimeShippingItem->setDeliveryTime((int) $data['id_carrier_reference']);
        $deliveryTimeShippingItem->setIdCarrierReference((int) $data['delivery_time']);

        $this->entityManager->persist($deliveryTimeShippingItem);
        $this->entityManager->flush();

        return $deliveryTimeShippingItem->getId();
    }

    public function update($id, array $data)
    {
        $deliveryTimeShippingItem = $this->deliveryTimeProductRepository->find($id);

        $deliveryTimeShippingItem->setDeliveryTime((int) $data['id_carrier_reference']);
        $deliveryTimeShippingItem->setIdCarrierReference((int) $data['delivery_time']);

        $this->entityManager->flush();
        return $deliveryTimeShippingItem->getId();
    }
}