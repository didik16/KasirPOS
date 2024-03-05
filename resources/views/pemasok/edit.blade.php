@extends('layouts.template')
@section('content')
    <title>Pasok | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
        </div>
        <div class="card-body">
            <form action="/pemasok/update" method="post">
                @csrf

                <input type="hidden" name="id_pasok" value={{ $pasok->id }}>


                <div class="form-group">
                    <label for="">Nama Pemasok</label>
                    <input type="text" name="nama_pemasok" class="form-control" value="{{ $pasok->nama_pemasok }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="">No HP</label></label>
                    <input type="text" name="no_hp" class="form-control" value="{{ $pasok->no_hp }}">
                </div>
                <div class="form-group">
                    <label for="">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="{{ $pasok->alamat }}">
                </div>
                <label for="">Status</label>
                <br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="active"
                        @if ($pasok->status == 'active') checked @endif>
                    <label class="form-check-label" for="inlineRadio1">Aktif</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="disable"
                        @if ($pasok->status == 'disable') checked @endif>
                    <label class="form-check-label" for="inlineRadio2">Tidak Aktif</label>
                </div>
                <br>
                <input type="submit" value="Update" class="btn btn-warning mt-3">
            </form>
        </div>
    </div>
@endsection
