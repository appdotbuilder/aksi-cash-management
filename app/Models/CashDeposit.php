<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CashDeposit
 *
 * @property int $id
 * @property string $deposit_code
 * @property int $outlet_user_id
 * @property string $amount
 * @property string|null $description
 * @property string $status
 * @property int|null $sales_user_id
 * @property \Illuminate\Support\Carbon|null $sales_approved_at
 * @property string|null $sales_notes
 * @property int|null $operator_user_id
 * @property \Illuminate\Support\Carbon|null $operator_approved_at
 * @property string|null $operator_notes
 * @property int|null $depositor_user_id
 * @property int|null $finance_user_id
 * @property \Illuminate\Support\Carbon|null $finance_approved_at
 * @property string|null $finance_notes
 * @property \Illuminate\Support\Carbon|null $rejected_at
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $outlet
 * @property-read \App\Models\User|null $sales
 * @property-read \App\Models\User|null $operator
 * @property-read \App\Models\User|null $depositor
 * @property-read \App\Models\User|null $finance
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereDepositCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereFinanceApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereFinanceNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereFinanceUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereOperatorApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereOperatorNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereOperatorUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereOutletUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereSalesApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereSalesNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereSalesUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDeposit pending()
 * @method static \Database\Factories\CashDepositFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class CashDeposit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'deposit_code',
        'outlet_user_id',
        'amount',
        'description',
        'status',
        'sales_user_id',
        'sales_approved_at',
        'sales_notes',
        'operator_user_id',
        'operator_approved_at',
        'operator_notes',
        'depositor_user_id',
        'finance_user_id',
        'finance_approved_at',
        'finance_notes',
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
        'sales_approved_at' => 'datetime',
        'operator_approved_at' => 'datetime',
        'finance_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the outlet that initiated the deposit request.
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(User::class, 'outlet_user_id');
    }

    /**
     * Get the sales representative who verified the deposit.
     */
    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_user_id');
    }

    /**
     * Get the operator who approved the deposit.
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_user_id');
    }

    /**
     * Get the depositor assigned to handle the deposit.
     */
    public function depositor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'depositor_user_id');
    }

    /**
     * Get the finance user who performed final reconciliation.
     */
    public function finance(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finance_user_id');
    }

    /**
     * Scope a query to only include pending deposits.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Generate a unique deposit code.
     *
     * @return string
     */
    public static function generateDepositCode(): string
    {
        do {
            $randomNumber = random_int(1, 9999);
            $code = 'DEP-' . date('Ymd') . '-' . str_pad((string) $randomNumber, 4, '0', STR_PAD_LEFT);
        } while (self::where('deposit_code', $code)->exists());

        return $code;
    }
}