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
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
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
    public function donated_items()
    {
        return $this->hasMany(DonatedItem::class, 'donation_id');
    }

    public function scopeConcluded($query)
    {
        return $query->where('status', 'concluded');
    }

    const STATUS = [
        'agended' => 'Agendada',
        'canceled' => 'Cancelada',
        'concluded' => 'Concluída',
    ];

    const STATUS_COLOR = [
        'agended' => 'info',
        'canceled' => 'danger',
        'concluded' => 'success',
    ];

}
