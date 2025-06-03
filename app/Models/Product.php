<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Menentukan nama tabel (optional jika nama tabel sudah sesuai konvensi)
    protected $table = 'products';
    protected $primaryKey = 'id_product';

    // Menentukan kolom yang boleh diisi (fillable)
    protected $fillable = [
        'barcode',
        'name',
        'photo',
        'expired_date',
        'stock',
        'modal',
        'selling_price',
        'profit',
        'fid_category',
        'description',
    ];

    // Relasi ke Category (one-to-many / many-to-one)
    public function category()
    {
        return $this->belongsTo(Category::class, 'fid_category', 'id_category');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'product_id', 'id_product');
    }
    

}
