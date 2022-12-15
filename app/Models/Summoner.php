<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

class Summoner extends Model
{
    use Searchable;

    protected $primaryKey = 'puuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'participants', 'puuid', 'gameId');
    }

    public function leagueEntrySolo()
    {
        return $this->hasOne(LeagueEntrySolo::class, 'summonerId', 'summonerId');
    }

    public function leagueEntryFlex()
    {
        return $this->hasOne(LeagueEntryFlex::class, 'summonerId', 'summonerId');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    #[SearchUsingPrefix(['name'])]
    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
        ];
    }
}
