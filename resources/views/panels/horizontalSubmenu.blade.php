{{-- For Horizontal submenu --}}
<ul class="dropdown-menu" data-bs-popper="none">
  @if(isset($menu))
  @foreach($menu as $submenu)
  @php
  $custom_classes = "";
  if(isset($submenu->classlist)) {
    $custom_classes = $submenu->classlist;
  }
  @endphp

  <li class="{{ $custom_classes }} {{ (isset($submenu->submenu)) ? 'dropdown dropdown-submenu' : '' }} {{ $submenu->slug === Route::currentRouteName() ? 'active' : '' }}" @if(isset($submenu->submenu)){{'data-menu=dropdown-submenu'}}@endif>
    <a href="{{isset($submenu->url) ? url($submenu->url):'javascript:void(0)'}}" class="dropdown-item {{ (isset($submenu->submenu)) ? 'dropdown-toggle' : ''}} d-flex align-items-center"
      {{ (isset($submenu->submenu)) ? 'data-bs-toggle=dropdown' : '' }} target="{{isset($submenu->newTab) && $submenu->newTab === true  ? '_blank':'_self'}}">
      @if (isset($submenu->icon))
      <i data-feather="{{ $submenu->icon }}"></i>
      @endif
      <span>{{ __('locale.'.$submenu->name) }}</span>
    </a>
    @if (isset($submenu->submenu))
    @include('panels/horizontalSubmenu', ['menu' => $submenu->submenu])
    @endif
  </li>
  @endforeach
  @endif
</ul>
