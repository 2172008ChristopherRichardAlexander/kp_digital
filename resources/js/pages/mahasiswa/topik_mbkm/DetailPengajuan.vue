<template>
  <div class="row">
    <div class="col">
      <div class="row">
        <div class="col text-left">
          <h4 class="my-1 text-left judul">Detail</h4>
        </div>
        <div class="col text-right">
          <router-link
            :to="`/topik/revisi/${topik.slug_topik}`"
            class="btn btn-form"
            v-if="
              topik.status_topik === 'topik dapat di Revisi' ||
              topik.status_topik == 6
            "
            >Revisi</router-link
          >
          <div class="btn btn-form-disabled disabled" v-else>Revisi</div>
        </div>
      </div>
      <div class="text-center my-2" v-if="loading">
        <ring-loader class="loading-page" color="#20a506" :size="50" />
        <strong class="loading-text">Loading...</strong>
      </div>
      <b-table-simple v-else>
        <b-tbody>
          <b-tr>
            <b-td>Nama Instansi</b-td>
            <b-td>:</b-td>
            <b-td>{{ topik.judul_topik }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>Status</b-td>
            <b-td>:</b-td>
            <b-td>{{ topik.status_topik }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>KBK</b-td>
            <b-td>:</b-td>
            <b-td>{{ topik.kbk.nama_kbk }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>Pembimbing Utama</b-td>
            <b-td>:</b-td>
            <b-td>{{ topik.id_pembimbing }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>Pembimbing Lapangan</b-td>
            <b-td>:</b-td>
            <b-td>{{ topik.id_pembimbing_lapangan }}</b-td>
          </b-tr>
          <!-- <b-tr>
            <b-td>Penguji Sidang</b-td>
            <b-td>:</b-td>
            <b-td>{{topik.id_penguji_sidang}}</b-td>
          </b-tr>
          <b-tr v-if="topik.id_penguji_sidang_dua != '-'">
            <b-td>Penguji Sidang Dua</b-td>
            <b-td>:</b-td>
            <b-td>{{topik.id_penguji_sidang_dua}}</b-td>
          </b-tr>-->
        </b-tbody>
      </b-table-simple>
      <!-- // ? Bimbingan -->
      <div class="form-histori-bimbingan">
        <!-- Title Histori Bimbingan -->
        <div class="histori-bimbingan">
          <div class="row">
            <div class="col text-left">
              <h4 class="my-1 text-left judul">Bimbingan</h4>
            </div>
            <div class="col text-right">
              <router-link
                :to="`/bimbingan/ajukan/${topik.slug_topik}`"
                class="btn btn-form"
                >Tambah Bimbingan</router-link
              >
            </div>
          </div>
          <!-- Table Histori Bimbingan  -->
          <b-table
            sticky-header
            class="table-histori-bimbingan"
            ref="tabelHistory"
            hover
            striped
            :items="bimbingan"
            :fields="fields"
            responsive="sm"
            :busy="isBusyHistory"
            show-empty
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
          >
            <template v-slot:table-busy>
              <div class="text-center my-2">
                <ring-loader class="loading-page" color="#20a506" :size="50" />
                <strong class="loading-text">Loading...</strong>
              </div>
            </template>
            <template v-slot:empty>
              <h5 class="text-center">Tidak ada data bimbingan</h5>
            </template>
          </b-table>
        </div>
      </div>
    </div>
    <div class="col-6">
      <!-- <h4 class="my-1 text-center judul">Tahapan Kerja Praktek</h4> -->
      <!-- <div v-if="topik.status_topik >= 2 && topik.status_topik <= 6"> -->
      <h4 class="my-2 judul">Tahap Pengajuan Topik</h4>
      <div class="pengajuan-topik">
        <Stepper
          v-for="(step, index) in steps_pengajuan_topik"
          :key="step.id_step"
          :detail="step"
          :status="topik.status_topik"
          :icon="index + 1"
        />
      </div>
      <!-- </div> -->
      <!-- <div v-else-if="topik.status_topik == 5"> -->
      <h4 class="my-2 judul">Tahap Pengajuan Bimbingan</h4>
      <div
        class="bimbingan"
        v-if="
          topik.status_topik == 'diterima Koordinator KBK' ||
          topik.status_topik == 5
        "
      >
        <Stepper
          v-for="(step, index) in steps_pengajuan_bimbingan"
          :key="step.id_step"
          :detail="step"
          status="1"
          :icon="index + 1"
        />
      </div>
      <div class="bimbingan" v-else>
        <Stepper
          v-for="(step, index) in steps_pengajuan_bimbingan"
          :key="step.id_step"
          :detail="step"
          :icon="index + 1"
        />
      </div>
      <!-- </div> -->
      <!-- <div v-else-if="topik.status_topik == 7"> -->
      <h4 class="my-2 judul">Tahap Pengajuan Sidang</h4>
      <div class="sidang">
        <Stepper
          v-for="(step, index) in steps_pengajuan_sidang"
          :key="step.id_step"
          :detail="step"
          :icon="index + 1"
        />
      </div>
      <!-- </div> -->
    </div>
  </div>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import Stepper from "../../../components/Stepper";
export default {
  components: {
    Stepper,
  },
  mounted() {
    this.changeName();
  },
  data() {
    return {
      fields: [
        { key: "tanggal_bimbingan", label: "Tanggal", sortable: true },
        { key: "deskripsi_bimbingan", label: "Deskripsi", sortable: false },
        { key: "status_bimbingan", label: "Status", sortable: false },
      ],
      bimbingan: [],
      selectMode: "multi",
      loading: false,
      input_user_password: null,
      show: true,
      isBusyHistory: true,
      steps_pengajuan_topik: [
        {
          id_step: -1,
          judul: "Mengajukan Pendaftaran",
          isi: "",
        },
        {
          id_step: 13,
          judul: "Melakukan Konversi SKS",
          isi: "",
        },
        {
          id_step: 12,
          judul: "Menunggu Persetujuan Koordinator KP",
          isi: "",
        },
      ],
      steps_pengajuan_bimbingan: [
        {
          id_step: 1,
          judul: "Mengajukan Bimbingan",
          isi: "",
        },
        {
          id_step: 2,
          judul: "Menunggu Persetujuan Dosen",
          isi: "",
        },
      ],
      steps_pengajuan_sidang: [
        {
          id_step: -1,
          judul: "mengajukan sidang",
          isi: "",
        },
        {
          id_step: 2,
          judul: "menunggu persetujuan pembimbing",
          isi: "",
        },
        {
          id_step: 3,
          judul: "menentukan jadwal berhalangan, menunggu penjadwalan",
          isi: "",
        },
        {
          id_step: 4,
          judul: "sidang sudah di jadwalkan",
          isi: "",
        },
      ],
      sortBy: "tanggal_bimbingan",
      sortDesc: false,
    };
  },
  methods: {
    changeName() {
      this.loading = true;
      if (this.topik.id_pembimbing) {
        Axios.get(
          `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
            this.topik.id_pembimbing
        ).then((response) => {
          this.topik.id_pembimbing = response.data[0].Name;
        });
      } else {
        this.topik.id_pembimbing = "-";
      }
      if (this.topik.id_pembimbing_lapangan) {
        Axios.get(
          `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
            this.topik.id_pembimbing_lapangan
        ).then((response) => {
          this.topik.id_pembimbing_lapangan = response.data[0].Name;
        });
      } else {
        this.topik.id_pembimbing_lapangan = "-";
      }
      // if (!!this.topik.id_penguji_sidang) {
      //   Axios.get(
      //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //       this.topik.id_penguji_sidang
      //   ).then(response => {
      //     this.topik.id_penguji_sidang = response.data[0].Name;
      //   });
      // } else {
      //   this.topik.id_penguji_sidang = "-";
      // }
      // if (!!this.topik.id_penguji_sidang_dua) {
      //   Axios.get(
      //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //       this.topik.id_penguji_sidang_dua
      //   ).then(response => {
      //     this.topik.id_penguji_sidang_dua = response.data[0].Name;
      //   });
      // } else {
      //   this.topik.id_penguji_sidang_dua = "-";
      // }
      this.topik.status_topik = this.cekStatus(this.topik.status_topik);
      this.loading = false;
      this.getBimbingan();
    },
    getBimbingan() {
      Axios.post(
        `${config.apiMahasiswaUrl}/bimbingan-topik`,
        {
          id_topik: this.topik.id_topik,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.bimbingan = response.data.data;
          this.changeStatus();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    changeStatus() {
      this.bimbingan.forEach((element) => {
        element.status_bimbingan = this.cekStatusBimbingan(
          element.status_bimbingan
        );
      });
      this.isBusyHistory = false;
    },
    cekStatusBimbingan(status) {
      if (status == 0) {
        return "ditolak";
      } else if (status == 1) {
        return "diterima";
      } else if (status == 2) {
        return "sedang diajukan";
      } else {
        return "";
      }
    },
    cekStatus(status) {
      if (status == 0) {
        return "ditolak Koordinator KP / Dosen";
      } else if (status == 1) {
        return "diterima Koordinator KP";
      } else if (status == 2) {
        return "sedang diajukan";
      } else if (status == 3 ) {
        return "menunggu persetujuan dosen";
      } else if (status == 4) {
        return "ditolak Koordinator RG";
      } else if (status == 5) {
        return "diterima Koordinator KP";
      } else if (status == 6) {
        return "topik dapat di Revisi";
      } else if (status == 7) {
        return "masa sidang";
      } else if (status == 8) {
        return "topik tidak aktif";
      } else if(status == 13) {
        return "lakukan konversi SKS"
      }else {
        return "";
      }
    },
  },
  props: ["topik"],
};
</script>


<style scoped>
/* // * Judul Topik */
.judul {
  font-weight: bold;
}
.md-card {
  width: 320px;
  margin: 4px;
  display: inline-block;
  vertical-align: top;
}
.pengajuan-topik,
.bimbingan,
.sidang {
  padding-left: 20px;
}

/* // * Css Tabel */
.table-histori-bimbingan {
  margin: 0px 0px 0px 0px;
  background-color: rgb(255, 255, 255);
  max-height: 33vh;
  white-space: pre-wrap;
}
/* // * Css Button */

.btn-form {
  padding: 8px 20px;
  font-size: 15px;
  border-radius: 5px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
  width: 195px;
}

.btn-form:disabled,
.btn-form:disabled:hover {
  background-color: rgb(198, 205, 180);
  color: rgb(28, 31, 1);
  cursor: default;
}
.btn-form-disabled,
.btn-form-disabled:hover {
  padding: 8px 20px;
  background-color: rgb(17, 122, 139);
  color: rgb(255, 255, 255);
  cursor: default;
  width: 195px;
}

.btn-form:hover {
  font-size: 15px;
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>