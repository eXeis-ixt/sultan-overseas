<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case New = 'new';

    case Processing = 'processing';

    case Medical = 'medical';
    case Embassy = 'embassy';

    case Delivered = 'delivered';


    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Processing => 'Processing',
            self::Embassy => 'Embassy',
            self::Delivered => 'Delivered',
            self::Medical => 'medical',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::New => 'info',
            self::Processing => 'warning',
            self::Medical => 'danger',
            self::Embassy, self::Delivered => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::New => 'heroicon-m-sparkles',
            self::Processing => 'heroicon-m-arrow-path',
            self::Embassy => 'heroicon-m-truck',
            self::Delivered => 'heroicon-m-check-badge',
            self::Medical => 'heroicon-m-home-modern',
        };
    }
}
