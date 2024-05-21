<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonatedItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'donation_id',
        'item_id',
        'quantity',
    ];

    /**
     * Get the donation that owns the donated item.
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Get the item that was donated.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
