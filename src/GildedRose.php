<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    public function __construct(private array $items) {}

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->updateSingleItem($item);
        }
    }

    private function updateSingleItem(Item $item): void
    {
        foreach ($this->items as $item) {
            // 1. Reducir SellIn primero (Excepto Sulfuras)
            if ($item->name !== 'Sulfuras, Hand of Ragnaros') {
                $item->sellIn--;
            }
        
            // 2. Determinar el cambio de calidad según el tipo y tiempo
            switch (true) {
                case ($item->name === 'Aged Brie'):
                    $item->quality += ($item->sellIn < 0) ? 2 : 1;
                    break;
        
                case ($item->name === 'Sulfuras, Hand of Ragnaros'):
                    // Sulfuras es legendario, no se altera
                    break;
        
                case ($item->name === 'Backstage passes to a TAFKAL80ETC concert'):
                    if ($item->sellIn < 0) {
                        $item->quality = 0;
                    } elseif ($item->sellIn < 5) {
                        $item->quality += 3;
                    } elseif ($item->sellIn < 10) {
                        $item->quality += 2;
                    } else {
                        $item->quality += 1;
                    }
                    break;
        
                case str_contains($item->name, 'Conjured'):
                    $item->quality -= ($item->sellIn < 0) ? 4 : 2;
                    break;
        
                default: // Items normales
                    $item->quality -= ($item->sellIn < 0) ? 2 : 1;
                    break;
            }
        
            // 3. Aplicar límites de calidad (0 - 50)
            if ($item->name !== 'Sulfuras, Hand of Ragnaros') {
                if ($item->quality > 50) $item->quality = 50;
                if ($item->quality < 0)  $item->quality = 0;
            }
        }
    }
}