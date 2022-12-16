<?php

namespace App\Http\Controllers;

use App\Models\LeagueEntrySolo;
use Illuminate\Http\Request;
use RiotAPI\LeagueAPI\LeagueAPI;

class LeagueEntrySoloController extends Controller
{
    public static function createOrUpdateLeagueEntry($leagueEntryDto)
    {
        if(!$leagueEntryDto) return;

        LeagueEntrySolo::query()->updateOrCreate([
            'summonerId' => $leagueEntryDto->summonerId,
        ], [
            'leagueId' => $leagueEntryDto->leagueId,
            'tier' => $leagueEntryDto->tier,
            'rank' => $leagueEntryDto->rank,
            'lp' => $leagueEntryDto->leaguePoints,
            'wins' => $leagueEntryDto->wins,
            'losses' => $leagueEntryDto->losses,
        ]);
    }
}
