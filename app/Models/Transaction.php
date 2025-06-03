<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice',
        'user_id',
        'fid_member',
        'point',
        'point_after',
        'cut',
        'total_price',
        'total_price_after',
        'cash',
        'change',
        'transaction_date',
    ];

    // Relasi: Transaksi dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Transaksi dimiliki oleh satu member (jika ada)
    public function member()
    {
        return $this->belongsTo(Member::class, 'fid_member', 'id_member');
    }

    // Relasi: Transaksi memiliki banyak detail
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
