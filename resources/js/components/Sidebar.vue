<template>
  <aside :class="{ active: active }" v-if="sideBar">
    <b-nav vertical>
      <div class="judul">
        <h4>
          <router-link class="sidebar-title-item" to="/">Kerja Praktek</router-link>
        </h4>
      </div>
      <!-- // ? Koor KP -->
      <template v-if="cekJabatan('Koordinator KP')">
        <!-- // * ####### PENGGUNA ####### -->
        <div class="sidebar-menu">Pengguna</div>
        <router-link to="/pengguna/jabatan" class="nav-link sidebar-menu-item">Pengaturan Jabatan</router-link>
        <router-link to="/pengguna/reset-password" class="nav-link sidebar-menu-item">Reset Password</router-link>
        <router-link to="/pengguna/import-data" class="nav-link sidebar-menu-item">Import Pengguna</router-link>
        <!-- // * ####### BATCH ####### -->
        <div class="sidebar-menu">Batch KP</div>
        <router-link to="/batch-semester" class="nav-link sidebar-menu-item">Semester KP</router-link>
        <router-link to="/batch-pengajuan-topik" class="nav-link sidebar-menu-item">Pengajuan Topik</router-link>
        <router-link to="/batch-pengajuan-sidang" class="nav-link sidebar-menu-item">Pengajuan Sidang</router-link>
      </template>
      <!-- // * ####### TOPIK ####### -->
      <!-- // ? Koor KBK -->
      <template v-if="cekJabatan('Koordinator KP')">
        <div class="sidebar-menu">Koordinator KBK</div>
        <router-link to="/topik/kelayakan-topik" class="nav-link sidebar-menu-item"
          v-if="cekJabatan('Koordinator KBK')">Kelayakan Topik</router-link>
        <router-link to="/topik/pembimbing" class="nav-link sidebar-menu-item">Pengaturan Pembimbing</router-link>
        <router-link to="/sidang/penguji" class="nav-link sidebar-menu-item">Pengaturan Penguji</router-link>
      </template>
      <template v-else-if="cekJabatan('Koordinator KBK')">
        <div class="sidebar-menu">Koordinator KBK</div>
        <router-link to="/topik/kelayakan-topik" class="nav-link sidebar-menu-item">Kelayakan Topik</router-link>
        <router-link to="/topik/pengaturan-pembimbing" class="nav-link sidebar-menu-item">Pengaturan
          Pembimbing</router-link>
        <router-link to="/sidang/pengaturan-penguji" class="nav-link sidebar-menu-item">Pengaturan Penguji</router-link>
      </template>
      <div class="sidebar-menu">Topik</div>
      <!-- // ? Mahasiswa -->
      <template v-if="cekJabatan('Mahasiswa')">
        <router-link to="/topik/pengajuan" class="nav-link sidebar-menu-item">Pengajuan</router-link>
        <router-link to="/topik/histori" class="nav-link sidebar-menu-item">Histori</router-link>
      </template>
      <!-- // ? Dosen -->
      <template v-if="cekJabatan('Dosen')">
        <router-link to="/topik/persetujuan" class="nav-link sidebar-menu-item">Persetujuan Topik</router-link>
      </template>

      <!-- // * ####### BIMBINGAN ####### -->
      <div class="sidebar-menu">Bimbingan</div>
      <!-- // ? Mahasiswa -->
      <template v-if="cekJabatan('Mahasiswa') && (topik || topik_sidang)">
        <template v-if="topik">
          <router-link :to="{ path: '/bimbingan/ajukan/' + topik.slug_topik }"
            class="nav-link sidebar-menu-item">Pengajuan</router-link>
          <router-link :to="{ path: '/bimbingan/' + topik.slug_topik }"
            class="nav-link sidebar-menu-item">Histori</router-link>
        </template>
        <template v-else-if="topik_sidang">
          <router-link :to="{ path: '/bimbingan/ajukan/' + topik_sidang.slug_topik }"
            class="nav-link sidebar-menu-item">Pengajuan</router-link>
          <router-link :to="{ path: '/bimbingan/' + topik_sidang.slug_topik }"
            class="nav-link sidebar-menu-item">Histori</router-link>
        </template>
      </template>
      <template v-if="cekJabatan('Mahasiswa') && !topik && !topik_sidang">
        <div class="nav-link sidebar-menu-item-disabled">Pengajuan</div>
        <div class="nav-link sidebar-menu-item-disabled">Histori</div>
      </template>
      <!-- // ? Dosen -->
      <template v-if="cekJabatan('Dosen')">
        <router-link to="/bimbingan/konfirmasi" class="nav-link sidebar-menu-item">Proses Bimbingan</router-link>
      </template>

      <!-- // * ####### Sidang ####### -->
      <div class="sidebar-menu">Sidang</div>
      <!-- // ? Mahasiswa -->
      <template v-if="cekJabatan('Mahasiswa')">
        <div class="sidebar-menu-item" v-if="loading_topik_sidang">
          <ring-loader class="loading-page" color="white" :size="25" v-if="loading_topik_sidang" />
        </div>
        <template v-else>
          <router-link :to="{ path: '/sidang/' + topik_sidang.slug_topik }" class="nav-link sidebar-menu-item"
            v-if="id_batch_sidang && topik_sidang">Pengajuan</router-link>
          <div class="sidebar-menu-item-disabled" v-else>Pengajuan</div>
        </template>
        <router-link to="/sidang" class="nav-link sidebar-menu-item">Histori</router-link>
      </template>
      <!-- // ? TU -->
      <template v-if="cekJabatan('Tata Usaha')">
        <router-link to="/penjadwalan/sidang" class="nav-link sidebar-menu-item">Jadwal Sidang</router-link>
      </template>
      <!-- // ? Dosen -->
      <template v-if="cekJabatan('Dosen')">
        <router-link to="/sidang/persetujuan" class="nav-link sidebar-menu-item">Pengajuan Sidang</router-link>
        <router-link to="/sidang/acara" class="nav-link sidebar-menu-item">Berita Acara Sidang</router-link>
        <router-link to="/sidang/list-revisi" class="nav-link sidebar-menu-item">Revisi Sidang</router-link>
        <router-link to="/sidang/pergantian-nilai-akhir-sidang" class="nav-link sidebar-menu-item"
          v-if="!cekJabatan('Koordinator KP')">Pengajuan Ganti Nilai</router-link>
      </template>
      <!-- // ? Koor KP -->
      <template v-if="cekJabatan('Koordinator KP')">
        <div class="sidebar-menu">Koordinator KP</div>
        <router-link to="/sidang/komponen-penilaian" class="nav-link sidebar-menu-item">Komponen Penilaian</router-link>
        <router-link to="/sidang/syarat-dan-ketentuan" class="nav-link sidebar-menu-item">Syarat dan
          Ketentuan</router-link>
        <router-link to="/sidang/penjadwalan" class="nav-link sidebar-menu-item">Penjadwalan Sidang</router-link>
        <router-link to="/topik/kelengkapan-dokumen" class="nav-link sidebar-menu-item">Penerimaan Topik</router-link>
        <router-link to="/sidang/koor-kp-pergantian-nilai-akhir-sidang" class="nav-link sidebar-menu-item">Penggantian
          Nilai</router-link>
        <router-link to="/sidang/laporan-nilai" class="nav-link sidebar-menu-item">Laporan Nilai</router-link>
        <router-link to="/sidang/pembimbing-lapangan" class="nav-link sidebar-menu-item">Nilai Pembimbing
          Lapangan</router-link>
        <router-link to="/sidang/penilaian" class="nav-link sidebar-menu-item">Nilai Koordinator KP</router-link>
        <router-link to="/sidang/list-laporan" class="nav-link sidebar-menu-item">Laporan</router-link>
      </template>

      <!-- MBKM -->
      <div class="sidebar-menu">MBKM</div>
      <template v-if="cekJabatan('Mahasiswa')">
        <router-link to="/mbkm/pendaftaran" class="nav-link sidebar-menu-item">Daftar MBKM</router-link>
        <router-link to="/mbkm/konversi" class="nav-link sidebar-menu-item">Konversi SKS</router-link>
        <router-link to="/mbkm/logbook" class="nav-link sidebar-menu-item">Logbook</router-link>
      </template>
      <template v-if="cekJabatan('Dosen') || cekJabatan('Koordinator KP')">
        <router-link to="/mbkm/konfirmasi" class="nav-link sidebar-menu-item">Konfirmasi MBKM</router-link>
        <router-link to="/mbkm/jenis-mbkm" class="nav-link sidebar-menu-item">Jenis MBKM</router-link>
        <router-link to="/mbkm/mata-kuliah" class="nav-link sidebar-menu-item">Mata Kuliah</router-link>
        <router-link to="/mbkm/list-logbook" class="nav-link sidebar-menu-item">Logbook</router-link>
      </template>
      <div class="sidebar-menu">Topik MBKM</div>
      <template v-if="cekJabatan('Mahasiswa')">
        <router-link to="/topik-mbkm/pengajuan" class="nav-link sidebar-menu-item">Pengajuan</router-link>
        <router-link to="/topik-mbkm/histori" class="nav-link sidebar-menu-item">Histori</router-link>
      </template>
      <!-- // ? Dosen -->
      <template v-if="cekJabatan('Dosen')">
        <router-link to="/topik-mbkm/persetujuan" class="nav-link sidebar-menu-item">Persetujuan Topik</router-link>
      </template>
      <div class="sidebar-menu">Bimbingan MBKM</div>
      <!-- // ? Mahasiswa -->
      <template v-if="cekJabatan('Mahasiswa') && (topik || topik_sidang)">
        <template v-if="topik">
          <router-link :to="{ path: '/bimbingan-mbkm/ajukan/' + topik.slug_topik }"
            class="nav-link sidebar-menu-item">Pengajuan</router-link>
          <router-link :to="{ path: '/bimbingan-mbkm/' + topik.slug_topik }"
            class="nav-link sidebar-menu-item">Histori</router-link>
        </template>
        <template v-else-if="topik_sidang">
          <router-link :to="{ path: '/bimbingan-mbkm/ajukan/' + topik_sidang.slug_topik }"
            class="nav-link sidebar-menu-item">Pengajuan</router-link>
          <router-link :to="{ path: '/bimbingan-mbkm/' + topik_sidang.slug_topik }"
            class="nav-link sidebar-menu-item">Histori</router-link>
        </template>
      </template>
      <template v-if="cekJabatan('Mahasiswa') && !topik && !topik_sidang">
        <div class="nav-link sidebar-menu-item-disabled">Pengajuan</div>
        <div class="nav-link sidebar-menu-item-disabled">Histori</div>
      </template>
      <!-- // ? Dosen -->
      <template v-if="cekJabatan('Dosen')">
        <router-link to="/bimbingan-mbkm/konfirmasi" class="nav-link sidebar-menu-item">Proses Bimbingan</router-link>
      </template>
      <div class="sidebar-menu">Sidang MBKM</div>
      <!-- // ? Mahasiswa -->
      <template v-if="cekJabatan('Mahasiswa')">
        <div class="sidebar-menu-item" v-if="loading_topik_sidang">
          <ring-loader class="loading-page" color="white" :size="25" v-if="loading_topik_sidang" />
        </div>
        <template v-else>
          <router-link :to="{ path: '/sidang-mbkm/' + topik_sidang.slug_topik }" class="nav-link sidebar-menu-item"
            v-if="id_batch_sidang && topik_sidang">Pengajuan</router-link>
          <div class="sidebar-menu-item-disabled" v-else>Pengajuan</div>
        </template>
        <router-link to="/sidang-mbkm" class="nav-link sidebar-menu-item">Histori</router-link>
      </template>
      <!-- // ? TU -->
      <template v-if="cekJabatan('Tata Usaha')">
        <router-link to="/penjadwalan/sidang-mbkm" class="nav-link sidebar-menu-item">Jadwal Sidang</router-link>
      </template>
      <!-- // ? Dosen -->
      <template v-if="cekJabatan('Dosen')">
        <router-link to="/sidang-mbkm/persetujuan" class="nav-link sidebar-menu-item">Pengajuan Sidang</router-link>
        <router-link to="/sidang-mbkm/acara" class="nav-link sidebar-menu-item">Berita Acara Sidang</router-link>
        <router-link to="/sidang-mbkm/list-revisi" class="nav-link sidebar-menu-item">Revisi Sidang</router-link>
        <router-link to="/sidang-mbkm/pergantian-nilai-akhir-sidang" class="nav-link sidebar-menu-item"
          v-if="!cekJabatan('Koordinator KP')">Pengajuan Ganti Nilai</router-link>
      </template>
    </b-nav>
  </aside>
</template>

<script>
import Axios from "axios";
import config from "../config";
export default {
  data() {
    return {
      active: false,
      id_batch: null,
      topik: null,
      placement: "right",
      id_pengguna: this.$store.getters.pengguna.UserId,
      id_batch_sidang: null,
      topik_sidang: null,
      loading_topik_sidang: true,
    };
  },
  computed: {
    sideBar() {
      return this.$store.getters.isLoggedIn;
    },
  },
  mounted() {
    this.$parent.$on("toggleNav", () => {
      this.active = !this.active;
    });
    this.getBatchTopik();
    this.getTopikSidang();
  },
  methods: {
    cekJabatan(jabatan) {
      return this.$store.getters.jabatan.includes(jabatan);
    },
    getBatchTopik() {
      let id_pengguna = this.$store.getters.pengguna.UserId;
      this.$store.dispatch("jabatan", { id_pengguna }).then((response) => {
        if (this.cekJabatan("Mahasiswa")) {
          if (this.$store.getters.batchTopik) {
            this.id_batch = this.$store.getters.batchTopik.id_batch;
            this.getBatchSidang();
          } else {
            this.$store.dispatch("batchTopik").then((response) => {
              this.id_batch = this.$store.getters.batchTopik.id_batch;
              this.getBatchSidang();
            });
          }
        }
      });
    },
    getBatchSidang() {
      let id_pengguna = this.$store.getters.pengguna.UserId;
      this.$store.dispatch("jabatan", { id_pengguna }).then((response) => {
        if (this.cekJabatan("Mahasiswa")) {
          if (this.$store.getters.batchSidang) {
            this.id_batch_sidang = this.$store.getters.batchSidang.id_batch;
            this.getTopik();
          } else {
            this.$store.dispatch("batchSidang").then((response) => {
              this.id_batch_sidang = this.$store.getters.batchSidang.id_batch;
              this.getTopik();
            });
          }
        }
      });
    },
    getTopik() {
      const formData = new FormData();
      formData.append("id_batch", this.id_batch);
      formData.append("id_pengaju", this.id_pengguna);
      this.$store
        .dispatch("topik", formData)
        .then((response) => {
          if (response.data.message) {
            this.topik = null;
          } else {
            this.topik = response.data.data[0];
          }
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getTopikSidang() {
      this.loading_topik_sidang = true;
      const formData = new FormData();
      formData.append("id_pengaju", this.id_pengguna);
      Axios.post(`${config.apiMahasiswaUrl}/topik/sidang`, formData, {
        headers: {
          "Content-Type": "application/json",
        },
      }).then((response) => {
        if (response.data.message) {
          this.topik_sidang = null;
        } else {
          this.topik_sidang = response.data;
        }
        this.loading_topik_sidang = false;
      });
    },
  },
};
</script>

<style scoped>
div .judul {
  padding: 15px 0px 4px 35px;
  min-width: 243px;
  max-width: 280px;
  background-color: rgb(175, 170, 6);
  color: rgb(243, 249, 215);
  cursor: default;
}

aside {
  display: none;
  width: 300px;
  background-color: rgb(28, 31, 1);
  /* margin-bottom: -8px; */
  margin-bottom: -0.5rem;
  height: auto;
  /* height: 100vh;
  overflow-y: auto; */
}

/* aside a {
  display: block;
  padding: 10px 5px;
  color: #666;
  border-bottom: 1px solid #bbb;
} */

.sidebar-title-item {
  color: rgb(243, 249, 215);
}

.sidebar-menu {
  padding: 10px 15px;
  background-color: rgb(19, 21, 0);
  color: rgb(243, 249, 215);
  cursor: default;
}

.sidebar-menu-item-disabled,
.sidebar-menu-item-disabled:hover {
  padding-left: 35px;
  color: rgb(65, 72, 2);
  cursor: default;
}

.sidebar-menu-item {
  padding-left: 35px;
  color: rgb(243, 249, 215);
}

.sidebar-menu-item:hover,
.sidebar-title-item:hover {
  color: rgb(228, 243, 118);
}

/* .router-link-active{} */

aside.active {
  display: block;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}

.loading-text {
  color: rgb(32, 165, 6);
}
</style>