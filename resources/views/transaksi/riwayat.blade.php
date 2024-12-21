@extends('layouts.main')

@section('title', 'Riwayat transaksi')
@section('content')

<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <span class="ml-1">Riwayat transaksi</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Riwayat</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-users" class="display nowrap text-dark" style="width:100%">
                            @php
                            $rolesUser = [];
                            if (auth()->user()) {
                                foreach (auth()->user()->roles as $item) {
                                    array_push($rolesUser, $item->kode_role);
                                }
                            }
                            @endphp
                            
                            @if (in_array('ADM', $rolesUser))
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Merek</th>
                                        <th>Plat nomor</th>
                                        <th>Warna</th>
                                        <th>Foto</th>
                                        <th>Customer</th>
                                        <th>No Hp</th>
                                        <th>Tgl Peminjaman</th>
                                        <th>Tgl Pengembalian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayats as $riwayat)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $riwayat->mobil->merek }}</td>
                                            <td>{{ $riwayat->mobil->plat_nomor }}</td>
                                            <td>{{ $riwayat->mobil->warna }}</td>
                                            <td>
                                                <img src="{{ asset('/storage/' . $riwayat->mobil->foto) }}" alt="foto" style="width: 100px; height: 100px;">
                                            </td>
                                            <td>{{ $riwayat->user->konsumen->nama }}</td>
                                            <td>{{ $riwayat->user->konsumen->no_hp }}</td>
                                            <td>{{ $riwayat->tgl_peminjaman }}</td>
                                            <td>{{ $riwayat->tgl_pengembalian }}</td>
                                            <td>{{ $riwayat->status }}</td>
                                            <td>
                                                @if ($riwayat->status == 'pending')
                                                    <a href="{{ route('approve', $riwayat->id) }}" class="btn btn-success">Approve</a>
                                                @elseif ($riwayat->status == 'Berjalan')
                                                    <a href="{{ route('approve', $riwayat->id) }}" class="btn btn-primary">Selesai</a>
                                                @else
                                                    <span>Tidak ada aksi</span>

                                                    
                                                
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Merek</th>
                                        <th>Plat nomor</th>
                                        <th>Warna</th>
                                        <th>Foto</th>
                                        <th>Customer</th>
                                        <th>No Hp</th>
                                        <th>Tgl Peminjaman</th>
                                        <th>Tgl Pengembalian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            @elseif (in_array('CST', $rolesUser))
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Merek</th>
                                        <th>Plat nomor</th>
                                        <th>Warna</th>
                                        <th>Foto</th>
                                        <th>Tgl Peminjaman</th>
                                        <th>Tgl Pengembalian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayats as $riwayat)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $riwayat->mobil->merek }}</td>
                                            <td>{{ $riwayat->mobil->plat_nomor }}</td>
                                            <td>{{ $riwayat->mobil->warna }}</td>
                                            <td>
                                                <img src="{{ asset('/storage/' . $riwayat->mobil->foto) }}" alt="foto" style="width: 100px; height: 100px;">
                                            </td>
                                            <td>{{ $riwayat->tgl_peminjaman }}</td>
                                            <td>{{ $riwayat->tgl_pengembalian }}</td>
                                            <td>{{ $riwayat->status }}</td>
                                            <td>
                                            @if ($riwayat->status == 'pending')
                                             <form action="{{ route('booking.batalkan', $riwayat->id) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                  <button class="btn btn-warning" onclick="return confirm('Anda yakin batal ini?')">Batalkan</button>
                                                  </form>
                                            @else
                                           
                                                tidak ada aksi   
                                            @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Merek</th>
                                        <th>Plat nomor</th>
                                        <th>Warna</th>
                                        <th>Foto</th>
                                        <th>Tgl Peminjaman</th>
                                        <th>Tgl Pengembalian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    new DataTable('#table-users', {
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        }
    });
</script>
@endpush
@endsection