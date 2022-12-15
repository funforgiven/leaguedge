<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $primaryKey = 'participantId';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $guarded = [];

    public function summoner()
    {
        return $this->belongsTo(Summoner::class, 'puuid', 'puuid');
    }
}
