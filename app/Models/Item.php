<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status'
    ];

    const STATUS = [
        'active' => 'Ativo',
        'inactive' => 'Inativo',
    ];

    public function donations()
    {
        return $this->belongsToMany(Donation::class, 'donated_items')->withPivot('quantity');
    }
}
