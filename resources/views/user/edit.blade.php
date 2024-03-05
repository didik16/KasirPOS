@extends('layouts.template')
@section('content')
    <title>Data Admin | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>
        <div class="card-body">
            <form action="/user/update" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Nama Admin</label>
                    <input type="hidden" name="id" value="{{ $admin->id }}">
                    <input type="text" name="name" class="form-control" value="{{ $admin->name }}">
                </div>
                <div class="form-group">

                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $admin->email }}">
                </div>
                <div class="form-group">

                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <label for="">Status</label>
                <br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="active"
                        @if ($admin->status == 'active') checked @endif>
                    <label class="form-check-label" for="inlineRadio1">Aktif</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="disable"
                        @if ($admin->status == 'disable') checked @endif>
                    <label class="form-check-label" for="inlineRadio2">Tidak Aktif</label>
                </div>
                <br>
                <input type="submit" value="Update" class="btn btn-warning">
            </form>
        </div>
    </div>
@endsection
