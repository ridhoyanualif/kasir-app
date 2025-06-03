<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $primaryKey = 'id_member';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'telephone',
        'point',
        'status',
    ];

    // Relasi: Seorang member punya banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'fid_member', 'id_member');
    }
}
