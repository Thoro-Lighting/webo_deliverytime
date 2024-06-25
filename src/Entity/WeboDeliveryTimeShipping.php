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
    private $idCarrier;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $deliveryTime;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCarrier(): ?int
    {
        return $this->idCarrier;
    }

    public function setIdCarrier(int $idCarrier): self
    {
        $this->idCarrier = $idCarrier;
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
