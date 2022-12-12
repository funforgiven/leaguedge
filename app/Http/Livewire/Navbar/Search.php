<?php

namespace App\Http\Livewire\Navbar;

use App\Models\Summoner;
use Livewire\Component;

class Search extends Component
{
    public string $region = "tr";
    public string $name = "";

    public function render()
    {
        return view('livewire.navbar.search', [
            'results' => Summoner::search($this->name)->take(5)->get(),
        ]);
    }

    public function search()
    {
        $this->redirect("/lol/summoner/{$this->region}/{$this->name}");
    }
}
