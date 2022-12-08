<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $primaryKey = 'gameId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function participants()
    {
        return $this->hasMany(Participant::class, 'gameId', 'gameId');
    }
}
