<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $primaryKey = 'gameId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function summoners()
    {
        return $this->belongsToMany(Summoner::class, 'participants', 'gameId', 'puuid')
            ->withPivot(
                'championId',
            );
    }
}
