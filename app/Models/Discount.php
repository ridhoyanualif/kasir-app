<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'name',
        'description',
        'cut',
        'start_datetime',
        'end_datetime',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    // Relasi ke produk (jika satu diskon bisa banyak produk)
    public function products()
    {
        return $this->hasMany(Product::class, 'fid_discount');
    }
}
