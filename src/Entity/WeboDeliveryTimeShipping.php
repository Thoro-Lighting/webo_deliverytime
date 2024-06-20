<?php

namespace WeboDeliveryTime\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="WeboDeliveryTime\Repository\Doctrine\DeliveryTimeShippingRepository")
 * @ORM\Table(name="ps_webo_deliverytime_shipping")
 */
class WeboDeliveryTimeShipping
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idCarrierReference;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $deliveryTime;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCarrierReference(): ?int
    {
        return $this->idCarrierReference;
    }

    public function setIdCarrierReference(int $idCarrierReference): self
    {
        $this->idCarrierReference = $idCarrierReference;
        return $this;
    }

    public function getDeliveryTime(): ?int
    {
        return $this->deliveryTime;
    }

    public function setDeliveryTime(int $deliveryTime): self
    {
        $this->deliveryTime = $deliveryTime;
        return $this;
    }
}
