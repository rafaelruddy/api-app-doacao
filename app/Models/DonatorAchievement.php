<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonatorAchievement extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'donator_achievements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'donator_id',
        'achievement_id',
    ];

    /**
     * Get the donator that owns the donator_achievement.
     */
    public function donator()
    {
        return $this->belongsTo(Donator::class);
    }

    /**
     * Get the achievement that owns the donator_achievement.
     */
    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
