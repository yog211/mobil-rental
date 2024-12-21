<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Konsumen;
use App\Models\RentalMobil;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() 
    {
        $user = User::all();
        return view('users.index', [
            'users' => $user
        ]);
    }

    public function profile($id)
    {
        $role = Role::all();
        $user = User::find($id);
        return view('users.profile', [
            'user' => $user,
            'roles' => $role
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->username = $request->username;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        
        $roles = $this->checkRole($user->id);
        
        if (in_array('SAD', $roles)) {}

        if (in_array('CST', $roles)) {
            $konsumen = Konsumen::where('user_id', $user->id)->first();

            if ($konsumen) {
                $konsumen->update([
                    'nama' => $request->nama,
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp
                ]);
                $konsumen->save();
            } else {
                Konsumen::create([
                    'user_id' => $user->id,
                    'nama' => $request->nama,
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp
                ]);
            }
           
        } 
        
        if (in_array('ADM', $roles)) {
            $request->validate([
                'foto' => 'mimes:png,jpg|max:1000'
            ]);
        

        $rentalMobil = RentalMobil::where('user_id', $user->id)->first();


        if ($rentalMobil) {
    $rentalMobil->nama_rental = $request->nama_rental;
    $rentalMobil->deskripsi = $request->deskripsi;
    $rentalMobil->alamat = $request->alamat;
    $rentalMobil->no_hp = $request->no_hp;
    

    if ($request->hasFile('foto')) {
        $fotoL = public_path('/storage/') . $rentalMobil->foto;
        if (file_exists($fotoL)) {
            @unlink($fotoL);
        }
        $rentalMobil->foto = $request->file('foto')->store('rental-mobil', 'public');
   
    }
    $rentalMobil->save();
} else {
    RentalMobil::create([
    'user_id' => $user->id,
    'nama_rental' => $request->nama_rental,
    'deskripsi' => $request->deskripsi,
    'alamat' => $request->alamat,
    'no_hp' => $request->no_hp,
    'foto' => $request->file('foto') 
        ? $request->file('foto')->store('rental-mobil', 'public') 
        : '' // Berikan nilai default jika foto tidak diunggah
]);

}
        }
        return redirect()->back()->with('success', 'Profile Berhasil Diperbarui!');
    }


    public function checkRole($id)
    {
        $roleUser = [];
        $user = User::find($id);

        foreach ($user->roles as $item) {
            array_push($roleUser, $item->kode_role);
        }

        return $roleUser;
    }
}