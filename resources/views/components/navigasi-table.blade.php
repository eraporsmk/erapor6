<div class="row justify-content-between mb-2">
  <div class="col-4">
      <div class="d-inline" wire:ignore>
          <select class="form-select" wire:model="per_page" wire:change="loadPerPage" data-pharaonic="select2" data-component-id="{{ $this->id }}" data-search-off="true">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
          </select>
      </div>
  </div>
  <div class="col-4">
      <input type="text" class="form-control" placeholder="Cari data..." wire:model="search">
  </div>
</div>