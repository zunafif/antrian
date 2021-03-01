<div class="sidebar" data-color="green" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

    Tip 2: you can also add an image using data-image tag
-->
  <div class="logo">
    <a href="/" class="simple-text logo-normal">
      ANTRIAN RS BUNDA
    </a></div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item ">
        <a class="nav-link" href="/">
          <i class="material-icons">dashboard</i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{route('queuefo.read')}}">
          <i class="material-icons">person</i>
          <p>Manajemen Antrian</p>
        </a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="{{route('queueinfo.read')}}">
          <i class="material-icons">library_books</i>
          <p>Tampilan Antrian</p>
        </a>
      </li>
    </ul>
  </div>
</div>