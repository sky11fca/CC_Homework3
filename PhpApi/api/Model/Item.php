<?php

namespace Model;

use Model\Enums\ItemQuality;

class Item
{
    private string $id;
    private string $name;
    private int $quantity;
    private ItemQuality $quality;
    private float $price;

    public function __construct(
        string $id,
        string $name,
        int $quantity,
        ItemQuality $quality,
        float $price
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->quality = $quality;
        $this->price = $price;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getQuality(): ItemQuality
    {
        return $this->quality;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}