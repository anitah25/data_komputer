@extends('admin.components.layout')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2 class="border-bottom pb-2">
            <i class="bi bi-list-ul text-primary"></i> Daftar Perangkat Komputer
        </h2>
    </div>
    <div class="col-md-6 d-flex justify-content-md-end align-items-center">
        <a href="{{ route('komputer.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Perangkat Baru
        </a>
    </div>
</div>
@endsection
