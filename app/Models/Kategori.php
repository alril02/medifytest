<?php

namespace App\Models;

use App\Models\MasterItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode',
    ];

    public function masterItems()
    {
        return $this->belongsToMany(MasterItem::class, 'kategori_master_item', 'kategori_id', 'master_item_id');
    }
}
