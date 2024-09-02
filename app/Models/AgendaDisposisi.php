<?php

namespace App\Models;

use App\Models\Disposisi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaDisposisi extends Model
{
    use HasFactory;

    public function disposisi()
    {
        return $this->hasMany(Disposisi::class);
    }
}
