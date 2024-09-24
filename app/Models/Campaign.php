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
        'status',
        'start_date',
        'end_date',
        'donation_start_time',
        'donation_end_time',
        'institution_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'donation_start_time' => 'datetime:H:i',
        'donation_end_time' => 'datetime:H:i',
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

    public function donated_items()
    {
        return $this->hasManyThrough(
            DonatedItem::class,
            Donation::class,
            'campaign_id',
            'donation_id',
            'id',
            'id'
        );
    }

    public function necessary_items()
    {
        return $this->hasMany(NecessaryItem::class);
    }

}
