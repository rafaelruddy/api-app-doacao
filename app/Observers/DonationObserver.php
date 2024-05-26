<?php

namespace App\Observers;

use App\Models\Achievement;
use App\Models\Donation;

class DonationObserver
{
    public function created(Donation $doacao)
    {
        if ($doacao->status === 'concluded') {
            $this->checkAchievements($doacao->doador);
        }
    }

    public function updated(Donation $doacao)
    {
        if ($doacao->isDirty('status') && $doacao->status === 'concluded') {
            $this->checkAchievements($doacao->doador);
        }
    }

    protected function checkAchievements(Donation $doador)
    {
        $totalItems = $doador->donated_items_quantity();
        $conquistas = Achievement::all();

        foreach ($conquistas as $conquista) {
            if ($totalItems >= $conquista->items_quantity) {
                if (!$doador->achievements->contains($conquista->id)) {
                    $doador->conquistas()->attach($conquista->id);
                }
            }
        }
    }
}
