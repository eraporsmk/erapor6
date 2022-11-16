<div>
    @include('panels.breadcrumb')
    <div class="content-body">
        <div class="card">
            <form wire:ignore.self wire:submit.prevent="store">
            <div class="card-body">
                @include('livewire.formulir-kurmer')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary {{($show) ? '' : 'd-none'}}">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
