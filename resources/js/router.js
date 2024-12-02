import Vue from 'vue';
import Router from 'vue-router';
import store from './store/store.js';
import Home from './views/Home.vue';
import Login from './views/Login.vue';
import Credit from './views/Credit.vue';
import Profile from './views/Profile.vue';
// ? Pengguna
// * Koordinator KP
import JabatanPengguna from './pages/koor_kp/pengguna/Jabatan.vue';
import JabatanPenggunaUbah from './pages/koor_kp/pengguna/UpdateJabatan.vue';
import ResetPasswordPengguna from './pages/koor_kp/pengguna/ResetPassword.vue';
import ImportDataPengguna from './pages/koor_kp/pengguna/ImportPengguna.vue';
// ? Batch
// * Koordinator KP
import BatchSemester from './pages/koor_kp/batch/Semester.vue';
import BatchTopik from './pages/koor_kp/batch/Topik.vue';
import BatchSidang from './pages/koor_kp/batch/Sidang.vue';
// ? Topik
// * Mahasiswa
import AddTopik from './pages/mahasiswa/topik/Add.vue';
import HistoriTopik from './pages/mahasiswa/topik/Index.vue';
import Topik from './pages/mahasiswa/topik/Topik.vue';
import EditTopik from './pages/mahasiswa/topik/Edit.vue';
// * Dosen
import PersetujuanDosen from './pages/dosen/topik/Persetujuan.vue';
import DetailDosen from './pages/dosen/topik/Detail.vue';
// * Koordinator KP
import KelengkapanDokumen from './pages/koor_kp/topik/Persetujuan.vue';
import DetailKelengkapanDokumen from './pages/koor_kp/topik/Detail.vue';
// * Koordinator KBK
import KelayakanTopik from './pages/koor_kbk/topik/Persetujuan.vue';
import DetailKelayakanTopik from './pages/koor_kbk/topik/Detail.vue';
import PembimbingPengujiTopik from './pages/koor_kbk/topik/PembimbingPenguji.vue';
import DetailPembimbingPengujiTopik from './pages/koor_kbk/topik/DetailPembimbingPenguji.vue';
// ? Bimbingan
// * Mahasiswa
import HistoriBimbingan from './pages/mahasiswa/bimbingan/Index.vue';
import AjukanBimbingan from './pages/mahasiswa/bimbingan/Add.vue';
// * Dosen
import KonfirmasiBimbingan from './pages/dosen/bimbingan/Index.vue';
import DetailBimbingan from './pages/dosen/bimbingan/Detail.vue';
// ? Sidang
// * Mahasiswa
import PengajuanSidangMahasiwa from './pages/mahasiswa/sidang/Add.vue';
import AddJadwalMahasiwa from './pages/mahasiswa/sidang/AddJadwal.vue';
import UpdateJadwalMahasiwa from './pages/mahasiswa/sidang/UpdateJadwal.vue';
import HistoriSidangMahasiswa from './pages/mahasiswa/sidang/Index.vue';
import RevisiSidangMahasiswa from './pages/mahasiswa/sidang/Revisi.vue';
import DetailSidangMahasiswa from './pages/mahasiswa/sidang/DetailSidang.vue';
// * Koordinator KP
import PengaturanSidang from './pages/koor_kp/sidang/Index.vue';
import PenjadwalanSidang from './pages/koor_kp/sidang/Penjadwalan.vue';
import ListNilaiMahasiswa from './pages/koor_kp/sidang/IndexPenilaian.vue';
import PenilaianSidang from './pages/koor_kp/sidang/Nilai.vue';
import KomponenPenilaianSidang from './pages/koor_kp/sidang/KomponenPenilaian.vue';
import AddKomponenPenilaianSidang from './pages/koor_kp/sidang/AddKomponenPenilaian.vue';
import SyaratDanKetentuanSidang from './pages/koor_kp/sidang/SyaratDanKetentuan.vue';
import AddSyaratDanKetentuanSidang from './pages/koor_kp/sidang/AddSyaratDanKetentuan.vue';
import PergantianNilaiAkhirSidangKoorKP from './pages/koor_kp/sidang/PergantianNilai.vue';
import LaporanNilaiAkhirSidangKoorKP from './pages/koor_kp/sidang/LaporanNilai.vue';
// * Koordinator KBK
import PengaturanPengujiSidang from './pages/koor_kbk/sidang/PengaturanPenguji.vue';
import ListPengaturanSidang from './pages/koor_kbk/sidang/Index.vue';
// * Dosen
import PersetujuanSidang from './pages/dosen/sidang/Persetujuan.vue';
import DetailSidangBimbingan from './pages/dosen/sidang/DetailBimbingan.vue';
import AcaraSidangDosen from './pages/dosen/sidang/Index.vue';
import FileSidangDosen from './pages/dosen/sidang/File.vue';
import ListRevisiDosen from './pages/dosen/sidang/IndexRevisi.vue';
import DetailRevisiDosen from './pages/dosen/sidang/Revisi.vue';
import ListPembimbingLapangan from './pages/dosen/sidang/IndexPembimbingLapangan.vue';
import NilaiPembimbingLapangan from './pages/dosen/sidang/NilaiPembimbingLapangan.vue';
import PergantianNilaiAkhirSidangDosen from './pages/dosen/sidang/PergantianNilai.vue';

// ? MBKM
// * Mahasiswa
import PendaftaranMBKM from './pages/mahasiswa/mbkm/daftar.vue';
import KonversiSKS from './pages/mahasiswa/mbkm/KonversiSKS.vue';
import Logbook from './pages/mahasiswa/mbkm/logbook.vue';

import AddTopikMBKM from './pages/mahasiswa/topik_mbkm/Add.vue';
import HistoriTopikMBKM from './pages/mahasiswa/topik_mbkm/Index.vue';
import TopikMBKM from './pages/mahasiswa/topik_mbkm/Topik.vue';
import EditTopikMBKM from './pages/mahasiswa/topik_mbkm/Edit.vue';

import HistoriBimbinganMBKM from './pages/mahasiswa/bimbingan_mbkm/Index.vue';
import AjukanBimbinganMBKM from './pages/mahasiswa/bimbingan_mbkm/Add.vue';

import PengajuanSidangMahasiwaMBKM from './pages/mahasiswa/sidang_mbkm/Add.vue';
import AddJadwalMahasiwaMBKM from './pages/mahasiswa/sidang_mbkm/AddJadwal.vue';
import UpdateJadwalMahasiwaMBKM from './pages/mahasiswa/sidang_mbkm/UpdateJadwal.vue';
import HistoriSidangMahasiswaMBKM from './pages/mahasiswa/sidang_mbkm/Index.vue';
import RevisiSidangMahasiswaMBKM from './pages/mahasiswa/sidang_mbkm/Revisi.vue';
import DetailSidangMahasiswaMBKM from './pages/mahasiswa/sidang_mbkm/DetailSidang.vue';

// * KoorKP
import KonfirmasiMBKM from './pages/koor_kp/mbkm/Konfirmasi.vue';
import KonversiMBKM from './pages/koor_kp/mbkm/DetailKonfirmasi.vue';
import ListMataKuliah from './pages/koor_kp/mbkm/MataKuliah.vue';
import ListLogbook from './pages/koor_kp/mbkm/Logbook.vue';
import JenisMbkm from './pages/koor_kp/mbkm/JenisMbkm.vue';

Vue.use(Router)
const router = new Router({
    mode: 'history',
    routes: [{
        path: '/',
        name: 'home',
        component: Home,
        alias: ['/home', '/beranda']
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    // {
    //     path: '/topik',
    //     component: IndexTopik,
    // },
    {
        path: '/profile',
        component: Profile,
        meta: {
            requiresAuth: true
        }
    },
    // ? Pengguna
    // * Koordinator KP
    {
        path: '/pengguna/jabatan',
        component: JabatanPengguna,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/pengguna/jabatan/update/:id',
        component: JabatanPenggunaUbah,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/pengguna/reset-password',
        component: ResetPasswordPengguna,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/pengguna/import-data',
        component: ImportDataPengguna,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    // ? Batch
    // * Koordinator KP
    {
        path: '/batch-semester',
        component: BatchSemester,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/batch-pengajuan-topik',
        component: BatchTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/batch-pengajuan-sidang',
        component: BatchSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },

    // ? Topik
    // * Mahasiswa
    {
        path: '/topik/histori',
        component: HistoriTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/topik/histori/:id',
        component: Topik,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/topik/revisi/:id',
        component: EditTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/topik/pengajuan',
        component: AddTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    // * Dosen
    {
        path: '/topik/persetujuan',
        component: PersetujuanDosen,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    {
        path: '/topik/persetujuan/:id',
        component: DetailDosen,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    // * Koordinator KP
    {
        path: '/topik/kelengkapan-dokumen',
        component: KelengkapanDokumen,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    }, {
        path: '/topik/kelengkapan-dokumen/:id',
        component: DetailKelengkapanDokumen,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    }, {
        path: '/topik/pembimbing',
        component: PembimbingPengujiTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    }, {
        path: '/topik/pembimbing/:id',
        component: DetailPembimbingPengujiTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    // * Koordinator KBK 
    {
        path: '/topik/kelayakan-topik',
        component: KelayakanTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KBK"
        }
    }, {
        path: '/topik/kelayakan-topik/:id',
        component: DetailKelayakanTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KBK"
        }
    }, {
        path: '/topik/pengaturan-pembimbing',
        component: PembimbingPengujiTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KBK"
        }
    }, {
        path: '/topik/pengaturan-pembimbing/:id',
        component: DetailPembimbingPengujiTopik,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KBK"
        }
    },
    // {
    //     path: 'topik/ubah/:id_topik',
    //     component: EditTopik
    // },
    // ? Bimbingan
    // * Dosen
    {
        path: '/bimbingan/konfirmasi',
        component: KonfirmasiBimbingan,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    {
        path: '/bimbingan/detail/:id',
        component: DetailBimbingan,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    // * Mahasiswa
    {
        path: '/bimbingan/:id',
        component: HistoriBimbingan,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/bimbingan/ajukan/:id',
        component: AjukanBimbingan,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    // ? Sidang
    // * Koordinator KBK
    {
        path: '/sidang/pengaturan-penguji',
        component: ListPengaturanSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KBK"
        }
    },
    {
        path: '/sidang/pengaturan-penguji/:id',
        component: PengaturanPengujiSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KBK"
        }
    },
    // * Dosen
    {
        path: '/sidang/persetujuan',
        component: PersetujuanSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    {
        path: '/sidang/list-revisi',
        component: ListRevisiDosen,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    {
        path: '/sidang/bimbingan/:id',
        component: DetailSidangBimbingan,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    {
        path: '/sidang/pembimbing-lapangan',
        component: ListPembimbingLapangan,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/pembimbing-lapangan/:id',
        component: NilaiPembimbingLapangan,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/list-revisi/:id',
        component: DetailRevisiDosen,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    {
        path: '/sidang/acara',
        component: AcaraSidangDosen,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    {
        path: '/sidang/acara/:id',
        component: FileSidangDosen,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    {
        path: '/sidang/pergantian-nilai-akhir-sidang',
        component: PergantianNilaiAkhirSidangDosen,
        meta: {
            requiresAuth: true,
            jenisUser: "Dosen"
        }
    },
    // * Koordinator KP
    {
        path: '/sidang/penguji',
        component: ListPengaturanSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/penguji/:id',
        component: PengaturanPengujiSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/penjadwalan',
        component: PengaturanSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/komponen-penilaian',
        component: KomponenPenilaianSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/komponen-penilaian/add',
        component: AddKomponenPenilaianSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/syarat-dan-ketentuan',
        component: SyaratDanKetentuanSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/syarat-dan-ketentuan/add',
        component: AddSyaratDanKetentuanSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/penjadwalan/:id',
        component: PenjadwalanSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/penilaian',
        component: ListNilaiMahasiswa,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/penilaian/:id',
        component: PenilaianSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/koor-kp-pergantian-nilai-akhir-sidang',
        component: PergantianNilaiAkhirSidangKoorKP,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/sidang/laporan-nilai',
        component: LaporanNilaiAkhirSidangKoorKP,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    // * Mahasiswa
    {
        path: '/sidang',
        component: HistoriSidangMahasiswa,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang/detail/:id',
        component: DetailSidangMahasiswa,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang/revisi/:id',
        component: RevisiSidangMahasiswa,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang/jadwal-mahasiswa/:id',
        component: AddJadwalMahasiwa,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang/update-jadwal-mahasiswa/:id',
        component: UpdateJadwalMahasiwa,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang/:id',
        component: PengajuanSidangMahasiwa,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    // * Tata Usaha
    {
        path: '/penjadwalan/sidang',
        component: PengaturanSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Tata Usaha"
        }
    },
    {
        path: '/penjadwalan/sidang/:id',
        component: PenjadwalanSidang,
        meta: {
            requiresAuth: true,
            jenisUser: "Tata Usaha"
        }
    },
    /////////// MBKM //////////////////
    // ! Mahasiswa
    {
        path: '/mbkm/pendaftaran',
        component: PendaftaranMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/mbkm/konversi',
        component: KonversiSKS,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/mbkm/logbook',
        component: Logbook,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang-mbkm',
        component: HistoriSidangMahasiswaMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang-mbkm/detail/:id',
        component: DetailSidangMahasiswaMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang-mbkm/revisi/:id',
        component: RevisiSidangMahasiswaMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang-mbkm/jadwal-mahasiswa/:id',
        component: AddJadwalMahasiwaMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang-mbkm/update-jadwal-mahasiswa/:id',
        component: UpdateJadwalMahasiwaMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/sidang-mbkm/:id',
        component: PengajuanSidangMahasiwaMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/bimbingan-mbkm/:id',
        component: HistoriBimbinganMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/bimbingan-mbkm/ajukan/:id',
        component: AjukanBimbinganMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/topik-mbkm/histori',
        component: HistoriTopikMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/topik-mbkm/histori/:id',
        component: TopikMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/topik-mbkm/revisi/:id',
        component: EditTopikMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },
    {
        path: '/topik-mbkm/pengajuan',
        component: AddTopikMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Mahasiswa"
        }
    },

    ////////////////// KOORKP/////////////////////////////
    {
        path: '/mbkm/konfirmasi',
        component: KonfirmasiMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/mbkm/detail-konversi/:id',
        name: 'KonversiMBKM',
        component: KonversiMBKM,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/mbkm/mata-kuliah',
        component: ListMataKuliah,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/mbkm/jenis-mbkm',
        component: JenisMbkm,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/mbkm/list-logbook',
        component: ListLogbook,
        meta: {
            requiresAuth: true,
            jenisUser: "Koordinator KP"
        }
    },
    {
        path: '/*',
        name: 'credit',
        component: Credit
    },
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            if (to.meta.jenisUser) {
                if (store.getters.jabatan.length == 0) {
                    let id_pengguna = store.getters.pengguna.UserId;
                    store
                        .dispatch("jabatan", { id_pengguna })
                        .then(response => {
                            if (store.getters.jabatan.includes(to.meta.jenisUser)) {
                                next()
                            }
                            else {
                                next('/credit')
                            }

                        })
                        .catch(response => {
                            console.log(response);
                        });
                } else {
                    if (store.getters.jabatan.includes(to.meta.jenisUser)) {
                        next()
                    }
                    else {
                        next('/credit')
                    }
                }
            } else {
                next()
            }
        }
        else {
            next('/login')
        }

    } else {
        next()
    }
});
export default router;