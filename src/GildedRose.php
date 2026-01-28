<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {

            if (str_contains($item->name, 'Conjured')) {
                $this->updateConjuredItem($item);
                continue; // Saltar al siguiente item
            }

            if ($item->name != 'Aged Brie' and $item->name != 'Backstage passes to a TAFKAL80ETC concert') {
                if ($item->quality > 0) {
                    if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                        $item->quality = $item->quality - 1;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                    if ($item->name == 'Backstage passes to a TAFKAL80ETC concert') {
                        if ($item->sellIn < 11) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                        if ($item->sellIn < 6) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                    }
                }
            }

            if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                $item->sellIn = $item->sellIn - 1;
            }

            if ($item->sellIn < 0) {
                if ($item->name != 'Aged Brie') {
                    if ($item->name != 'Backstage passes to a TAFKAL80ETC concert') {
                        if ($item->quality > 0) {
                            if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                                $item->quality = $item->quality - 1;
                            }
                        }
                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                }
            }
        }
    }

    private function updateConjuredItem(Item $item): void
    {
        // Sulfuras no se degrada, incluso si es "Conjured"
        if ($item->name == 'Sulfuras, Hand of Ragnaros') {
            return;
        }

        // Disminuir sellIn para conjured
        $item->sellIn = $item->sellIn - 1;
        
        // Degrada al DOBLE de velocidad
        $decreaseAmount = 2;
        
        // Si ya pasó la fecha, degrada DOBLE otra vez (total 4)
        if ($item->sellIn < 0) {
            $decreaseAmount = 4;
        }
        
        // Aplicar disminución de calidad
        $this->decreaseQuality($item, $decreaseAmount);
        
        // NOTA: No necesitamos la lógica adicional del método original
        // porque ya manejamos todo aquí
    }
    
    private function decreaseQuality($item, $amount) {
        if ($item->quality > 0) {
            $item->quality = $item->quality - $amount;
            if ($item->quality < 0) {
                $item->quality = 0;
            }
        }
    }
}
