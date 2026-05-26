@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="form-group mb-2">
                <a href="{{ url('kategori/form/new') }}" class="btn btn-secondary">+ Kategori Baru</a>
            </div>
            <div class="card">
                <div class="card-header">Daftar Kategori</div>

                <div class="card-body">
                    @include('kategori.index.filter')
                    @include('kategori.index.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@include('kategori.index.js')
@endsection
