@extends('layouts.main')

@section('title', 'Dashboard')
@section('content')

@php
    $rolesUser = [];
    if (auth()->user()){
        foreach (auth()->user()->roles as $item) {
            array_push($rolesUser, $item->kode_role);
        }
    }
@endphp

@if (in_array('SAD', $rolesUser))
    @php
        $user = App\Models\User::count();
        $rentalMobil = App\Models\RentalMobil::count();
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-two card-body">
                        <div class="stat-content">
                            <div class="stat-text">Pengguna</div>
                            <div class="stat-digit"><i class="fa fa-user"></i> {{$user}}</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success w-85" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="stat-widget-two card-body">
                        <div class="stat-content">
                            <div class="stat-text">Rental Mobil</div>
                            <div class="stat-digit"><i class="fa fa-car"></i> {{$rentalMobil}}</div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-primary w-75" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(in_array('ADM', $rolesUser))
@php
$mobils= App\Models\Mobil::Where('rental_mobil_id', auth()->user()->rentalMobil->id)
->latest()
->get();
@endphp
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Mobil</h4>
            </div>
            <div class="card-body">
                <a href="{{ route('mobil.create') }}" class="btn btn-primary">Create</a>
                <div class="table-responsive">
                    <table id="table-mobil" class="display nowrap text-dark" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Merek</th>
                                <th>Plat Nomor</th>
                                <th>Warna</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mobils as $mobil)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $mobil->merek }}</td>
                                    <td>{{ $mobil->plat_nomor }}</td>
                                    <td>{{ $mobil->warna }}</td>
                                    <td><img src="{{ asset('/storage/' . $mobil->foto) }}" alt="foto" style="width: 100px; height: 100px;"></td>
                                    <td>
                                        @if ($mobil->checkStatus($mobil->id))
                                            @if ($mobil->checkStatus($mobil->id)->status == 'selesai')
                                                <span class="badge badge-pill badge-success">Ready</span>
                                            @elseif ($mobil->checkStatus($mobil->id)->status == 'Pending')
                                                <span class="badge badge-pill badge-primary">Sedang di Booking</span>
                                            @elseif ($mobil->checkStatus($mobil->id)->status == 'Berjalan')
                                                <span class="badge badge-pill badge-secondary">Sedang di Rental</span>
                                                @else
                                                <span class="badge badge-pill badge-success">Ready</span>
                                            @endif
                                      
                                            
                                        @endif
                                      
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Merek</th>
                                <th>Plat Nomor</th>
                                <th>Warna</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
</div>


@push('scripts')
    <script>
        new DataTable('#table-mobil', {
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            }
        });
    </script>
@endpush
@endsection
