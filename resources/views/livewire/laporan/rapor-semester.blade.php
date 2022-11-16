<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @role('waka', session('semester_id'))
                @if($show)
                    @include('livewire.laporan.rapor-semester-pd')
                @endif
                @else
                @if($show)
                    @include('livewire.laporan.rapor-semester-pd')
                @endif
                @endrole
            </div>
        </div>
    </div>
</div>
