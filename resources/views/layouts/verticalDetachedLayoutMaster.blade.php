<body class="vertical-layout vertical-menu-modern {{$configData['contentLayout']}} {{$configData['blankPageClass']}} {{ $configData['bodyClass']}} {{$configData['verticalMenuNavbarType']}} {{$configData['sidebarClass']}} {{$configData['footerType']}}"
data-open="click"
data-menu="vertical-menu-modern"
data-col="{{$configData['showMenu'] ? $configData['contentLayout'] : '1-column' }}"
data-framework="laravel"
data-asset-path="{{ asset('/')}}">

  {{-- Include Sidebar --}}
  @if((isset($configData['showMenu']) && $configData['showMenu'] === true))
  @include('panels.sidebar')
  @endif

  {{-- Include Navbar --}}
  @include('panels.navbar')

  <!-- BEGIN: Content-->
  <div class="app-content content {{ $configData['pageClass'] }}">
    <!-- BEGIN: Header-->
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>

    <div class="content-wrapper {{ $configData['layoutWidth'] === 'boxed' ? 'container-xxl p-0' : '' }}">
      {{-- Include Breadcrumb --}}
      @if($configData['pageHeader'] == true) @include('panels.breadcrumb') @endif

      <div class="{{ $configData['contentsidebarClass'] }}">
        <div class="content-body">
          {{-- Include Page Content --}}
          @yield('content')
        </div>
      </div>

      <div class="{{ $configData['sidebarPositionClass'] }}">
        <div class="sidebar">
          {{-- Include Sidebar Content --}}
          @yield('content-sidebar')
        </div>
      </div>
    </div>
  </div>
  <!-- End: Content-->

  <div class="sidenav-overlay"></div>
  <div class="drag-target"></div>

  {{-- include footer --}}
  @include('panels/footer')

  {{-- include default scripts --}}
  @include('panels/scripts')

  <script type="text/javascript">
    $(window).on('load', function() {
      if (feather) {
        feather.replace({
          width: 14,
          height: 14
        })
      }
    })
  </script>
</body>
</html>
