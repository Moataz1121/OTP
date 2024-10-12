<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{asset('assets')}}/"
  data-template="vertical-menu-template-free"
>

@include('merchant.auth.layouts.head')

  <body>
    <!-- Content -->

    <div class="container-xxl">
        @yield('content')    
    </div>

    <!-- / Content -->

   

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
@include('merchant.auth.layouts.scripts')  
</body>
</html>
