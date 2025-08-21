<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
  <div class="scrollbar-inner">
    <!-- Brand -->
    <div class="sidenav-header d-flex align-items-center">
      <a class="navbar-brand" href="{{ asset('/') }}argonpro/pages/dashboards/dashboard.html">
        <img src="{{ asset('/') }}argonpro/assets/img/brand/blue.png" class="navbar-brand-img" alt="...">
      </a>
      <div class="ml-auto">
        <!-- Sidenav toggler -->
        <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
          <div class="sidenav-toggler-inner">
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar-inner">
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Nav items -->
        <ul class="navbar-nav">

          <!-- Dashboard -->
         <li class="nav-item">
  <a class="nav-link" href="{{ route('dashboard') }}">
    <i class="ni ni-shop text-primary"></i>
    <span class="nav-link-text">Dashboard</span>
  </a>
</li>


          <!-- Sales Report -->
<li class="nav-item">
  <a class="nav-link" href="#navbar-sales" data-toggle="collapse">
    <i class="ni ni-chart-bar-32 text-info"></i>
    <span class="nav-link-text">Sales Report</span>
  </a>
  <div class="collapse" id="navbar-sales">
    <ul class="nav nav-sm flex-column">
      <li class="nav-item"><a href="{{ route('reports.activity') }}" class="nav-link">Report Activity</a></li>
      <li class="nav-item"><a href="{{ route('reports.competitor') }}" class="nav-link">Report Competitor</a></li>
    </ul>
  </div>
</li>

          <!-- Operational Report -->
          <li class="nav-item">
            <a class="nav-link" href="#navbar-operational" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-operational">
              <i class="ni ni-settings-gear-65 text-orange"></i>
              <span class="nav-link-text">Operational Report</span>
            </a>
            <div class="collapse" id="navbar-operational">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="{{ asset('/') }}argonpro/pages/operational/input-pelanggan.html" class="nav-link">Input Data Pelanggan</a>
                </li>
                <li class="nav-item">
                  <a href="{{ asset('/') }}argonpro/pages/operational/cari-pelanggan.html" class="nav-link">Cari Pelanggan</a>
                </li>
                <li class="nav-item">
                  <a href="{{ asset('/') }}argonpro/pages/operational/kotak-distribusi.html" class="nav-link">Kotak Distribusi</a>
                </li>
              </ul>
            </div>
          </li>

          <!-- Export Data -->
          <li class="nav-item">
            <a class="nav-link" href="#navbar-export" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-export">
              <i class="ni ni-cloud-download-95 text-green"></i>
              <span class="nav-link-text">Export Data</span>
            </a>
            <div class="collapse" id="navbar-export">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="{{ asset('/') }}argonpro/pages/export/activity.html" class="nav-link">Report Activity</a>
                </li>
                <li class="nav-item">
                  <a href="{{ asset('/') }}argonpro/pages/export/competitor.html" class="nav-link">Report Competitor</a>
                </li>
                <li class="nav-item">
                  <a href="{{ asset('/') }}argonpro/pages/export/operational.html" class="nav-link">Report Operational</a>
                </li>
              </ul>
            </div>
          </li>

        </ul>
      </div>
    </div>
  </div>
</nav>
