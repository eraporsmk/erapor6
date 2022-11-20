<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <div class="card-body">
                @role('waka', session('semester_id'))
                    @include('livewire.formulir-waka')
                @endrole
                @if($show)
                    @include('livewire.laporan.rapor-semester-pd')
                @endif
            </div>
        </div>
    </div>
    @include('livewire.laporan.modal.review_nilai')
    @include('components.loader')
</div>
@push('scripts')
<script>
    Livewire.on('preview-nilai', event => {
        $('#reviewModal').modal('show')
    })
</script>
@endpush
