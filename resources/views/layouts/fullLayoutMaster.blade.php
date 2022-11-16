@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
@php $configData = Helper::applClasses(); @endphp

<html class="loading {{ ($configData['theme'] === 'light') ? '' : $configData['layoutTheme'] }}"
lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif"
data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}"
@if($configData['theme'] === 'dark') data-layout="dark-layout"@endif>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Aplikasi Penilaian dan Asesmen. Direktorat SMK">
  <meta name="keywords" content="erapor, smk, eraporsmk, erapor v6, erapor versi 6">
  <meta name="author" content="Direktorat SMK">
  <title>@yield('title') | {{config('app.name')}}</title>
  <!--link rel="apple-touch-icon" href="{{asset('images/ico/apple-icon-120.png')}}"-->
  <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/logo.png')}}">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

  {{-- Include core + vendor Styles --}}
  @include('panels/styles')
  <style>
    /*.blank-page{background:#d2d6de url(images/login-page.png)}*/
    .blank-page{background:#fff}
  </style>
</head>



<body class="vertical-layout vertical-menu-modern {{ $configData['bodyClass'] }} {{($configData['theme'] === 'dark') ? 'dark-layout' : ''}} {{ $configData['blankPageClass'] }} blank-page"
data-menu="vertical-menu-modern"
data-col="blank-page"
data-framework="laravel"
data-asset-path="{{ asset('/')}}">

  <!-- BEGIN: Content-->
  <div class="app-content content {{ $configData['pageClass'] }}">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>

    <div class="content-wrapper">
      <div class="content-body">

        {{-- Include Startkit Content --}}
        @yield('content')
      </div>
    </div>
  </div>
  <!-- End: Content-->

  {{-- include default scripts --}}
  @include('panels/scripts')

  <script type="text/javascript">
    $(window).on('load', function() {
      if (feather) {
        feather.replace({
          width: 14,
          height: 14
        });
      }
    })
  </script>

</body>

</html>
