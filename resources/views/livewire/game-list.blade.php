<div class="flex flex-col gap-4 justify-center items-center">
    @foreach ($games as $game)
        @php($currentParticipant = $game->participants->firstWhere('puuid', $summoner->puuid))
        <x-overview.game :$game :$currentParticipant></x-overview.game>
    @endforeach

    <div x-data="{
        observe () {
            let observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        @this.call('loadMore')
                    }
                })
            }, {
                root: null
            })
            observer.observe(this.$el)
        }
    }" x-init="observe">

    </div>

</div>
