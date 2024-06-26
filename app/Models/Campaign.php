<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Campaign extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'donation_start_time',
        'donation_end_time',
        'items_quantity_objective',
        'institution_id',
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
     * Get the institution that owns the campaign.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function addressess(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'campaign_addresses');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function necessary_items()
    {
        return $this->hasMany(NecessaryItem::class);
    }

    public function donatedItemsObjective()
    {
        return (int) $this->necessary_items()
                        ->sum('quantity_objective');
    }

    public function currentDonationQuantity()
    {
        return (int) $this->donations()
                    ->join('donated_items', 'donations.id', '=', 'donated_items.donation_id')
                    ->sum('donated_items.quantity');
    }
}
