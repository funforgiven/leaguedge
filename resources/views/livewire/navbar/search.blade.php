<div class="flex gap-2 items-center">
    <div x-data="{ open: false }" class="dropdown dropdown-bottom dropdown-end">
        <button x-on:click="open = true" x-text="$wire.region" class="btn btn-accent btn-outline">TR</button>
        <ul x-show="open" x-on:click.outside="open = false" class="dropdown-content menu p-2 gap-1 shadow rounded-box w-52">
            <li><button x-on:click="$wire.set('region', 'na')">North America</button></li>
            <li><button x-on:click="$wire.set('region', 'euw')">Europe West</button></li>
            <li><button x-on:click="$wire.set('region', 'eune')">Europe Nordic & East</button></li>
            <li><button x-on:click="$wire.set('region', 'kr')">Korea</button></li>
            <li><button x-on:click="$wire.set('region', 'br')">Brazil</button></li>
            <li><button x-on:click="$wire.set('region', 'jp')">Japan</button></li>
            <li><button x-on:click="$wire.set('region', 'ru')">Russia</button></li>
            <li><button x-on:click="$wire.set('region', 'oce')">Oceania</button></li>
            <li><button x-on:click="$wire.set('region', 'tr')">Türkiye</button></li>
            <li><button x-on:click="$wire.set('region', 'lan')">LAN</button></li>
            <li><button x-on:click="$wire.set('region', 'las')">LAS</button></li>
        </ul>
    </div>
    <div class="form-control">
        <input wire:model="name" wire:keydown.enter="search" type="text" placeholder="Search summoner..." class="input input-accent input-bordered" />
    </div>
    <button wire:click="search" class="btn btn-ghost">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
    </button>
</div>
