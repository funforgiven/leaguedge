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
