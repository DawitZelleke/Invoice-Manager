<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    // Same as Status: no timestamp columns exist on this table.
    public $timestamps = false;

    protected $fillable = ['number', 'client', 'email', 'amount', 'status_id'];

    protected $casts = [
        'amount'    => 'integer',
        'status_id' => 'integer',
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Limit the query to one status name. Anything other than a real status
     * name (including "all" and null) leaves the query untouched.
     */
    public function scopeOfStatus(Builder $query, ?string $status): Builder
    {
        if (! $status || $status === 'all') {
            return $query;
        }

        return $query->whereHas('status', fn (Builder $q) => $q->where('status', $status));
    }

    /**
     * Five uppercase letters, matching the format already in the database.
     */
    public static function generateNumber(): string
    {
        do {
            $number = '';
            for ($i = 0; $i < 5; $i++) {
                $number .= chr(random_int(65, 90));
            }
        } while (static::where('number', $number)->exists());

        return $number;
    }
}
