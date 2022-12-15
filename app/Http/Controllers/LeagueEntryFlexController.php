<?php

namespace App\Http\Controllers;

use App\Models\LeagueEntryFlex;

class LeagueEntryFlexController extends Controller
{
    public static function createOrUpdateLeagueEntry($leagueEntryDto)
    {
        if(!$leagueEntryDto) return;

        LeagueEntryFlex::updateOrCreate([
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
