<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $primaryKey = 'participantId';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $guarded = [];

    public function summoner()
    {
        return $this->belongsTo(Summoner::class, 'puuid', 'puuid');
    }
}
