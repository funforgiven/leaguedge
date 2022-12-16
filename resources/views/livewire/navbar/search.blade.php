<div x-data="{ openRegion: false}" class="dropdown dropdown-bottom dropdown-start">
    <div x-data="{openSearch: false}" class="form-control">
        <div class="input-group bg-base-300 border border-accent rounded-lg">
            <button x-on:click="openRegion = true" x-text="$wire.region" class="btn btn-square">TR</button>
            <ul x-show="openRegion" x-on:click.outside="openRegion = false" class="dropdown-content border border-accent menu bg-base-300 rounded-box w-56 p-2 my-1 gap-1">
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'na')">North America</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'euw')">Europe West</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'eune')">Europe Nordic & East</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'kr')">Korea</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'br')">Brazil</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'jp')">Japan</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'ru')">Russia</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'oce')">Oceania</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'tr')">Türkiye</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'lan')">LAN</button></li>
                <li x-on:click="openRegion = false"><button x-on:click="$wire.set('region', 'las')">LAS</button></li>
            </ul>
            <div class="dropdown dropdown-bottom">
                <label x-on:click="openSearch = true">
                    <input wire:model="name" wire:keydown.enter="search" type="text" placeholder="Search summoner..." class="input" />
                </label>
                <ul x-show="$wire.name != '' && openSearch" x-on:click.outside="openSearch = false" class="dropdown-content border border-accent menu bg-base-300 rounded-box w-64 p-2 my-1 gap-1">
                    @if($results->isEmpty())
                        <li>
                            <a class="flex" href="/lol/summoner/{{ $region }}/{{ $name }}">
                                <img class="w-8 h-8" src="https://cdn.communitydragon.org/latest/profile-icon/1">
                                <div class="flex gap-1">
                                    {{$name}}
                                    ({{strtoupper($region)}})
                                </div>
                            </a>
                        </li>
                    @else
                        @foreach($results as $result)
                            <li>
                                <a class="flex" href="/lol/summoner/{{ $result->region }}/{{ $result->name }}">
                                    <img class="w-8 h-8" src="https://cdn.communitydragon.org/latest/profile-icon/{{ $result->profileIconId }}">
                                    <div class="flex gap-1">
                                        {{$result->name}}
                                        ({{strtoupper($result->region)}})
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <button wire:click="search" class="btn btn-square">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </button>
        </div>
    </div>
</div>
