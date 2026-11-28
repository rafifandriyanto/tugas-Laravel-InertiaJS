<?php

// nentuin namespace model di folder app/Models
namespace App\Models;

// narik class dasar Model Eloquent dari laravel
use Illuminate\Database\Eloquent\Model;

// model Post yang nyambung ke tabel posts di database
class Post extends Model
{
    // daftar kolom yang dibolehin buat diisi langsung barengan (mass assignment) kayak pas Post::create()
    protected $fillable = [
        // kolom judul postingan boleh diisi lewat input massal
        'title',
        // kolom isi konten postingan boleh diisi lewat input massal
        'content',
    ];
}
