<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueEntrySolo extends Model
{
    use HasFactory;

    protected $primaryKey = 'leagueId';
    public $incrementing = false;
    protected $keyType = 'string';
}
