<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignAddress extends Model
{
    use HasFactory;

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

}
