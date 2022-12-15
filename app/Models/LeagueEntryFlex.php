<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueEntryFlex extends Model
{
    use HasFactory;

    protected $table = 'flex_league_entries';

    protected $primaryKey = 'leagueId';
    public $incrementing = false;
    protected $keyType = 'string';
}
