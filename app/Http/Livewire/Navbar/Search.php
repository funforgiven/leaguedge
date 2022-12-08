<?php

namespace App\Http\Livewire\Navbar;

use Livewire\Component;

class Search extends Component
{
    public string $region = "tr";
    public string $name = "";

    public function render()
    {
        return view('livewire.navbar.search');
    }

    public function search()
    {
        $this->redirect("/lol/{$this->region}/{$this->name}");
    }
}
