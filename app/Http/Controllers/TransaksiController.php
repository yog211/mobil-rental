<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\RentalMobil;
use Illuminate\Http\Request;
use App\Models\TransaksiRental;

class TransaksiController extends Controller
{
    public function index($id) 
    {
      
        return view('transaksi.booking', [
            'id' => $id
        ]);
    }


    public function proses(Request $request, $id) {

    
        TransaksiRental::create([
          'mobil_id' =>$id,
          'user_id' => auth()->user()->id,
          'tgl_peminjaman' => $request->tgl_peminjaman,
          'tgl_pengembalian' => $request->tgl_pengembalian,
          'status' => 'pending'
        ]);
    
    
        // Berikan feedback sukses
        return redirect()->route('riwayatTransaksi' ,auth()->user()->id)->with('success', 'Data berhasil di tambahkan!');
      }

      public function riwayatTransaksi($id = null)
     
      {
        $role = $this->checkRole();
        if (in_array('ADM', $role)) {
            $rentalMobil = auth()->user()->rentalMobil->id;
            $riwayat = TransaksiRental::with('mobil')
            ->whereHas('mobil', function ($query) use ($rentalMobil) {
                $query->where('rental_mobil_id', $rentalMobil);
            })
            ->latest()
            ->get();
            
        } elseif (in_array('CST', $role)){
            $riwayat =TransaksiRental::where('user_id', $id)->latest()->get();
        }
        return view ('transaksi.riwayat', [
            'riwayats' => $riwayat
        ]);
      }

      public function approve($id)
      {
        $data = TransaksiRental::find($id);
        if ($data->status == 'pending') {
            $data->update([
                'status'=> 'Berjalan'
            ]);
            return redirect()->back()->with('success', 'berhasil menyetujui');
        } elseif ($data->status == 'Berjalan') {
            $data->update([
                'status'=> 'Selesai'
            ]);
            return redirect()->back()->with('success', $data->mobil->merek. ''. 'status telah selseai dikembalikan');
        }
      }

      public function destroy ($id)
      {
        Transaksirental::find($id)->delete();
        return redirect()->back()->with('success', 'BOOKING DI batalkan');
      }

      public function checkRole()
      {
          $roleUser = [];
          $user = User::find(auth()->user()->id);
  
          foreach ($user->roles as $item) {
              array_push($roleUser, $item->kode_role);
          }
  
          return $roleUser;
      }
}
