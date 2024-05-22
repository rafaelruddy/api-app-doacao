<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NecessaryItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'item_id',
        'quantity_objective'
    ];

    /**
     * Get the campaign that owns the necessary item.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the item that is necessary for the campaign.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function total_donated()
    {
        return (int) $this->campaign->donations()
                    ->join('donated_items', 'donations.id', '=', 'donated_items.donation_id')
                    ->where('donated_items.item_id', $this->item->id)
                    ->sum('donated_items.quantity');
    }
}
