<?php

namespace App\Http\Livewire;

use App\Http\Controllers\GameController;
use App\Models\Summoner;
use Livewire\Component;
use Livewire\WithPagination;

class GameList extends Component
{
    use WithPagination;
    public Summoner $summoner;

    public $perPage = 5;
    public $pageCount = 1;
    public $games;

    public function render()
    {
        $this->games = $this->summoner->games;

        return view('livewire.game-list', [
            'games' => $this->games,
        ]);
    }

    public function showMore()
    {
        $this->pageCount++;

        if($this->games->count() < $this->perPage * ($this->pageCount+1))
        {
            GameController::createMoreGames($this->summoner, $this->perPage);
        }
    }
}
