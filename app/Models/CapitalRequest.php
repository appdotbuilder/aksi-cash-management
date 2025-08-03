<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CapitalRequest
 *
 * @property int $id
 * @property string $request_code
 * @property int $outlet_user_id
 * @property string $amount
 * @property string $purpose
 * @property string $status
 * @property int|null $operator_user_id
 * @property \Illuminate\Support\Carbon|null $operator_approved_at
 * @property string|null $operator_notes
 * @property int|null $finance_user_id
 * @property \Illuminate\Support\Carbon|null $finance_approved_at
 * @property string|null $finance_notes
 * @property \Illuminate\Support\Carbon|null $disbursed_at
 * @property \Illuminate\Support\Carbon|null $rejected_at
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $outlet
 * @property-read \App\Models\User|null $operator
 * @property-read \App\Models\User|null $finance
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereDisbursedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereFinanceApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereFinanceNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereFinanceUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereOperatorApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereOperatorNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereOperatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereOutletUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereRequestCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CapitalRequest pending()
 * @method static \Database\Factories\CapitalRequestFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class CapitalRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'request_code',
        'outlet_user_id',
        'amount',
        'purpose',
        'status',
        'operator_user_id',
        'operator_approved_at',
        'operator_notes',
        'finance_user_id',
        'finance_approved_at',
        'finance_notes',
        'disbursed_at',
        'rejected_at',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'operator_approved_at' => 'datetime',
        'finance_approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the outlet that requested capital.
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(User::class, 'outlet_user_id');
    }

    /**
     * Get the operator who gave initial approval.
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_user_id');
    }

    /**
     * Get the finance user who gave final approval.
     */
    public function finance(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finance_user_id');
    }

    /**
     * Scope a query to only include pending requests.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Generate a unique request code.
     *
     * @return string
     */
    public static function generateRequestCode(): string
    {
        do {
            $randomNumber = random_int(1, 9999);
            $code = 'CAP-' . date('Ymd') . '-' . str_pad((string) $randomNumber, 4, '0', STR_PAD_LEFT);
        } while (self::where('request_code', $code)->exists());

        return $code;
    }
}