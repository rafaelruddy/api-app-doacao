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
        'status'
    ];

    /**
     * Get the user that owns the institution.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }


    const STATUS = [
        'analysis' => 'Análise',
        'reproved' => 'Reprovado',
        'active' => 'Ativo',
        'inactive' => 'Inativo',
    ];
    const STATUS_COLOR = [
        'analysis' => 'info',
        'reproved' => 'danger',
        'active' => 'success',
        'inactive' => 'warning',
    ];
}
