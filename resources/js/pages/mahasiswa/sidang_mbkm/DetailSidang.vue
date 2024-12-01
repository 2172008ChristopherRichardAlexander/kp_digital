<template>
  <b-container>
    <div class="form-sidang-mahasiswa">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website">Detail Sidang</h3>
        </div>
      </div>
      <!-- // ! Sidang Mahasiswa -->
      <div class="row sidang-mahasiswa">
        <div class="col">
          <div class="row">
            <div class="col text-left">
              <h4 class="my-1 text-left judul">Detail Topik</h4>
            </div>
            <div class="col text-right">
              <router-link
                :to="`/sidang/revisi/${acakAcak(sidang.id_sidang, 0)}`"
                class="btn btn-form"
                v-if="
                  sidang.status_sidang == 1 || sidang.status_sidang === 'Lulus'
                "
                >Revisi</router-link
              >
              <b-button class="btn-form" disabled v-else>Revisi</b-button>
            </div>
          </div>
          <div
            class="text-center my-2"
            v-if="loading_page || isBusySidang || isBusyTopik"
          >
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
          <b-table-simple v-else>
            <b-tbody>
              <b-tr>
                <b-td>Judul</b-td>
                <b-td>:</b-td>
                <b-td>{{ topik.judul_topik }}</b-td>
              </b-tr>
              <b-tr>
                <b-td>Ruangan Sidang</b-td>
                <b-td>:</b-td>
                <b-td>{{ sidang.ruangan_sidang }}</b-td>
              </b-tr>
              <b-tr>
                <b-td>Tanggal Sidang</b-td>
                <b-td>:</b-td>
                <b-td>{{ sidang.tanggal_sidang }}</b-td>
              </b-tr>
              <b-tr>
                <b-td>Waktu Sidang</b-td>
                <b-td>:</b-td>
                <b-td
                  >{{ sidang.waktu_mulai_sidang }} -
                  {{ sidang.waktu_akhir_sidang }}</b-td
                >
              </b-tr>
              <b-tr>
                <b-td>Status Sidang</b-td>
                <b-td>:</b-td>
                <b-td>{{ cekStatusSidang(sidang.status_sidang) }}</b-td>
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
              <b-tr>
                <b-td>Penguji Sidang</b-td>
                <b-td>:</b-td>
                <b-td>{{ sidang.id_penguji_sidang }}</b-td>
              </b-tr>
              <b-tr v-if="sidang.id_penguji_sidang_dua != '-'">
                <b-td>Penguji Sidang Dua</b-td>
                <b-td>:</b-td>
                <b-td>{{ sidang.id_penguji_sidang_dua }}</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </div>
        <div class="col-6">
          <h4 class="my-1 text-center judul">Tahapan Sidang Kerja Praktek</h4>
          <h5 class="my-1 judul">Tahap Sidang</h5>
          <div class="text-center" v-if="isBusySidang">
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
          <div class="pengajuan-topik" v-else>
            <Stepper
              v-for="(step, index) in steps_pengajuan_sidang"
              :key="step.id_step"
              :detail="step"
              :status="sidang.status_sidang"
              :icon="index + 1"
            />
          </div>
          <h5 class="my-1 judul">Tahap Revisi</h5>
          <div class="text-center" v-if="isBusySidang">
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
          <div class="pengajuan-topik" v-else>
            <Stepper
              v-for="(step, index) in steps_revisi"
              :key="step.id_step"
              :detail="step"
              :status="sidang.status_revisi"
              :icon="index + 1"
            />
          </div>
        </div>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import Stepper from "../../../components/Stepper";
export default {
  components: {
    Stepper,
  },
  data() {
    return {
      steps_pengajuan_sidang: [
        {
          id_step: -2,
          judul: "menyetujui syarat dan ketentuan",
          isi: "",
        },
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
          judul: "sidang",
          isi: "",
        },
        {
          id_step: 5,
          judul: "revisi",
          isi: "",
        },
      ],
      steps_revisi: [
        {
          id_step: -1,
          judul: "upload foto revisi dan/atau menuliskan text revisi",
          isi: "",
        },
        {
          id_step: 3,
          judul: "melakukan revisi",
          isi: "",
        },
        {
          id_step: 2,
          judul: "mengajukan penyelesaian revisi",
          isi: "",
        },
        {
          id_step: 4,
          judul: "revisi diterima dosen pembimbing",
          isi: "",
        },
        {
          id_step: 5,
          judul: "revisi diterima dosen penguji",
          isi: "",
        },
      ],
      loading: false,
      loading_page: true,
      isBusySidang: true,
      isBusyTopik: true,
      sidang: {},
      topik: {},
    };
  },
  mounted() {
    // this.changeName();
    this.getSidang();
  },
  methods: {
    getSidang() {
      Axios.get(
        `${config.apiUrl}/sidang/` + this.acakAcak(this.$route.params.id, 1)
      )
        .then((response) => {
          this.sidang = response.data.data;
          this.isBusySidang = false;
          this.topik = this.sidang.topik;
          this.changeName();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    changeName() {
      this.loading_page = true;
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
      if (this.sidang.id_penguji_sidang) {
        Axios.get(
          `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
            this.sidang.id_penguji_sidang
        ).then((response) => {
          this.sidang.id_penguji_sidang = response.data[0].Name;
        });
      } else {
        this.sidang.id_penguji_sidang = "-";
      }
      if (this.sidang.id_penguji_sidang_dua) {
        Axios.get(
          `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
            this.sidang.id_penguji_sidang_dua
        ).then((response) => {
          this.sidang.id_penguji_sidang_dua = response.data[0].Name;
        });
      } else {
        this.sidang.id_penguji_sidang_dua = "-";
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
      this.isBusyTopik = false;
      this.loading_page = false;
    },
    cekStatusSidang(index) {
      const status = [
        "Tidak Lulus",
        "Lulus",
        "Menunggu Persetujuan",
        "Sidang Sedang Diajukan Penjadwalan",
        "Sidang Sudah di jadwalankan",
      ];
      if (index >= 0 && index < 5) {
        return status[index];
      } else {
        return "";
      }
      // if (index == 0) {
      //   return "Tidak Lulus";
      // } else if (index == 1) {
      //   return "Lulus";
      // } else if (index == 2) {
      //   return "Menunggu Persetujuan";
      // } else if (index == 3) {
      //   return "Sidang Sedang Diajukan Penjadwalan";
      // } else if (index == 4) {
      //   return "Sidang Sudah di jadwalankan";
      // } else {
      //   return "";
      // }
    },
    cekStatus(status) {
      if (status == 0) {
        return "ditolak Koordinator KP / Dosen";
      } else if (status == 1) {
        return "diterima Koordinator KP";
      } else if (status == 2) {
        return "sedang diajukan";
      } else if (status == 3) {
        return "menunggu persetujuan dosen";
      } else if (status == 4) {
        return "ditolak Koordinator KBK";
      } else if (status == 5) {
        return "diterima Koordinator KBK";
      } else if (status == 6) {
        return "topik dapat di Revisi";
      } else if (status == 7) {
        return "masa sidang";
      } else if (status == 8) {
        return "topik tidak aktif";
      } else {
        return "";
      }
    },
    acakAcak(isi, status) {
      isi = Array.from(String(isi));
      var code = "";
      if (status == 0) {
        isi.forEach((element) => {
          if (element == "0") {
            code = code + "c";
          } else if (element == "1") {
            code = code + "h";
          } else if (element == "2") {
            code = code + "r";
          } else if (element == "3") {
            code = code + "i";
          } else if (element == "4") {
            code = code + "s";
          } else if (element == "5") {
            code = code + "t";
          } else if (element == "6") {
            code = code + "a";
          } else if (element == "7") {
            code = code + "n";
          } else if (element == "8") {
            code = code + "o";
          } else if (element == "9") {
            code = code + "7";
          }
        });
      } else if (status == 1) {
        isi.forEach((element) => {
          if (element == "c") {
            code = code + "0";
          } else if (element == "h") {
            code = code + "1";
          } else if (element == "r") {
            code = code + "2";
          } else if (element == "i") {
            code = code + "3";
          } else if (element == "s") {
            code = code + "4";
          } else if (element == "t") {
            code = code + "5";
          } else if (element == "a") {
            code = code + "6";
          } else if (element == "n") {
            code = code + "7";
          } else if (element == "o") {
            code = code + "8";
          } else if (element == "7") {
            code = code + "9";
          }
        });
      }
      return code;
    },
  },
};
</script>
<style scoped>
/* .keterangan-website {
  margin-left: 0px;
  padding-left: 0px;
} */
/* // * Keperluan Sidang */
.sidang-mahasiswa {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 20px 20px;
  background-color: rgb(255, 255, 255);
}
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
  max-height: 30vh;
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
  background-color: rgb(17, 122, 139);
  color: rgb(255, 255, 255);
  cursor: default;
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