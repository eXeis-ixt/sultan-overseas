<?php

namespace App\Filament\Resources\PassportResource\Pages;

use App\Filament\Resources\PassportResource;
use App\Models\Passport;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePassport extends CreateRecord
{
    protected static string $resource = PassportResource::class;


}
