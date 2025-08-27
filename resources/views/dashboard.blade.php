@extends('layouts.app')

@section('content')
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Default</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
              <li class="breadcrumb-item active" aria-current="page">Default</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <!-- <a href="#" class="btn btn-sm btn-neutral">New</a>
          <a href="#" class="btn btn-sm btn-neutral">Filters</a> -->
        </div>
      </div>

      <!-- Card stats -->
      <div class="row justify-content-center" style="margin-top: 80px;">

        <!-- Sales Report -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="dropdown w-100">
            <div class="card card-stats text-decoration-none dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Sales Report</h5>
                    <span class="h2 font-weight-bold mb-0">2,356</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                      <i class="ni ni-chart-bar-32"></i>
                    </div>
                  </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                  <span class="text-nowrap">Since last month</span>
                </p>
              </div>
            </div>

            <!-- Dropdown menu -->
            <div class="dropdown-menu w-100">
              <a class="dropdown-item" href="{{ route('reports.activity') }}">
                Report Activity
              </a>
              <a class="dropdown-item" href="{{ route('reports.competitor') }}">
                Report Competitor
              </a>
            </div>
          </div>
        </div>

        <!-- Operational Report -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="dropdown w-100">
            <div class="card card-stats text-decoration-none dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Operational Report</h5>
                    <span class="h2 font-weight-bold mb-0">924</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                      <i class="ni ni-laptop"></i>
                    </div>
                  </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                  <span class="text-nowrap">Since last month</span>
                </p>
              </div>
            </div>

            <!-- Dropdown menu -->
            <div class="dropdown-menu w-100">
              <a class="dropdown-item" href="{{ route('report.operational.index') }}">
                Input Data Pelanggan
              </a>
              <a class="dropdown-item" href="{{ route('customer.search') }}">
                Cari Pelanggan & kode FAT
              </a>
            </div>
          </div>
        </div>

        <!-- User Management -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="dropdown w-100">
            <div class="card card-stats text-decoration-none dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">User Management</h5>
                    <span class="h2 font-weight-bold mb-0">156</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                      <i class="ni ni-badge"></i>
                    </div>
                  </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 2.15%</span>
                  <span class="text-nowrap">Since last month</span>
                </p>
              </div>
            </div>

            <!-- Dropdown menu -->
            <div class="dropdown-menu w-100">
              <a class="dropdown-item" href="{{ route('users.index') }}">
                Daftar User
              </a>
            </div>
          </div>
        </div>

        <!-- Export Data -->
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="dropdown w-100">
            <div class="card card-stats text-decoration-none dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Export Data</h5>
                    <span class="h2 font-weight-bold mb-0">49,65%</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-gradient-purple text-white rounded-circle shadow">
                      <i class="ni ni-folder-17"></i>
                    </div>
                  </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                  <span class="text-nowrap">Since last month</span>
                </p>
              </div>
            </div>

            <!-- Dropdown menu -->
            <div class="dropdown-menu w-100">
              <a class="dropdown-item" href="{{ asset('/') }}argonpro/pages/export/activity.html">
                Report Activity
              </a>
              <a class="dropdown-item" href="{{ asset('/') }}argonpro/pages/export/competitor.html">
                Report Competitor
              </a>
              <a class="dropdown-item" href="{{ asset('/') }}argonpro/pages/export/operational.html">
                Report Operational
              </a>
            </div>
          </div>
        </div>

      </div>
      <!-- End stats -->
    </div>
  </div>
</div>

<!-- Tambahan CSS untuk memastikan dropdown bekerja dengan baik -->
<style>
.card.dropdown-toggle::after {
  display: none; /* Hide default dropdown arrow */
}

.dropdown-menu {
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.08);
  border: 1px solid rgba(0,0,0,0.05);
}

.dropdown-item {
  padding: 12px 16px;
  font-size: 14px;
  transition: all 0.2s ease;
}

.dropdown-item:hover {
  background-color: #f8f9fe;
  color: #5e72e4;
}

.card-stats {
  transition: all 0.3s ease;
}

.card-stats:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>
@endsection