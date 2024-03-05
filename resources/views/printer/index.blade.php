@extends('layouts.template')
@section('content')
    <title>Pasok | Kasir</title>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data Printer</h6>
        </div>
        <div class="card-body">
            @if (Session::get('update') != '')
                <div class='alert alert-success'>
                    <center><b>{{ Session::get('update') }}</b></center>
                </div>
            @endif
            <form action="{{ route('printer.update') }}" method="post">
                @csrf

                <input type="hidden" name="id_pasok" value={{ $printer->id }}>


                <div class="form-group">
                    <label for="">Nama Printer</label>
                    <input type="text" name="nama_printer" class="form-control" value="{{ $printer->nama_printer }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="">IP Local</label></label>
                    <input type="text" name="ip" class="form-control" value="{{ $printer->ip }}">
                </div>

                <input type="submit" value="Update" class="btn btn-warning mt-3">
            </form>
        </div>
    </div>
@endsection
