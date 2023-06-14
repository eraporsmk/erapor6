<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @role('waka', session('semester_id'))
                    @include('livewire.formulir-waka')
                @endrole
                @if($show)
                    @include('livewire.laporan.nilai-ekstrakurikuler-pd')
                @endif
            </div>
        </div>
    </div>
    @include('components.loader')
</div>
