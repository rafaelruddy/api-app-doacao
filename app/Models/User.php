<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function institutions(): BelongsToMany {
        return $this->belongsToMany(Institution::class, 'institution_users');
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->hasRole(Utils::getSuperAdminName())) {
            return Institution::all();
        }

        return $this->institutions;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->hasRole(Utils::getSuperAdminName())) {
            return true;
        }

        return $this->institutions->contains($tenant);
    }

    public function canAccessFilament(): bool
    {
        return true;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole(Utils::getSuperAdminName()) || $this->hasRole('admin_user') || $this->hasRole('panel_user');
        }

        if ($panel->getId() === 'institution') {
            return $this->institutions()->exists() || $this->hasRole(Utils::getSuperAdminName());
        }

        return false;
    }

}
