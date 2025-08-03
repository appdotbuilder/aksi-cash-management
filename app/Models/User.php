<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $role
 * @property string|null $outlet_code
 * @property bool $is_active
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashDeposit> $outletDeposits
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashDeposit> $salesDeposits
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashDeposit> $operatorDeposits
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashDeposit> $depositorDeposits
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashDeposit> $financeDeposits
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalRequest> $outletCapitalRequests
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalRequest> $operatorCapitalRequests
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CapitalRequest> $financeCapitalRequests
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOutletCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \Illuminate\Database\Eloquent\Builder|User byRole($role)
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'outlet_code',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get cash deposits initiated by this outlet.
     */
    public function outletDeposits(): HasMany
    {
        return $this->hasMany(CashDeposit::class, 'outlet_user_id');
    }

    /**
     * Get cash deposits verified by this sales rep.
     */
    public function salesDeposits(): HasMany
    {
        return $this->hasMany(CashDeposit::class, 'sales_user_id');
    }

    /**
     * Get cash deposits approved by this operator.
     */
    public function operatorDeposits(): HasMany
    {
        return $this->hasMany(CashDeposit::class, 'operator_user_id');
    }

    /**
     * Get cash deposits assigned to this depositor.
     */
    public function depositorDeposits(): HasMany
    {
        return $this->hasMany(CashDeposit::class, 'depositor_user_id');
    }

    /**
     * Get cash deposits reconciled by this finance user.
     */
    public function financeDeposits(): HasMany
    {
        return $this->hasMany(CashDeposit::class, 'finance_user_id');
    }

    /**
     * Get capital requests initiated by this outlet.
     */
    public function outletCapitalRequests(): HasMany
    {
        return $this->hasMany(CapitalRequest::class, 'outlet_user_id');
    }

    /**
     * Get capital requests approved by this operator.
     */
    public function operatorCapitalRequests(): HasMany
    {
        return $this->hasMany(CapitalRequest::class, 'operator_user_id');
    }

    /**
     * Get capital requests approved by this finance user.
     */
    public function financeCapitalRequests(): HasMany
    {
        return $this->hasMany(CapitalRequest::class, 'finance_user_id');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Get the display name for the user's role.
     *
     * @return string
     */
    public function getRoleDisplayAttribute(): string
    {
        return match($this->role) {
            'outlet' => 'Outlet',
            'sales' => 'Sales Representative',
            'operator' => 'Operator',
            'penyetor' => 'Depositor',
            'finance' => 'Finance',
            'admin' => 'Administrator',
            default => 'Unknown',
        };
    }
}