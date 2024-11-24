<?php

namespace App\Http\Controllers;

use App\Http\Resources\Pengguna as ResourcesPengguna;
use App\Pengguna;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function GetFiltered(Request $request)
    {
        $nrp = $request->input('input_user_nrp');
        $roles = $request->input('input_id_roles');
        $result = [];
        if ($nrp != "") {
            $penggunas = Pengguna::where('kode_pengguna', $nrp)->orderBy('nama_pengguna', 'asc')->get();
            if (sizeof($penggunas) != 0) {
                foreach ($penggunas as $pengguna) {
                    $data[] = [
                        "Id" => $pengguna->id_pengguna, "Name" => $pengguna->nama_pengguna,
                        "Password" => $pengguna->passsword,
                        "Id_Roles" => $pengguna->id_pengguna_role, "created_at" => "",
                        "updated_at" => "", "UserId" => $pengguna->kode_pengguna,
                        "remember_token" => null, "has_pp" => 0, "profile_picture" => 0
                    ];
                }
                return $data;
            }
        } else {
            if ($roles == "")
                $roles = 2;
            $penggunas = Pengguna::where('id_pengguna_role', $roles)->orderBy('nama_pengguna', 'asc')->get();
            if (sizeof($penggunas) != 0) {
                foreach ($penggunas as $pengguna) {
                    $data[] = [
                        "Id" => $pengguna->id_pengguna, "Name" => $pengguna->nama_pengguna,
                        "Password" => $pengguna->passsword,
                        "Id_Roles" => $pengguna->id_pengguna_role, "created_at" => "",
                        "updated_at" => "", "UserId" => $pengguna->kode_pengguna,
                        "remember_token" => null, "has_pp" => 0, "profile_picture" => 0
                    ];
                }
                return $data;
            }
        }
        return json_encode($result);
    }

    public function GetFilteredV2(Request $request)
    {
        $nrp = $request->input('input_user_nrp');
        $roles = $request->input('input_id_roles');
        $result = [];
        if ($nrp != "") {
            $result = ResourcesPengguna::collection(Pengguna::where('kode_pengguna', $nrp)->get());
        } else {
            if ($roles == "")
                $roles = 2;
            $result = ResourcesPengguna::collection(Pengguna::where('id_pengguna_role', $roles)->get());
        }
        return $result;
    }

    public function GetProfile(Request $request)
    {
        $kodePengguna = $request->input('kode_pengguna');
        return new ResourcesPengguna(Pengguna::where('kode_pengguna', $kodePengguna)->first());
    }

    public function UpdateProfile(Request $request)
    {
        $kodePengguna = $request->input('kode_pengguna');
        $email = $request->input('email');
        $kotaLahir = $request->input('kota_lahir');
        $tanggalLahir = $request->input('tanggal_lahir');
        $nomorHP = $request->input('nomor_hp');
        $nomorTelepon = $request->input('nomor_telepon');
        $kontakLainnya = $request->input('kontak_lainnya');
        $photoProfile = $request->input('path');
        $pengguna = Pengguna::where('kode_pengguna', $kodePengguna)->first();
        if ($email != null && $email != "")
            $pengguna->email = $email;
        if ($kotaLahir != null && $kotaLahir != "")
            $pengguna->kota_lahir = $kotaLahir;
        if ($tanggalLahir != null && $tanggalLahir != "")
            $pengguna->tanggal_lahir = $tanggalLahir;
        if ($nomorHP != null && $nomorHP != "")
            $pengguna->nomor_hp = $nomorHP;
        if ($nomorTelepon != null && $nomorTelepon != "")
            $pengguna->nomor_telepon = $nomorTelepon;
        if ($kontakLainnya != null && $kontakLainnya != "")
            $pengguna->kontak_lainnya = $kontakLainnya;

        if ($filePhotoProfile = $request->file('photo_profile')) {
            $destination_path_photo_profile = 'public/profile/' . md5($kodePengguna) . '/' . 'photo_profile/'; // upload path
            $name_file_photo_profile = $kodePengguna . '_' . $pengguna->nama_pengguna . "." . $filePhotoProfile->getClientOriginalExtension();
            $filePhotoProfile->move($destination_path_photo_profile, $name_file_photo_profile);
            $pengguna->photo_profile = $photoProfile . $destination_path_photo_profile . $name_file_photo_profile;
        }

        $pengguna->save();
        return new ResourcesPengguna($pengguna);
    }

    public function ResetPassword(Request $request)
    {
        $kodePengguna = $request->input('input_user_id');
        $pengguna = Pengguna::where('kode_pengguna', $kodePengguna)->first();
        $pengguna->password = md5($pengguna->kode_pengguna);
        $pengguna->save();
        return json_encode(['id' => '1', 'message' => 'Reset Password Success']);
    }

    public function UpdatePassword(Request $request)
    {
        $kodePengguna = $request->input('input_user_id');
        $userPass = $request->input('input_user_pass');
        $userNewPass = $request->input('input_new_pass');
        $pengguna = Pengguna::where('kode_pengguna', $kodePengguna)->first();
        if ($pengguna == null)
            return json_encode(['id' => '0', 'message' => 'Update Password Failed']);
        else if (md5($userPass) == $pengguna->password) {
            $pengguna->password = md5($userNewPass);
            $pengguna->save();
            return json_encode(['id' => '1', 'message' => 'Update Password Success']);
        }
        return json_encode(['id' => '0', 'message' => 'Update Password Failed']);
    }

    public function login(Request $request)
    {
        
        $kodePengguna = $request->input('input_user_id');
        $userPass = $request->input('input_user_password');
        $pengguna = Pengguna::where('kode_pengguna', $kodePengguna)->first();
        $messageError = json_encode(["Id" => "0", "UserId" => "0", "Name" => "0", "Id_Roles" => "0", "Message" => "Username or Password mismatch."]);
        if ($pengguna == null)
            return $messageError;
        else if (md5($userPass) == $pengguna->password)
            return json_encode(["Id" => $pengguna->id_pengguna, "UserId" => $pengguna->kode_pengguna, "Name" => $pengguna->nama_pengguna, "Id_Roles" => $pengguna->id_pengguna_role, "Message" => "Login Success"]);
        return $messageError;
        
    }

    public function loginV2(Request $request)
    {
        
        $kodePengguna = $request->input('kode_pengguna');
        $userPass = $request->input('password');
        $pengguna = Pengguna::where('kode_pengguna', $kodePengguna)->first();
        $messageError = ["message" => "Username or Password mismatch", "data" => null];
        $response = response($messageError, 401);
        if ($pengguna == null)
            return $response;
        else if (md5($userPass) == $pengguna->password) {
            $messageSuccess = ['message' => 'Login Success', 'data' => new ResourcesPengguna($pengguna)];
            return response($messageSuccess, 200);
        }
        return $response;
    }

    public function loginV3(Request $request) {
        return "Login with V3";
    }

    public function registerUsers(Request $request)
    {
        $messageResponse = ["message" => "Please Check Input Parameter", "data" => null];
        $response = response($messageResponse, 401);
        if ($request->has("data_pengguna") && $request->has("id_pengguna_role")) {
            $dataPengguna = json_decode($request->input("data_pengguna"));
            $idPengunaRole = $request->input("id_pengguna_role");
            for ($i = 0; $i < sizeOf($dataPengguna); $i++) {
                Pengguna::firstOrCreate(["kode_pengguna" => $dataPengguna[$i]->kode_pengguna], ["nama_pengguna" => $dataPengguna[$i]->nama_pengguna, "password" => md5($dataPengguna[$i]->kode_pengguna), "id_pengguna_role" => $idPengunaRole]);
            }
            $messageResponse = ["message" => "Success", "data" => null];
            $response = response($messageResponse, 200);
        }
        return $response;
    }
}
