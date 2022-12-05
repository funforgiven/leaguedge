<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $puuid
 * @property mixed|string $accountId
 * @property mixed|string $summonerId
 * @property int|mixed $profileIconId
 * @property int|mixed $revisionDate
 * @property mixed|string $name
 * @property int|mixed $summonerLevel
 * @property string|mixed $region
 */
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
