<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $primaryKey = 'gameId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];
    public $timestamps = false;

    public function participants()
    {
        return $this->hasMany(Participant::class, 'gameId', 'gameId')->with('summoner');
    }
}
