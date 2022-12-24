<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Asal;

class Admin extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function asal()
    {
        return $this->belongsTo(Asal::class);
    }
}
