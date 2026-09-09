<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    // The statuses table was created without created_at / updated_at columns,
    // so Eloquent must not try to write them.
    public $timestamps = false;

    protected $fillable = ['status'];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
