<div class="content-header row" wire:ignore>
  <div class="content-header-left col-md-9 col-12 mb-2">
    <div class="row breadcrumbs-top">
      <div class="col-12">
        <h2 class="content-header-title float-start mb-0">
          @yield('title')
        </h2>
        <div class="breadcrumb-wrapper">
          @if(@isset($breadcrumbs))
          <ol class="breadcrumb">
              {{-- this will load breadcrumbs dynamically from controller --}}
              @foreach ($breadcrumbs as $breadcrumb)
              <li class="breadcrumb-item">
                  @if(isset($breadcrumb['link']))
                  <a href="{{ $breadcrumb['link'] == 'javascript:void(0)' ? $breadcrumb['link']:url($breadcrumb['link']) }}">
                      @endif
                      {{$breadcrumb['name']}}
                      @if(isset($breadcrumb['link']))
                  </a>
                  @endif
              </li>
              @endforeach
          </ol>
          @endisset
        </div>
      </div>
    </div>
  </div>
  @if(isset($tombol_add))
    <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
      <div class="mb-1 breadcrumb-right">
    @if(is_array($tombol_add))
      @if($tombol_add['wire'])
        <button 
        @if(isset($tombol_add['attributes']))
        @foreach ($tombol_add['attributes'] as $attribute)
            {{$attribute}}
        @endforeach
        @endif
        type="button" class="btn btn-{{$tombol_add['color']}}" wire:click="{{$tombol_add['wire']}}">{{$tombol_add['text']}}</button>
      @else 
        <a class="btn btn-{{$tombol_add['color']}}" href="{{$tombol_add['link']}}">{{$tombol_add['text']}}</a>
      @endif
    @else
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Data</button>
    @endif
      </div>
    </div>
  @endif
</div>
