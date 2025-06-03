<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Menentukan nama tabel (optional jika nama tabel sudah sesuai konvensi)
    protected $table = 'categories';
    protected $primaryKey = 'id_category'; // Pastikan primary key sesuai dengan database
    public $timestamps = true; // Jika menggunakan timestamps (created_at & updated_at)

    // Menentukan kolom yang boleh diisi (fillable)
    protected $fillable = [
        'name',
    ];

    // Relasi ke Product (one-to-many)
    public function products()
    {
        return $this->hasMany(Product::class, 'fid_category', 'id_category');
    }
}


