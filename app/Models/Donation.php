<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'donator_id',
        'date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the campaign that owns the donation.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the donator that owns the donation.
     */
    public function donator()
    {
        return $this->belongsTo(Donator::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'donated_items')->withPivot('quantity');
    }

    public function scopeConcluded($query)
    {
        return $query->where('status', 'concluded');
    }

}
