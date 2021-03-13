<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <title>{{config('app.name')}}</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    @include('admin/layout/partial/head')
  </head>
  <body class="">
    @yield('stylesheet')
    <div class="wrapper">
      @include('admin/layout/partial/sidebar')
      <div class="main-panel">
        @include('admin/layout/partial/navbar')
        @yield('content')
        @include('admin/layout/partial/footer')
        @include('admin/layout/partial/plugin')
      </div>
    </div>
    
    @include('admin/layout/partial/foot')
    @yield('javascript')
  </body>
</html>