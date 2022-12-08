<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summoner extends Model
{
    use HasFactory;

    protected $primaryKey = 'puuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'participants', 'puuid', 'gameId');
    }
}
