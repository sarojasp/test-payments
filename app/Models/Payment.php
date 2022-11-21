<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    
    public function getKeyType()
    {
        return 'string';
    }

    protected $fillable = [
        'payment_date',
        'expires_at',
        'status',
        'client_id',
        'clp_usd'
    ];

    /**
     * Get the user associated with the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
