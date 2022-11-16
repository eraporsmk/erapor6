<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
                <div class="card-body">
                    @role('waka', session('semester_id'))
                    @if($show)
                        @include('livewire.laporan.catatan-akademik-pd')
                    @endif
                    @else
                    @if($show)
                        @include('livewire.laporan.catatan-akademik-pd')
                    @endif
                    @endrole
                </div>
                <div class="card-footer{{($form) ? '' : ' d-none'}}">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
