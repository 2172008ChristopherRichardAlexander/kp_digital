<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;

use App\Topik;
// use App\Sidang;
// use App\JenisSidang;
// use App\KomponenNilai;
use App\Http\Requests;
use App\Http\Resources\Topik as TopikResource;
use App\Pengguna;
// use App\Http\Resources\JenisSidang as JenisSidangResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TopikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // ! ################# MAHASISWA #################
    public function index()
    {
        $id_pengaju = 1772999;
        // Get Topik
        // $kumpulan_topik = Topik::where('id_pengaju', $id_pengaju)->get();
        // foreach ($kumpulan_topik as $topik) {
        //     $data[] = [
        //         'judul_topik' => $topik->judul_topik,
        //         'latar_belakang' => $topik->latar_belakang,
        //         'rumusan_masalah' => $topik->rumusan_masalah,
        //         'tujuan_pembahasan' => $topik->tujuan_pembahasan,
        //         'ruang_lingkup' => $topik->ruang_lingkup,
        //         'sumber_data' => $topik->sumber_data,
        //         'sistematika_penyajian' => $topik->sistematika_penyajian,
        //         'tanggal_pengajuan_topik' => $topik->tanggal_pengajuan_topik,
        //         'status_persetujuan_dosen' => $topik->status_persetujuan_dosen,
        //         'status_topik' => $topik->status_topik,
        //         'id_batch' => $topik->batch,
        //         'id_kbk' => $topik->kbk,
        //         'id_pengaju' => $topik->id_pengaju,
        //         'id_pembimbing' => $topik->id_pembimbing
        //     ];
        // }
        $kumpulan_topik = TopikResource::collection(Topik::where('id_pengaju', $id_pengaju)->get());
        // Return collection of Topik as a resource
        // return TopikResource::collection($topik);
        return $kumpulan_topik;
    }


    public function listHistoriMahasiswa(Request $request)
    {
        return TopikResource::collection(Topik::where('id_pengaju', $request->input('id_pengaju'))->get());
    }

    public function getTopikTerbaruPengguna(Request $request)
    {
        // Topik Sudah diterima oleh Koordinator KBK
        $matchThese = ['id_pengaju' => $request->input('id_pengaju'), 'id_batch' => $request->input('id_batch')];
        // $topik = Topik::where($matchThese)->orderBy('id_topik', 'desc')->first();
        $topik = Topik::where($matchThese)->orderBy('id_topik', 'desc')->get();
        if (sizeof($topik) != 0) {
            return TopikResource::collection($topik);
        } else {
            return ['message' => 'kosong'];
        }
    }

    public function getTopikSidang(Request $request)
    {
        // ! GET topik sidang terakhir mahasiswa
        // ? Id Pengaju, *Status Topik
        $matchThese = ['id_pengaju' => $request->input('id_pengaju')];
        if ($request->input('status_topik')) {
            $status_topik = explode(",", $request->input('status_topik'));
            $topik = Topik::where($matchThese)->whereIn('status_topik', $status_topik)->orderBy('id_topik', 'desc')->first();
        } else {
            $topik = Topik::where($matchThese)->orderBy('id_topik', 'desc')->first();
        }
        if ($topik) {
            return $topik;
        } else {
            return ['message' => 'kosong'];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slug = Str::slug($request->input('judul_topik'), "-");
        $latestSlug = Topik::whereRaw("slug_topik = '$slug' or slug_topik LIKE '$slug-%'")->latest('id_topik')->value('slug_topik');
        if ($latestSlug) {
            $arrSlug = explode("-", $latestSlug);
            $number = intval(end($arrSlug));
            $slug .= '-' . ($number + 1);
        }
        // Menentukan Perintah Insert("Post") atau Update("Put")
        $topik = $request->isMethod('put') ? Topik::find($request->id_topik) : new Topik;
        // Insert Topik
        $topik->judul_topik = $request->input('judul_topik');
        $topik->slug_topik = $slug;
        $topik->latar_belakang = $request->input('latar_belakang');
        $topik->rumusan_masalah = $request->input('rumusan_masalah');
        $topik->tujuan_pembahasan = $request->input('tujuan_pembahasan');
        $topik->ruang_lingkup = $request->input('ruang_lingkup');
        $topik->sumber_data = $request->input('sumber_data');
        $topik->sistematika_penyajian = $request->input('sistematika_penyajian');
        $topik->tanggal_pengajuan_topik = $request->input('tanggal_pengajuan_topik');
        $topik->status_persetujuan_dosen = $request->input('status_persetujuan_dosen');
        $topik->status_topik = $request->input('status_topik');
        $topik->id_batch = $request->input('id_batch');
        $topik->id_kbk = $request->input('id_kbk');
        $topik->id_pengaju = $request->input('id_pengaju');
        $topik->id_pembimbing = $request->input('id_pembimbing');
        $topik->id_pembimbing_lapangan = $request->input('id_pembimbing_lapangan');

        if ($request->isMethod('post')) {

            // Upload File Transkrip Nilai
            $name_file_transkrip_nilai = "noimage.png";
            if ($transkrip_nilai_file = $request->file('transkrip_nilai')) {
                $destination_path_transkrip_nilai = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'transkrip_nilai/'; // upload path
                $name_file_transkrip_nilai = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'transkrip_nilai' . "." . $transkrip_nilai_file->getClientOriginalExtension();
                $transkrip_nilai_file->move($destination_path_transkrip_nilai, $name_file_transkrip_nilai);
            }
            $topik->transkrip_nilai = $name_file_transkrip_nilai;

            // Upload File DKBS
            $name_file_dkbs = "noimage.png";
            if ($dkbs_file = $request->file('dkbs')) {
                $destination_path_dkbs = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'dkbs/'; // upload path
                $name_file_dkbs = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'dkbs' . "." . $dkbs_file->getClientOriginalExtension();
                $dkbs_file->move($destination_path_dkbs, $name_file_dkbs);
            }
            $topik->dkbs = $name_file_dkbs;

            // Upload File Form Persetujuan Instansi
            if ($request->input('status_persetujuan_dosen') == 4) {
                $name_file_form_persetujuan_instansi = "noimage.png";
                if ($form_persetujuan_instansi_file = $request->file('form_persetujuan_instansi')) {
                    $destination_path = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'form_persetujuan_instansi/'; // upload path
                    $name_file_form_persetujuan_instansi = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'form_persetujuan_instansi' . "." . $form_persetujuan_instansi_file->getClientOriginalExtension();
                    $form_persetujuan_instansi_file->move($destination_path, $name_file_form_persetujuan_instansi);
                }
                $topik->form_persetujuan_instansi = $name_file_form_persetujuan_instansi;
            }

            // Upload File Lampiran Gambar
            $name_lampiran_gambar = "noimage.png";
            if ($lampiran_gambar_file = $request->file('lampiran_gambar')) {
                $destination_path_lampiran_gambar = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'lampiran_gambar/'; // upload path
                $name_lampiran_gambar = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'lampiran_gambar' . "." . $lampiran_gambar_file->getClientOriginalExtension();
                $lampiran_gambar_file->move($destination_path_lampiran_gambar, $name_lampiran_gambar);
                $topik->lampiran_gambar = $name_lampiran_gambar;
            }
        } elseif ($request->isMethod('put')) {
            // if (is_null($request->file('transkrip_nilai')) || empty($request->file('transkrip_nilai'))) {
            //     // if (is_null($request->file('transkrip_nilai'))) {
            //     //     # code...
            // } else {
            //     // Upload File Transkrip Nilai
            //     $name_file_transkrip_nilai = "noimage.png";
            //     if ($transkrip_nilai_file = $request->file('transkrip_nilai')) {
            //         $destination_path_transkrip_nilai = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'transkrip_nilai/'; // upload path
            //         $name_file_transkrip_nilai = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'transkrip_nilai' . "." . $transkrip_nilai_file->getClientOriginalExtension();
            //         $transkrip_nilai_file->move($destination_path_transkrip_nilai, $name_file_transkrip_nilai);
            //     }
            //     $topik->transkrip_nilai = $name_file_transkrip_nilai;
            // }

            // if (is_null($request->file('dkbs')) || empty($request->file('dkbs'))) {
            //     // if (is_null($request->file('dkbs'))) {
            //     //     # code...
            // } else {
            //     // Upload File DKBS
            //     $name_file_dkbs = "noimage.png";
            //     if ($dkbs_file = $request->file('dkbs')) {
            //         $destination_path_dkbs = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'dkbs/'; // upload path
            //         $name_file_dkbs = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'dkbs' . "." . $dkbs_file->getClientOriginalExtension();
            //         $dkbs_file->move($destination_path_dkbs, $name_file_dkbs);
            //     }
            //     $topik->dkbs = $name_file_dkbs;
            // }


            // if (is_null($request->file('lampiran_gambar')) || empty($request->file('lampiran_gambar'))) {
            //     // if (is_null($request->file('lampiran_gambar'))) {
            //     //     # code...
            // } else {
            //     // Upload File Lampiran Gambar
            //     $name_file_lampiran_gambar = "noimage.png";
            //     if ($lampiran_gambar_file = $request->file('lampiran_gambar')) {
            //         $destination_path_lampiran_gambar = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'lampiran_gambar/'; // upload path
            //         $name_file_lampiran_gambar = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'lampiran_gambar' . "." . $lampiran_gambar_file->getClientOriginalExtension();
            //         $lampiran_gambar_file->move($destination_path_lampiran_gambar, $name_file_lampiran_gambar);
            //     }
            //     $topik->lampiran_gambar = $name_file_lampiran_gambar;
            // }

            // // Upload File Form Persetujuan Instansi
            // if ($request->input('status_persetujuan_dosen') == 4) {
            //     if (is_null($request->file('form_persetujuan_instansi')) || empty($request->file('form_persetujuan_instansi'))) {
            //         // if (is_null($request->file('form_persetujuan_instansi'))) {
            //         //     # code...
            //     } else {
            //         $name_file_form_persetujuan_instansi = "noimage.png";
            //         if ($form_persetujuan_instansi_file = $request->file('form_persetujuan_instansi')) {
            //             $destination_path = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'form_persetujuan_instansi/'; // upload path
            //             $name_file_form_persetujuan_instansi = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'form_persetujuan_instansi' . "." . $form_persetujuan_instansi_file->getClientOriginalExtension();
            //             $form_persetujuan_instansi_file->move($destination_path, $name_file_form_persetujuan_instansi);
            //         }
            //         $topik->form_persetujuan_instansi = $name_file_form_persetujuan_instansi;
            //     }
            // }
        }
        $topik->save();
    }

    public function updateGambar(Request $request)
    {
        // $topik = Topik::where('id_topik', $request->input('id_topik'))->get();
        $topik = Topik::find($request->id_topik);
        if (is_null($request->file('transkrip_nilai')) || empty($request->file('transkrip_nilai'))) {
            // if (is_null($request->file('transkrip_nilai'))) {
            //     # code...
        } else {
            // Upload File Transkrip Nilai
            $name_file_transkrip_nilai = "noimage.png";
            if ($transkrip_nilai_file = $request->file('transkrip_nilai')) {
                $destination_path_transkrip_nilai = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'transkrip_nilai/'; // upload path
                $name_file_transkrip_nilai = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'transkrip_nilai' . "." . $transkrip_nilai_file->getClientOriginalExtension();
                $transkrip_nilai_file->move($destination_path_transkrip_nilai, $name_file_transkrip_nilai);
            }
            $topik->transkrip_nilai = $name_file_transkrip_nilai;
        }

        if (is_null($request->file('dkbs')) || empty($request->file('dkbs'))) {
            // if (is_null($request->file('dkbs'))) {
            //     # code...
        } else {
            // Upload File DKBS
            $name_file_dkbs = "noimage.png";
            if ($dkbs_file = $request->file('dkbs')) {
                $destination_path_dkbs = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'dkbs/'; // upload path
                $name_file_dkbs = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'dkbs' . "." . $dkbs_file->getClientOriginalExtension();
                $dkbs_file->move($destination_path_dkbs, $name_file_dkbs);
            }
            $topik->dkbs = $name_file_dkbs;
        }


        if (is_null($request->file('lampiran_gambar')) || empty($request->file('lampiran_gambar'))) {
            // if (is_null($request->file('lampiran_gambar'))) {
            //     # code...
        } else {
            // Upload File Lampiran Gambar
            $name_file_lampiran_gambar = "noimage.png";
            if ($lampiran_gambar_file = $request->file('lampiran_gambar')) {
                $destination_path_lampiran_gambar = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'lampiran_gambar/'; // upload path
                $name_file_lampiran_gambar = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'lampiran_gambar' . "." . $lampiran_gambar_file->getClientOriginalExtension();
                $lampiran_gambar_file->move($destination_path_lampiran_gambar, $name_file_lampiran_gambar);
            }
            $topik->lampiran_gambar = $name_file_lampiran_gambar;
        }

        // Upload File Form Persetujuan Instansi
        if ($request->input('status_persetujuan_dosen') == 4) {
            if (is_null($request->file('form_persetujuan_instansi')) || empty($request->file('form_persetujuan_instansi'))) {
                // if (is_null($request->file('form_persetujuan_instansi'))) {
                //     # code...
            } else {
                $name_file_form_persetujuan_instansi = "noimage.png";
                if ($form_persetujuan_instansi_file = $request->file('form_persetujuan_instansi')) {
                    $destination_path = 'public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . 'form_persetujuan_instansi/'; // upload path
                    $name_file_form_persetujuan_instansi = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . 'form_persetujuan_instansi' . "." . $form_persetujuan_instansi_file->getClientOriginalExtension();
                    $form_persetujuan_instansi_file->move($destination_path, $name_file_form_persetujuan_instansi);
                }
                $topik->form_persetujuan_instansi = $name_file_form_persetujuan_instansi;
            }
        }
        $topik->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Topik  $topik
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // Get Topik
        $topik = Topik::where('slug_topik', $slug)->first();

        // Return single Topik as a Resource
        return new TopikResource($topik);
    }

    public function showFromId($id_topik)
    {
        // Get Topik
        $topik = Topik::where('id_topik', $id_topik)->first();

        // Return single Topik as a Resource
        return new TopikResource($topik);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Topik  $topik
     * @return \Illuminate\Http\Response
     */
    public function edit(Topik $topik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Topik  $topik
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topik $topik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Topik  $topik
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topik $topik)
    {
        //
    }


    public function uploadFile($request, $namaFormInput)
    {
        // // Handle File Upload
        // if ($request->hasFile($namaFormInput)) {
        //     // Get just file name
        //     $fileName = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . $namaFormInput;
        //     // Get just extension
        //     $extension = $request->file($namaFormInput)->getClientOriginalExtension();
        //     // Filename to store
        //     $fileNameToStore = $fileName . '.' . $extension;
        //     // Upload Image
        //     $path = $request->file($namaFormInput)->storeAs('public/pengajuan_topik/' . $request->input('id_pengaju') . '/' . $namaFormInput, $fileNameToStore);
        // } else {
        //     $fileNameToStore = 'noimage.jpg';
        // }
        // return $fileNameToStore;

        // $name = 'noimage.jpg';
        // if ($request->get($namaFormInput)) {
        //     $image = $request->get($namaFormInput);
        //     $name = $request->input('id_batch') . '_' . $request->input('id_pengaju') . '_' . $namaFormInput;
        //     \Image::make($request->get($namaFormInput))->save(public_path('pengajuan_topik/' . $request->input('id_pengaju') . '/' . $namaFormInput) . $name);
        // }
        // $image = new FileUpload();
        // $image->image_name = $name;
        // $image->save();
        // return $name;
    }


    // ! ################# Dosen #################
    public function listTopikPersetujuan(Request $request)
    {
        $id_pembimbing = $request->input('id_pembimbing');
        // Menunggu persetujuan dosen
        $status_persetujuan_dosen = 2;
        $matchThese = ['id_pembimbing' => $id_pembimbing, 'status_persetujuan_dosen' => $status_persetujuan_dosen];
        // $kumpulan_topik = TopikResource::collection(Topik::where($matchThese)->get());
        $kumpulan_topik = Topik::where($matchThese)->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }

    // ? List Topik Permintaan Persetujuan
    public function listTopikPermintaanPersetujuan(Request $request)
    {
        // Menunggu persetujuan dosen
        $status_persetujuan_dosen = 2;
        $matchThese = ['id_pembimbing' => $request->input('id_pembimbing'), 'status_persetujuan_dosen' => $status_persetujuan_dosen, 'id_batch' => $request->input('id_batch')];
        // $kumpulan_topik = TopikResource::collection(Topik::where($matchThese)->get());
        $kumpulan_topik = Topik::where($matchThese)->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                    'status_topik' => $topik->status_topik
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }

    // ? List Topik History Permintaan Persetujuan
    public function listTopikHistoryPermintaanPersetujuan(Request $request)
    {
        $status_persetujuan_dosen = 2;
        $matchThese = ['id_pembimbing' => $request->input('id_pembimbing'), 'id_batch' => $request->input('id_batch')];
        $kumpulan_topik = Topik::where($matchThese)->where('status_persetujuan_dosen', "!=", $status_persetujuan_dosen)->orderBy('id_topik', 'desc')->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'status_persetujuan_dosen' => $topik->status_persetujuan_dosen,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }
    // ? List Mahasiswa yang dibimbing berdasarkan role_pembimbing,id_pembimbing,id_batch
    public function listMahasiswaBimbingan(Request $request)
    {
        // ! Role Pembimbing antara "id_pembimbing" dan "id_pemnbimbing_lapangan"
        $matchThese = [$request->input('role_pembimbing') => $request->input('id_pembimbing'), 'id_batch' => $request->input('id_batch')];
        $kumpulan_topik = Topik::where($matchThese)->orderBy('id_pengaju')->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }

    // public function detail
    public function updateStatusPersetujuanDosen(Request $request)
    {
        Topik::where('id_topik', $request->input('id_topik'))->update(['status_persetujuan_dosen' => $request->input('status_persetujuan_dosen'), 'status_topik' => $request->input('status_topik')]);

        // $topik = Topik::where('id_topik', $request->input('id_topik'))->first();
        // $topik->status_persetujuan_dosen = $request->input('status_persetujuan_dosen');
        // $topik->save();
    }
    // ! ################# Koordinator KP #################
    public function listTopikPeriksaKelengkapanDokumen()
    {
        // * Topik sudah diterima oleh dosen yang bersangkutan atau melalui Instansi
        $status_persetujuan_dosen = [1, 4];
        // * Topik sedang dalam proses pengajuan
        $status_topik = 2;

        $matchThese = ['status_topik' => $status_topik];

        $kumpulan_topik = Topik::where($matchThese)->whereIn('status_persetujuan_dosen', $status_persetujuan_dosen)->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }

    public function listTopikPemeriksaanDokumen(Request $request)
    {
        // * Topik sudah diterima oleh dosen yang bersangkutan atau melalui Instansi atau sedang menunggu persetujuan dosen
        $status_persetujuan_dosen = [1, 2, 4];
        // * Topik sedang dalam proses pengajuan, atau sedang dalam proses permintaan persetujuan dosen
        $status_topik = [2, 3];

        $matchThese = ['id_batch' => $request->input('id_batch')];

        $kumpulan_topik = Topik::where($matchThese)->whereIn('status_topik', $status_topik)->whereIn('status_persetujuan_dosen', $status_persetujuan_dosen)->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'status_topik' => $topik->status_topik,
                    'status_persetujuan_dosen' => $topik->status_persetujuan_dosen,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }

    public function listTopikHistoryPemeriksaanDokumen(Request $request)
    {
        // * Topik sedang dalam proses pengajuan, atau sedang dalam proses permintaan persetujuan dosen
        $status_topik = [0, 1, 5, 6];

        $matchThese = ['id_batch' => $request->input('id_batch')];

        $kumpulan_topik = Topik::where($matchThese)->whereIn('status_topik', $status_topik)->orderBy('id_topik', 'desc')->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'status_topik' => $topik->status_topik,
                    'status_persetujuan_dosen' => $topik->status_persetujuan_dosen,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }


    public function listTopik(Request $request)
    {
        // TODO: Belum Di Uji Coba
        $status_topik = explode(",", $request->input('status_topik'));
        $status_persetujuan_dosen = explode(",", $request->input('status_persetujuan_dosen'));
        $matchThese = ['id_batch' => $request->input('id_batch')];
        // ! Jika terdapat Role Pengguna
        if ($request->input('role_pengguna')) {
            // * Menghapus Whitespaces (Space, Tab, line), membuat semua menjadi huruf kecil
            $role = strtolower(preg_replace('/\s+/', '', $request->input('role_pengguna')));
            if ($role == "dosen")
                $matchThese = ['id_pembimbing' => $request->input('id_pembimbing'), 'id_batch' => $request->input('id_batch')];
            elseif ($role == "koorkbk")
                $matchThese = ['id_batch' => $request->input('id_batch'), 'id_kbk' => $request->input('id_kbk')];
        }

        $kumpulan_topik = TopikResource::collection(Topik::where($matchThese)->whereIn('status_persetujuan_dosen', $status_persetujuan_dosen)->whereIn('status_topik', $status_topik)->get());
        if (sizeof($kumpulan_topik) != 0) {
            return $kumpulan_topik;
        }
        return ['message' => 'kosong'];
    }


    public function updateStatusPersetujuanKoorKp(Request $request)
    {
        Topik::where('id_topik', $request->input('id_topik'))->update(['status_topik' => $request->input('status_topik')]);
    }
    public function updateKbk(Request $request)
    {
        Topik::where('id_topik', $request->input('id_topik'))->update(['id_kbk' => $request->input('id_kbk')]);
    }

    public function stepperTopik(Request $request)
    {
        return Topik::select(DB::raw('count(id_topik) as jumlah_data, status_topik'))->where('id_batch', $request->input('id_batch'))->orderBy('status_topik')->groupBy('status_topik')->get();
    }





    // ! ################# Koordinator KBK #################
    public function listTopikPeriksaKelayakanTopik()
    {
        // * Topik sudah diterima oleh dosen yang bersangkutan atau Instasi
        $status_persetujuan_dosen = [1, 4];
        // * Topik sudah diterima oleh Koordinator KP
        $status_topik = 1;

        $matchThese = ['status_topik' => $status_topik];

        $kumpulan_topik = Topik::where($matchThese)->whereIn('status_persetujuan_dosen', $status_persetujuan_dosen)->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }

    public function listTopikKelayakan(Request $request)
    {
        // // * Topik sudah diterima oleh dosen yang bersangkutan atau Instasi
        // $status_persetujuan_dosen = [1, 4];
        // * Topik sudah diterima oleh Koordinator KP
        $status_topik = 1;

        $matchThese = ['status_topik' => $status_topik, 'id_batch' => $request->input('id_batch'), 'id_kbk' => $request->input('id_kbk')];

        // $kumpulan_topik = Topik::where($matchThese)->whereIn('status_persetujuan_dosen', $status_persetujuan_dosen)->get();
        $kumpulan_topik = Topik::where($matchThese)->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'status_topik' => $topik->status_topik,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }

    public function listTopikHistoryKelayakan(Request $request)
    {
        // // * Topik sudah diterima oleh dosen yang bersangkutan atau Instasi
        // $status_persetujuan_dosen = [1, 4];
        // * Topik sudah diterima atau ditolak atau direvisi oleh Koordinator KBK
        // $status_topik = 1;

        $status_topik = [4, 5, 6];

        $matchThese = ['id_batch' => $request->input('id_batch'), 'id_kbk' => $request->input('id_kbk')];

        // $kumpulan_topik = Topik::where($matchThese)->whereIn('status_persetujuan_dosen', $status_persetujuan_dosen)->get();
        // $kumpulan_topik = Topik::where($matchThese)->where('status_topik', "!=", $status_topik)->get();
        $kumpulan_topik = Topik::where($matchThese)->whereIn('status_topik', $status_topik)->orderBy('id_topik', 'desc')->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'status_topik' => $topik->status_topik,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }




    public function updateStatusPersetujuanKoorKbk(Request $request)
    {
        // * Jika topik diterima Update Status Topik, Pembimbing ,dan Penguji, Jika topik ditolak Update Status Topik
        $updateThese = ['status_topik' => $request->input('status_topik')];
        if ($request->input('status_topik') == 5) {
            if ($request->input('id_pembimbing'))
                $updateThese = array_merge($updateThese, ['id_pembimbing' => $request->input('id_pembimbing')]);
            if ($request->input('id_pembimbing_lapangan'))
                $updateThese = array_merge($updateThese, ['id_pembimbing_lapangan' => $request->input('id_pembimbing_lapangan')]);
            if ($request->input('id_penguji_sidang'))
                $updateThese = array_merge($updateThese, ['id_penguji_sidang' => $request->input('id_penguji_sidang')]);
            if ($request->input('id_penguji_sidang_dua'))
                $updateThese = array_merge($updateThese, ['id_penguji_sidang_dua' => $request->input('id_penguji_sidang_dua')]);
        }
        Topik::where('id_topik', $request->input('id_topik'))->update($updateThese);
    }
    public function listTopikPembimbingPenguji()
    {
        $kumpulan_topik = Topik::where('status_topik', 5)->get();
        if (sizeof($kumpulan_topik) != 0) {
            foreach ($kumpulan_topik as $topik) {
                $pengguna = Pengguna::where('kode_pengguna', $topik->id_pengaju)->first();
                $data[] = [
                    'id_topik' => $topik->id_topik,
                    'slug_topik' => $topik->slug_topik,
                    'nrp' => $topik->id_pengaju,
                    'judul_topik' => $topik->judul_topik,
                    'nama' => $pengguna != null ? $pengguna->nama_pengguna : $topik->id_pengaju,
                ];
            }
            return $data;
        }
        return ['message' => 'kosong'];
    }
    public function updatePembimbingPengujiTopik(Request $request)
    {
        $updateThese = ['id_pembimbing' => $request->input('id_pembimbing'), 'id_pembimbing_lapangan' => $request->input('id_pembimbing_lapangan'), 'id_penguji_sidang' => $request->input('id_penguji_sidang'), 'id_penguji_sidang_dua' => $request->input('id_penguji_sidang_dua')];
        Topik::where('id_topik', $request->input('id_topik'))->update($updateThese);
    }
}
