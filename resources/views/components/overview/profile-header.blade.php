<div class="card gap-4 items-center">
    <div class="indicator gap-2">
        <span class="indicator-item indicator-bottom indicator-center badge border border-accent">{{ $summoner->summonerLevel }}</span>
        <div class="avatar">
            <div class="w-24 rounded-full ring ring-accent ring-offset-base-100 ring-offset-2">
                <img class="w-24 h-24" src="https://cdn.communitydragon.org/latest/profile-icon/{{ $summoner->profileIconId }}">
            </div>
        </div>
    </div>
    <div class="card-title">
        {{ $summoner->name }}
    </div>
</div>

