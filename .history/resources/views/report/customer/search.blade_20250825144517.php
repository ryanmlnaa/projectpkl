@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow border-0">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="fas fa-search"></i> Pencarian Pelanggan & Kode FAT</h4>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Cari berdasarkan:</label>
                            <div class="input-group">
                                <select class="form-select">
                                    <option>ID Pelanggan</option>
                                    <option>Nama Pelanggan</option>
                                    <option>Nomor Telepon</option>
                                    <option>Kode FAT</option>
                                </select>
                                <input type="text" class="form-control" placeholder="Masukkan kata kunci...">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Info:</strong> Halaman pencarian ini masih dalam pengembangan.
                    Untuk sementara Anda dapat melihat semua data di
                    <a href="{{ route('reports.operational
