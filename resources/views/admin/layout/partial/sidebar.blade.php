<div class="sidebar" data-color="green" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

    Tip 2: you can also add an image using data-image tag
-->
  <div class="logo">
    <a href="/" class="simple-text logo-normal">
      ANTRIAN RS BUNDA {{(Config::get('antrian.queue_version') > 1)? 'v'.Config::get('antrian.queue_version') : ''}}
    </a></div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item ">
        <a class="nav-link" href="{{route('dashboard.index')}}">
          <i class="material-icons">dashboard</i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="nav-item ">
      @if(Config::get('antrian.queue_version') == 1)
        <a class="nav-link" href="{{route('queuefo.read')}}">
      @else
        <a class="nav-link" href="{{route('queuefov2.read')}}">
      @endif
          <i class="material-icons">person</i>
          <p>Manajemen Antrian</p>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{route('counter_master.index')}}">
          <i class="material-icons">app_registration</i>
          <p>Master Loket</p>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('user_master.index')}}">
          <i class="material-icons">people</i>
          <p>Master Pengguna</p>
        </a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="">
          <i class="material-icons">volume_up</i>
          <p>Master Suara</p>
        </a>
      </li> -->
      <!-- <li class="nav-item ">
        <a class="nav-link" href="{{route('queueinfo.read')}}">
          <i class="material-icons">library_books</i>
          <p>Tampilan Antrian</p>
        </a>
      </li> -->
    </ul>
  </div>
</div>