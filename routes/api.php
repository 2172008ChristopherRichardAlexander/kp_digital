<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// ! API : ####### Batch #######
// Periksa Aktifasi Batch
// * Digunakan untuk Cron Job pada Server
Route::get('batch/cek-aktifasi', 'BatchController@aktifasiBatch');
// List Batch
Route::get('batch-list/{jenis}', 'BatchController@index');
// List Batch Aktif
Route::get('batch/aktif/{jenis}', 'BatchController@aktif');
// List Batch Belum Aktif
Route::get('batch/belum-aktif/{jenis}', 'BatchController@belumAktif');
// Get Newest Batch
Route::get('batch/newest/{jenis}', 'BatchController@newest');
// Get Latest Batch
Route::get('batch/latest/{jenis}', 'BatchController@latest');
// List Single Batch
Route::get('batch/{id}', 'BatchController@show');
// Create New Batch
Route::post('batch', 'BatchController@store');
// Update Batch
Route::put('batch', 'BatchController@store');
// List Batch Berdasarkan Jenis, Status
Route::post('batch/daftar', 'BatchController@listBatch');
// List Batch Berdasarkan Semester, Jenis, Status
Route::post('batch/semester/daftar', 'BatchController@listBatchSemester');
// Batch untuk pengajuan
Route::post('batch/pengajuan', 'BatchController@pengajuan');

// ! API : ####### Semester #######
// Periksa Aktifasi Semester
// * Digunakan untuk Cron Job pada Server
Route::get('semester/cek-aktifasi', 'SemesterController@aktifasiSemester');
// Create New Semester
Route::post('semester', 'SemesterController@store');
// Update Semester
Route::put('semester', 'SemesterController@store');
// List Semester
Route::get('semester/list', 'SemesterController@index');
// List Semester Aktif
Route::get('semester/aktif', 'SemesterController@aktif');
// List Semester Berdasarkan Status
Route::post('semester/list-status', 'SemesterController@listSemester');

// ! API : ####### Bimbingan #######
// List Bimbingan
Route::get('bimbingan', 'BimbinganController@index');
// ? PENGGUNA : ####### MAHASISWA #######
// List Single Bimbingan
Route::get('mahasiswa/bimbingan/{id}', 'BimbinganController@show');
// List Bimbingan Berdasarkan Topik
Route::post('mahasiswa/bimbingan-topik', 'BimbinganController@bimbinganTopik');
// List Bimbingan yang Sudah di Approve
Route::post('mahasiswa/list-bimbingan-konfirmasi', 'BimbinganController@bimbinganKonfirmasi');
// Create New Bimbingan
Route::post('mahasiswa/bimbingan', 'BimbinganController@store');
// Update Bimbingan
Route::put('mahasiswa/bimbingan', 'BimbinganController@store');

// ? PENGGUNA : ####### Dosen #######
// List Bimbingan Berdasarkan Pembimbing dan Batch
Route::post('dosen/bimbingan', 'BimbinganController@listBimbingan');
// Update Status Bimbingan
Route::put('dosen/bimbingan', 'BimbinganController@konfirmasiBimbingan');
Route::put('dosen/bimbingan/list', 'BimbinganController@konfirmasiListBimbingan');
// List Bimbingan Berdasarkan Topik
Route::post('dosen/bimbingan/topik', 'BimbinganController@bimbinganTopik');
Route::post('dosen/bimbingan/topik-status', 'BimbinganController@bimbinganTopikStatus');


// ! API : ####### Jabatan #######
// List Jabatan
Route::get('jabatan', 'JabatanController@index');
// List Jabatan Berdasarkan Status
Route::post('jabatan/list', 'JabatanController@listJabatanStatus');
// List Single Jabatan
Route::get('jabatan/{id}', 'JabatanController@show');
// Create New Jabatan
Route::post('jabatan', 'JabatanController@store');
// Update Jabatan
Route::put('jabatan', 'JabatanController@store');

// ! API : ####### Jabatan Pengguna #######
// List Jabatan Pengguna
Route::post('jabatan-pengguna/list', 'JabatanPenggunaController@listJabatanPengguna');
// Delete Jabatan Pengguna Berdasarkan Id
Route::post('jabatan-pengguna/delete', 'JabatanPenggunaController@deleteJabatanPengguna');
// Create New Jabatan Pengguna
Route::post('jabatan-pengguna/insert', 'JabatanPenggunaController@store');
// List Jabatan Pengguna Dosen
Route::get('dosen', 'JabatanPenggunaController@dosen');
// List Jabatan Pengguna Mahasiswa
Route::get('mahasiswa', 'JabatanPenggunaController@mahasiswa');
// List Jabatan Pengguna Berdasarkan Id Kbk
Route::get('jabatan-pengguna/kbk/{id_kbk}', 'JabatanPenggunaController@getJabatanPenggunaKbk');
// List Single Jabatan Pengguna
Route::get('jabatanPengguna/{id}', 'JabatanPenggunaController@show');
// Create New Jabatan Pengguna
Route::post('jabatanPengguna', 'JabatanPenggunaController@store');
// Update Jabatan Pengguna
Route::put('jabatanPengguna', 'JabatanPenggunaController@store');
// First or Create Jabatan Pengguna Pertama Kali Log In
Route::put('jabatanPengguna/firstLogin', 'JabatanPenggunaController@firstLogin');

// ! API : ####### Jadwal Mahasiswa #######
// ? PENGGUNA : ####### Mahasiswa #######
// List Jadwal Mahasiswa Berdasarkan Topik
Route::post('mahasiswa/list-jadwal-mahasiswa', 'JadwalMahasiswaController@listJadwalMahasiswa');
// Create New Jadwal Mahasiswa
Route::post('mahasiswa/insert-jadwal-mahasiswa', 'JadwalMahasiswaController@store');
// Get One Jadwal Mahasiswa
Route::get('mahasiswa/jadwal-mahasiswa/{id_jadwal_mahasiswa}', 'JadwalMahasiswaController@getJadwalMahasiswa');
// Update Jadwal Mahasiswa
Route::post('mahasiswa/jadwal-mahasiswa/update', 'JadwalMahasiswaController@updateJadwalMahasiswa');
// Delete Jadwal Mahasiswa
Route::post('mahasiswa/jadwal-mahasiswa/delete', 'JadwalMahasiswaController@deleteJadwalMahasiswa');

// ? PENGGUNA : ####### Koordinator KP #######
// List Jadwal Mahasiswa Berdasarkan Topik
Route::post('koorkp/list-jadwal-batch-topik', 'JadwalMahasiswaController@listJadwalBatchTopik');

// ! API : ####### Jenis Sidang #######
// List Jenis Sidang
Route::get('jenis-sidang', 'JenisSidangController@index');
// Get Newest Jenis Sidang
Route::get('jenis-sidang/newest', 'JenisSidangController@newest');
// List Single Jenis Sidang
Route::get('jenis-sidang/{id}', 'JenisSidangController@show');
// Create New Jenis Sidang
Route::post('jenis-sidang', 'JenisSidangController@store');
// Update Jenis Sidang
Route::put('jenis-sidang', 'JenisSidangController@store');

// ! API : ####### Jurusan #######
// List Jurusan
Route::get('jurusan', 'JurusanController@index');
// List Single Jurusan
Route::get('jurusan/{id}', 'JurusanController@show');
// Create New Jurusan
Route::post('jurusan', 'JurusanController@store');
// Update Jurusan
Route::put('jurusan', 'JurusanController@store');

// ! API : ####### Kbk #######
// List Kbk
Route::get('kbk', 'KbkController@index');
// List Kbk Aktif
Route::get('kbk-aktif', 'KbkController@aktif');
// List Single Kbk
Route::get('kbk/{id}', 'KbkController@show');
// Get Kbk Berdasarkan Nama Kbk
Route::post('kbk-nama', 'KbkController@getKbkNama');
// Create New Kbk
Route::post('kbk', 'KbkController@store');
// Update Kbk
Route::put('kbk', 'KbkController@store');

// ! API : ####### Komentar #######
// List Komentar
Route::get('komentar_topik/{id_topik}', 'KomentarController@index');
// List Single Komentar
Route::get('komentar/{id}', 'KomentarController@show');
// Create New Komentar
Route::post('komentar', 'KomentarController@store');
// Update Komentar
Route::put('komentar', 'KomentarController@store');

// ! API : ####### Komponen Nilai #######
// List Komponen Nilai
Route::get('komponen-nilai', 'KomponenNilaiController@index');
// Get Newest Komponen Nilai
Route::get('komponen-nilai/newest', 'KomponenNilaiController@newest');
// List Single Komponen Nilai
Route::get('komponen-nilai/{id}', 'KomponenNilaiController@show');
// Create New Komponen Nilai
Route::post('komponen-nilai', 'KomponenNilaiController@store');
// Update Komponen Nilai
Route::put('komponen-nilai', 'KomponenNilaiController@store');

// ! API : ####### Pengguna #######
// List Pengguna
Route::get('pengguna', 'PenggunaController@index');
// Create New Pengguna
Route::post('pengguna', 'PenggunaController@store');
// Update Pengguna
Route::put('pengguna', 'PenggunaController@store');
// List Single Pengguna
Route::get('pengguna/{id}', 'PenggunaController@show');
// // Delete Pengguna
// Route::delete('pengguna/{id}','PenggunaController@destroy');


// ! API : ####### Role #######
// List Role
Route::get('role', 'RoleController@index');
// List Role Pengguna
Route::get('role/pengguna', 'RoleController@listRolePengguna');
// List Single Role
Route::get('role/{id}', 'RoleController@show');
// Create New Role
Route::post('role', 'RoleController@store');
// Update Role
Route::put('role', 'RoleController@store');

// ? PENGGUNA : ####### Koordinator KP #######
// List Role Komponen Penilaian
Route::get('koorkp/role/komponen-penilaian', 'RoleController@listRoleKomponenPenilaian');
// List Role Syarat Ketentuan Sidang
Route::get('koorkp/role/syarat-ketentuan', 'RoleController@listRoleSyaratKetentuan');


// ! API : ####### Sidang #######
// ! List Sidang Bedasarkan Status Sidang, Batch
Route::post('sidang/list-sidang', 'SidangController@listSidang');
// List Sidang
Route::get('sidang', 'SidangController@index');
// List Single Sidang
Route::get('sidang/{id}', 'SidangController@show');
// Create New Sidang
Route::post('sidang', 'SidangController@store');
// Update Sidang
Route::put('sidang', 'SidangController@store');
// Show Sidang Topik
Route::post('sidang-topik', 'SidangController@sidangTopik');
// List Hasil Akhir Sidang dan Status
Route::post('sidang/hasil-sidang', 'SidangController@listHasilSidang');


// ? PENGGUNA : ####### MAHASISWA #######
// List Sidang Pengaju
Route::post('mahasiswa/list-sidang', 'SidangController@listSidangPengaju');
Route::post('mahasiswa/sidang/detail', 'SidangController@detailSidang');
Route::post('mahasiswa/sidang/revisi', 'SidangController@revisiSidang');
Route::put('mahasiswa/sidang/revisi/konfirmasi', 'SidangController@konfirmasiRevisi');
// Update Revisi Sidang
Route::post('mahasiswa/sidang/revisi/update', 'SidangController@updateRevisiSidang');

// ? PENGGUNA : ####### Koordinator KP #######
// List Pengajuan Sidang Topik
Route::post('koorkp/list-pengajuan-sidang', 'SidangController@listPengajuanSidang');
// Detail Sidang Topik
Route::post('koorkp/sidang-detail', 'SidangController@detailSidang');
Route::post('koorkp/sidang/detail', 'SidangController@detailSidangMahasiswa');
// List Mahasiswa
Route::post('koorkp/sidang/list-mahasiswa', 'SidangController@listMahasiswa');
// List Daftar Mahasiswa Berdasarkan Status Pembimbing, Id Batch
Route::post('koorkp/sidang/daftar-mahasiswa', 'SidangController@daftarMahasiswa');
// Update Nilai Mahasiswa
Route::put('koorkp/sidang/update-nilai', 'SidangController@updateNilaiMahasiswa');
// Delete Sidang Mahasiswa
Route::post('koorkp/sidang/delete', 'SidangController@deleteSidangMahasiswa');

// ? PENGGUNA : ####### Koordinator KBK #######
// List Pengajuan Sidang Topik Berdasarkan Batch dan KBK
Route::post('koorkbk/sidang/batch-kbk', 'SidangController@listSidangBatchKbk');
Route::put('koorkbk/sidang/penguji', 'SidangController@updatePenguji');

// ? PENGGUNA : ####### Dosen #######
// List Persetujuan Sidang Pembimbing Utama
Route::post('dosen/sidang/list-persetujuan-pembimbing-utama', 'SidangController@listPersetujuanSidangPembimbingUtama');
// list Persetujuan Sidang Pembimbing Lapangan
Route::post('dosen/sidang/list-persetujuan-pembimbing-lapangan', 'SidangController@listPersetujuanSidangPembimbingLapangan');
// List History Persetujuan Sidang Pembimbing Utama
Route::post('dosen/sidang/list-history-persetujuan-pembimbing-utama', 'SidangController@listHistoryPersetujuanSidangPembimbingUtama');
// list History Persetujuan Sidang Pembimbing Lapangan
Route::post('dosen/sidang/list-history-persetujuan-pembimbing-lapangan', 'SidangController@listHistoryPersetujuanSidangPembimbingLapangan');
// Update Persetujuan Sidang Pembimbing Utama
Route::put('dosen/sidang/update-persetujuan-pembimbing-utama', 'SidangController@updateStatusSidangPembimbingUtama');
// Update Persetujuan Sidang Pembimbing Lapangan
Route::put('dosen/sidang/update-persetujuan-pembimbing-lapangan', 'SidangController@updateStatusSidangPembimbingLapangan');
// List Sidang Sebagai Pembimbing Utama
Route::post('dosen/sidang/list-pembimbing-utama', 'SidangController@listPembimbingUtama');
// List History Sidang Sebagai Pembimbing Utama
Route::post('dosen/sidang/list-history-pembimbing-utama', 'SidangController@listHistoryPembimbingUtama');
// List Sidang Sebagai Pembimbing Lapangan
Route::post('dosen/sidang/list-pembimbing-lapangan', 'SidangController@listPembimbingLapangan');
// List Sidang Sebagai Penguji
Route::post('dosen/sidang/list-penguji', 'SidangController@listPenguji');
// List History Sidang Sebagai Penguji
Route::post('dosen/sidang/list-history-penguji', 'SidangController@listHistoryPenguji');
// Detail Sidang
Route::post('dosen/sidang-detail', 'SidangController@detailSidang');
Route::post('dosen/sidang/detail', 'SidangController@detailInfoSidang');
// Penilaian Pembimbing Lapangan
Route::post('dosen/sidang/penilaian-pembimbing-lapangan', 'SidangController@penilaianPembimbingLapangan');
// Penilaian Revisi Sidang
Route::post('dosen/sidang/penilaian-revisi', 'SidangController@penilaianRevisi');
// Penilaian Revisi Sidang
Route::post('dosen/sidang/hasil-penilaian-revisi', 'SidangController@hasilPenilaianRevisi');
// Konfirmasi Revisi
Route::put('dosen/sidang/revisi/konfirmasi', 'SidangController@konfirmasiRevisi');
// List Konfirmasi Revisi Sidang Sebagai Pembimbing Utama
Route::post('dosen/sidang/list-konfirmasi-revisi-pembimbing-utama', 'SidangController@listKonfirmasiRevisiPembimbingUtama');
// List Konfirmasi Revisi Sidang Sebagai Penguji
Route::post('dosen/sidang/list-konfirmasi-revisi-penguji', 'SidangController@listKonfirmasiRevisiPenguji');
// Update Revisi Sidang
Route::post('dosen/sidang/revisi/update', 'SidangController@updateRevisiSidang');

// ! API : ####### Topik #######
// ! List Topik Bedasarkan Role,Status Topik, Status Persetujuan Dosen, Batch
Route::post('topik/list-topik', "TopikController@listTopik");

// ? PENGGUNA : ####### MAHASISWA #######
// ! List Topik
Route::get('mahasiswa/topik', 'TopikController@index');
// *  List Hisori Topik Berdasarkan Mahasiswa
Route::post('mahasiswa/histori-topik', 'TopikController@listHistoriMahasiswa');
// * Detail Single Topik
Route::get('mahasiswa/topik/{slug}', 'TopikController@show');
// * Create New Topik
Route::post('mahasiswa/topik', 'TopikController@store');
// Update Topik
Route::put('mahasiswa/topik', 'TopikController@store');
// * Update Gambar
Route::post('mahasiswa/topik/update-gambar', 'TopikController@updateGambar');
// * Get Last Pengajuan Topik
Route::post('mahasiswa/topik/terbaru', 'TopikController@getTopikTerbaruPengguna');
// * Get Topik untuk Sidang
Route::post('mahasiswa/topik/sidang', 'TopikController@getTopikSidang');

// ? PENGGUNA : ####### Dosen #######
// List Topik Persetujuan
Route::get('dosen/topik', 'TopikController@listTopikPersetujuan');
// List Topik Permintaan Persetujuan
Route::post('dosen/topik-permintaan-persetujuan', 'TopikController@listTopikPermintaanPersetujuan');
// List Topik History Permintaan Persetujuan
Route::post('dosen/topik-history-permintaan-persetujuan', 'TopikController@listTopikHistoryPermintaanPersetujuan');
// List Mahasiswa Bimbingan
Route::post('dosen/topik/mahasiswa-bimbingan', 'TopikController@listMahasiswaBimbingan');
// Detail Single Topik
Route::get('dosen/topik/{slug}', 'TopikController@show');
Route::get('dosen/id-topik/{id_topik}', 'TopikController@showFromId');
// Update Status Persetujuan Dosen
Route::put('dosen/topik', 'TopikController@updateStatusPersetujuanDosen');

// ? PENGGUNA : ####### Koordinator KP #######
// List Topik Periksa Kelengkapan Dokumen
Route::get('koorkp/topik', 'TopikController@listTopikPeriksaKelengkapanDokumen');
// Detail Single Topik
Route::get('koorkp/topik/{slug}', 'TopikController@show');
// List Topik Pemeriksaan Dokumen
Route::post('koorkp/topik-pemeriksaan-dokumen', 'TopikController@listTopikPemeriksaanDokumen');
// List Topik History Pemeriksaan Dokumen
Route::post('koorkp/topik-history-pemeriksaan-dokumen', 'TopikController@listTopikHistoryPemeriksaanDokumen');
// Update Status Persetujuan Koordinator KP
Route::put('koorkp/topik', 'TopikController@updateStatusPersetujuanKoorKp');
// Update KBK
Route::put('koorkp/topik-update-kbk', 'TopikController@updateKbk');

// ? PENGGUNA : ####### Koordinator KBK #######
// List Topik Periksa Kelayakan Topik
Route::get('koorkbk/topik', 'TopikController@listTopikPeriksaKelayakanTopik');
// List Topik Pembimbing Penguji
Route::get('koorkbk/topik-pembimbing-penguji', 'TopikController@listTopikPembimbingPenguji');
// List Topik Kelayakan
Route::post('koorkbk/topik-kelayakan', 'TopikController@listTopikKelayakan');
// List Topik History Kelayakan
Route::post('koorkbk/topik-history-kelayakan', 'TopikController@listTopikHistoryKelayakan');
// Update Pembimbing dan Penguji Topik
Route::put('koorkbk/topik-pembimbing-penguji', 'TopikController@updatePembimbingPengujiTopik');
// // Detail Single Sidang Berdasarkan Topik
// Route::post('koorkbk/topik-sidang','SidangController@sidangTopik');
// Detail Single Topik
Route::get('koorkbk/topik/{slug}', 'TopikController@show');
// Update Status Persetujuan Koordinator KBK dan Pembimbing, Penguji jika topik diterima
Route::put('koorkbk/topik', 'TopikController@updateStatusPersetujuanKoorKbk');
// Update KBK
Route::put('koorkbk/topik-update-kbk', 'TopikController@updateKbk');



// List Topik Persetujuan
// ? PENGGUNA : ####### KBK #######
// // List Topik Persetujuan
// Route::get('koorkbk/topik', 'TopikController@listTopikPersetujuan');
// // Update Status Persetujuan
// Route::put('koorkbk/topik', 'TopikController@updateStatusPersetujuan');



// ! API : ####### Komponen Penilaian #######
// List Komponen Penilaian Aktif
Route::get('komponen-penilaian/aktif', 'KomponenPenilaianController@komponenPenilaianAktif');
// Single Detail Komponen Penilaian
Route::get('komponen-penilaian/{id}', 'KomponenPenilaianController@show');

// ? PENGGUNA : ####### Koordinator KP #######
// List Komponen Penilaian
Route::get('koorkp/komponen-penilaian', 'KomponenPenilaianController@index');
// List Komponen Penilaian Berdasarkan Role paginate
Route::get('koorkp/komponen-penilaian/{role}', 'KomponenPenilaianController@listKomponenPenilaianRole');
// List Komponen Penilaian Berdasarkan Role dan Status paginate
Route::post('koorkp/komponen-penilaian-role-status', 'KomponenPenilaianController@listKomponenPenilaian');
// Create New Komponen Penilaian
Route::post('koorkp/komponen-penilaian', 'KomponenPenilaianController@store');
// Update Komponen Penilaian
Route::put('koorkp/komponen-penilaian', 'KomponenPenilaianController@store');

// ? PENGGUNA : ####### Dosen #######
// List Semua Komponen Penilaian Berdasarkan Role
Route::get('dosen/komponen-penilaian/{role}', 'KomponenPenilaianController@komponenPenilaian');
// List Semua Komponen Penilaian Aktif Berdasarkan Role
Route::post('dosen/komponen-penilaian', 'KomponenPenilaianController@komponenPenilaianRole');

// ! API : ####### Syarat dan Ketentuan Sidang #######
// List Syarat dan Ketentuan Sidang Aktif Berdasarkan Role
Route::get('syarat-ketentuan-aktif/{role}', 'SyaratKetentuanSidangController@listSyaratKetentuanSidangAktifRole');

// ? PENGGUNA : ####### Koordinator KP #######
// List Syarat dan Ketentuan Sidang
Route::get('koorkp/syarat-ketentuan', 'SyaratKetentuanSidangController@index');
// List Syarat dan Ketentuan Sidang Berdasarkan Role paginate
Route::get('koorkp/syarat-ketentuan/{role}', 'SyaratKetentuanSidangController@listSyaratKetentuanSidangRole');
// List Syarat dan Ketentuan Sidang Aktif Berdasarkan Role
Route::get('koorkp/syarat-ketentuan-aktif/{role}', 'SyaratKetentuanSidangController@listSyaratKetentuanSidangAktifRole');
// Create New Syarat dan Ketentuan Sidang
Route::post('koorkp/syarat-ketentuan', 'SyaratKetentuanSidangController@store');
// Update Syarat dan Ketentuan Sidang
Route::put('koorkp/syarat-ketentuan', 'SyaratKetentuanSidangController@store');


// ! API : ####### Komponen Penilaian Sidang #######
// Create New Komponen Penilaian Sidang
Route::post('komponen-penilaian-sidang', 'KomponenPenilaianSidangController@store');
// Create New Data Komponen Penilaian Sidang
Route::post('komponen-penilaian-sidang/data', 'KomponenPenilaianSidangController@insertDataKomponenPenilaian');
// Get List Komponen Penilaian Sidang Berdasarkan Sidang dan Role
Route::post('komponen-penilaian-sidang/role', 'KomponenPenilaianSidangController@getKomponenPenilaianSidangRole');
// Update Nilai Komponen Penilaian Sidang
Route::put('komponen-penilaian-sidang/update-nilai', 'KomponenPenilaianSidangController@updateKomponenPenilaianSidang');

// ! API : ####### Pergantian Nilai Akhir Sidang #######
// Create New Pergantian Nilai Akhir Sidang
Route::post('pergantian-nilai-akhir-sidang', 'PergantianNilaiAkhirSidangController@store');
// List Pergantian Nilai Akhir Sidang Berdasarkan Role
Route::post('pergantian-nilai-akhir-sidang/list-role', 'PergantianNilaiAkhirSidangController@listPergantianNilaiSidangAkhirRole');
// List Pergantian Nilai Akhir Sidang Berdasarkan Role dan Sidang
Route::post('pergantian-nilai-akhir-sidang/list-role-sidang', 'PergantianNilaiAkhirSidangController@listPergantianNilaiSidangAkhirSidang');
// Data Pergantian Nilai Akhir Sidang Berdasarkan Sidang dan Sudah disetujui Pembimbing, Penguji, Penguji Dua, Koordinator KP
Route::post('pergantian-nilai-akhir-sidang/sidang-disetujui', 'PergantianNilaiAkhirSidangController@pergantianNilaiSidangTerakhirDiSetujui');
// Route::get('pergantian-nilai-akhir-sidang/sidang-disetujui/{id_sidang}', 'PergantianNilaiAkhirSidangController@getPergantianNilaiSidangTerakhirDiSetujui');
// Update Nilai Awal dan Akhir Pergantian Nilai Akhir Sidang
Route::post('pergantian-nilai-akhir-sidang/update-nilai', 'PergantianNilaiAkhirSidangController@updateNilai');
// Update Status Pergantian Nilai Akhir Sidang
Route::post('pergantian-nilai-akhir-sidang/update-status', 'PergantianNilaiAkhirSidangController@updateStatus');
Route::post('pergantian-nilai-akhir-sidang/update-status-koor-kp', 'PergantianNilaiAkhirSidangController@updateStatusKoorKp');


// ! API : ####### Stepper #######
// ? PENGGUNA : ####### Koordinator KP #######
Route::post('koorkp/stepper/topik', 'TopikController@stepperTopik');
Route::post('koorkp/stepper/sidang', 'SidangController@stepperSidang');


// ! API : ####### Users #######
// ? PENGGUNA : ####### All User #######
Route::get('Users/GetFiltered', 'UsersController@GetFiltered');
Route::get('Users/GetFilteredV2', 'UsersController@GetFilteredV2');
Route::post('Users/ResetPass', 'UsersController@ResetPassword');
Route::post('Users/UpdatePass', 'UsersController@UpdatePassword');
Route::post('Users/Login', 'UsersController@login');
Route::post('Users/Login_V2', 'UsersController@loginV2');
Route::get('Users/Login_V3','UsersController@loginV3');
Route::get('Users/GetProfile', 'UsersController@GetProfile');
Route::post('Users/UpdateProfile', 'UsersController@UpdateProfile');
// ? PENGGUNA : ####### Koordinator KP #######
Route::post('koorkp/Users/register-pengguna', 'UsersController@registerUsers');

// ! API : ####### MBKM #######
Route::get('mbkm/pendaftaran', 'MBKMController@index');