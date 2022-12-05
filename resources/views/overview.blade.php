<x-layout>
    <x-slot:content>
        <div class="flex mx-auto my-5 box-border w-[1064px] h-auto ">
            <div class="flex flex-1 flex-col gap-4 justify-center items-center">
                <x-overview.profile-header :$summoner></x-overview.profile-header>
                @foreach($games as $game)
                    <x-overview.game :$game></x-overview.game>
                @endforeach
            </div>
        </div>
    </x-slot:content>
</x-layout>
