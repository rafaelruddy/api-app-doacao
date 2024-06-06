<?php

namespace App\Observers;

use App\Models\Achievement;
use App\Models\Donation;
use App\Models\Donator;

class DonationObserver
{
    public function created(Donation $doacao)
    {
        if ($doacao->status === 'concluded') {
            $this->checkAchievements($doacao->donator);
        }
    }

    public function updated(Donation $doacao)
    {
        if ($doacao->isDirty('status') && $doacao->status === 'concluded') {
            $this->checkAchievements($doacao->donator);
        }
    }

    protected function checkAchievements(Donator $donator)
    {
        $totalItems = $donator->donatedItemsQuantity();
        $conquistas = Achievement::all();

        foreach ($conquistas as $conquista) {
            if ($totalItems >= $conquista->items_quantity) {
                if (!$donator->achievements->contains($conquista->id)) {
                    $donator->conquistas()->attach($conquista->id);
                }
            }
        }
    }
}
