@extends('layouts.template')
@section('content')
    <title>Kategori | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>
        <div class="card-body">
            <form action="/kategori/update" method="post">
                @csrf
                <div class="form-group">
                    <label for="">Nama Kategori</label>
                    <input type="hidden" name="id_kategori" value="{{ $kategori->id_kategori }}">
                    <input type="text" name="nama_kategori" class="form-control" value="{{ $kategori->nama_kategori }}">
                </div>
                <label for="">Status</label>
                <br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="active"
                        @if ($kategori->status == 'active') checked @endif>
                    <label class="form-check-label" for="inlineRadio1">Aktif</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="disable"
                        @if ($kategori->status == 'disable') checked @endif>
                    <label class="form-check-label" for="inlineRadio2">Tidak Aktif</label>
                </div>
                <br>
                <input type="submit" value="Update" class="btn btn-warning mt-3">
            </form>
        </div>
    </div>
@endsection
