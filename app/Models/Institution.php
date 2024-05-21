<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Institution extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'description',
        'latitude',
        'longitude',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
    ];

    /**
     * Get the user that owns the institution.
     */
    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }


    const STATUS = [
        'analysis' => 'Análise',
        'reprooved' => 'Reprovado',
        'active' => 'Ativo',
        'inactive' => 'Inativo',
    ];
    const STATUS_COLOR = [
        'analysis' => 'info',
        'reprooved' => 'danger',
        'active' => 'success',
        'inactive' => 'warning',
    ];
}
