<template>
  <b-container>
    <div class="form-penjadwalan-mahasiswa">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Penjadwalan Sidang</h3>
        </div>
      </div>
      <div class="penjadwalan-mahasiswa">
        <div class="pembimbing-penguji-sidang">
          <div class="row">
            <div class="col">
              <h3 class="keterangan-website">
                Daftar Pembimbing dan Penguji Sidang
              </h3>
            </div>
          </div>
          <b-row v-if="sidang.topik">
            <b-col sm="2">Pembimbing</b-col>
            <b-col
              >: {{ sidang.topik.pembimbing }} ({{
                sidang.topik.id_pembimbing
              }})</b-col
            >
            <template v-if="sidang.topik">
              <b-col sm="2">Pembimbing Lapangan</b-col>
              <b-col
                >: {{ sidang.topik.pembimbing_lapangan }} ({{
                  sidang.topik.id_pembimbing_lapangan
                }})</b-col
              >
            </template>
          </b-row>
          <b-row v-if="sidang.penguji_sidang">
            <b-col sm="2">Penguji</b-col>
            <b-col
              >: {{ sidang.penguji_sidang }} ({{
                sidang.id_penguji_sidang
              }})</b-col
            >
            <template v-if="sidang.penguji_sidang_dua">
              <b-col sm="2">Penguji Dua</b-col>
              <b-col
                >: {{ sidang.penguji_sidang_dua }} ({{
                  sidang.id_penguji_sidang_dua
                }})</b-col
              >
            </template>
          </b-row>
        </div>
        <div class="penjadwalan-mahasiswa-item">
          <div class="row my-2">
            <div class="col text-left">
              <h4 class="keterangan-website">Daftar Jadwal Mahasiswa</h4>
            </div>
            <!-- // ? Filter -->
            <div class="col-3 component-filter">
              <b-form-group
                label-size="sm"
                label-for="filterInput"
                class="mb-0"
              >
                <b-input-group size="sm">
                  <b-form-input
                    v-model="filter"
                    type="search"
                    id="filterInput"
                    placeholder="Ketik untuk mencari"
                  ></b-form-input>
                  <b-input-group-append>
                    <b-button
                      class="btn-form"
                      :disabled="!filter"
                      @click="filter = ''"
                      >Hapus</b-button
                    >
                  </b-input-group-append>
                </b-input-group>
              </b-form-group>
            </div>
            <!-- // ? ######################################################################## -->
          </div>
          <b-table
            sticky-header
            class="table-penjadwalan-mahasiswa"
            :items="jadwal_mahasiswa"
            :fields="fields"
            hover
            striped
            responsive="sm"
            :busy="isBusyPenjadwalan"
            show-empty
            :current-page="currentPage"
            :per-page="perPage"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :filter="filter"
            @filtered="onFiltered"
          >
            <template v-slot:table-busy>
              <div class="text-center my-2">
                <ring-loader class="loading-page" color="#20a506" :size="50" />
                <strong class="loading-text">Loading...</strong>
              </div>
            </template>
            <template v-slot:empty>
              <h5 class="text-center">Tidak ada data jadwal mahasiswa</h5>
            </template>
            <!-- // TODO: Mengganti dengan hari -->
            <!-- <template v-slot:cell(tanggal)="data">
              {{data}}
              <VueCtkDateTimePicker
                label="Tanggal"
                class="show-form-time-item"
                :id="'show-tanggal_jadwal_mahasiswa-'+data.item.id_jadwal_mahasiswa"
                format="YYYY-MM-DD"
                formatted="LL"
                only-date
                noClearButton
                disabled
                v-model="data.item.tanggal_jadwal_mahasiswa"
                noLabel
                inputSize="sm"
              />
            </template>-->
          </b-table>
          <b-row>
            <!-- // ? Pagination -->
            <b-col>
              <b-pagination
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage"
                align="fill"
                size="sm"
                class="mb-0"
              ></b-pagination>
            </b-col>
            <!-- // ? ######################################################################## -->
            <b-col class="text-right">&nbsp;</b-col>
          </b-row>
        </div>
        <!-- // * Menentukan Jadwal Mahasiswa -->
        <div class="row my-2">
          <div class="col text-left">
            <h4 class="keterangan-website">Menentukan Jadwal Mahasiswa</h4>
          </div>
        </div>
        <b-form
          @submit.prevent="onSubmit"
          v-if="show"
          enctype="multipart/form-data"
          method="post"
        >
          <div class="tambah-jadwal">
            <div class="form-tambah-jadwal-title">
              <h4 class="my-2">Form Tambah Jadwal</h4>
            </div>
            <div class="form-tambah-jadwal-item">
              <b-row>
                <b-col class="label-form">Tanggal</b-col>
                <b-col class="input-form-data">
                  <VueCtkDateTimePicker
                    label="Tanggal"
                    class="input-form-date-item"
                    id="input-tanggal_sidang"
                    format="DD-MM-YYYY"
                    formatted="LL"
                    outputFormat="YYYY-MM-DD"
                    v-model.trim="$v.tanggal_sidang.$model"
                    noLabel
                    onlyDate
                    inputSize="sm"
                  />
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.tanggal_sidang.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-col>
              </b-row>
            </div>
            <div class="form-tambah-jadwal-item">
              <b-row>
                <b-col class="label-form">Waktu</b-col>
                <b-col class="input-form-time text-left">
                  <VueCtkDateTimePicker
                    label="Waktu Mulai"
                    class="input-form-time-item"
                    id="input-waktu_mulai_sidang"
                    format="hh:mm a"
                    formatted="hh:mm a"
                    output-format="hh:mm a"
                    minute-interval="5"
                    only-time
                    v-model.trim="$v.waktu_mulai_sidang.$model"
                    noLabel
                    inputSize="sm"
                  />
                </b-col>
                <b-col class="simbol-sampai-dengan">&#9644;</b-col>
                <b-col class="input-form-time text-right">
                  <VueCtkDateTimePicker
                    label="Waktu Selesai"
                    class="input-form-time-item"
                    id="input-waktu_akhir_sidang"
                    format="hh:mm a"
                    formatted="hh:mm a"
                    output-format="hh:mm a"
                    minute-interval="5"
                    only-time
                    v-model.trim="$v.waktu_akhir_sidang.$model"
                    noLabel
                    inputSize="sm"
                  />
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="
                      !$v.waktu_mulai_sidang.required ||
                      !$v.waktu_akhir_sidang.required
                    "
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-col>
              </b-row>
            </div>
            <div class="form-tambah-jadwal-item">
              <b-row>
                <b-col class="label-form">Ruang Sidang</b-col>
                <b-col class="input-form-text">
                  <b-form-input
                    class="input-form-text-item"
                    id="input-ruangan_sidang"
                    v-model="ruangan_sidang"
                    placeholder="Ruangan Sidang"
                    size="sm"
                  ></b-form-input>
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.ruangan_sidang.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-col>
              </b-row>
            </div>
            <div class="form-tambah-jadwal-item row-konfirmasi">
              <b-row>
                <b-col class="input-form-submit text-right">
                  <b-button type="submit" :disabled="loading" class="btn-form">
                    {{ loading ? "" : "Tentukan Jadwal" }}
                    <ring-loader
                      class="loading-page"
                      color="white"
                      :size="25"
                      v-if="loading"
                    />
                  </b-button>
                </b-col>
                <b-col class="feedback-validasi">&nbsp;</b-col>
              </b-row>
            </div>
          </div>
        </b-form>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import { required } from "vuelidate/lib/validators";
export default {
  data() {
    return {
      // * Filter
      filter: null,
      // * Pagination
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      // * Sorting
      sortBy: "tanggal",
      sortDesc: false,
      // * Table
      sidang: {},
      jadwal_mahasiswa: [],
      tanggal_sidang:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate(),
      waktu_mulai_sidang: null,
      waktu_akhir_sidang: null,
      fields: [
        { key: "hari_jadwal_mahasiswa", label: "Hari", sortable: true },
        {
          key: "waktu_mulai_jadwal_mahasiswa",
          label: "Waktu Mulai",
          sortable: true,
        },
        {
          key: "waktu_selesai_jadwal_mahasiswa",
          label: "Waktu Selesai",
          sortable: true,
        },
        {
          key: "keterangan_jadwal_mahasiswa",
          label: "Keterangan",
          sortable: true,
        },
      ],
      show: true,
      submit_status: null,
      ruangan_sidang: "",
      loading: true,
      isBusyPenjadwalan: true,
      id_batch: null,
      pilihan_batch: [],
      id_semester: null,
      semester: null,
      isBusySemester: true,
      nama_pembimbing: null,
      nama_pembimbing_lapangan: null,
      nama_penguji: null,
      nama_penguji_dua: null,
    };
  },
  mounted() {
    this.getDetailSidang();
  },
  validations: {
    tanggal_sidang: {
      required,
    },
    waktu_mulai_sidang: {
      required,
    },
    waktu_akhir_sidang: {
      required,
    },
    ruangan_sidang: {
      required,
    },
  },
  methods: {
    getDetailSidang() {
      this.loading = true;
      Axios.post(
        `${config.apiKoorKpUrl}/sidang-detail`,
        {
          id_sidang: this.acakAcak(this.$route.params.id, 1),
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.sidang = response.data.data[0];
          this.id_batch = this.sidang.batch.id_batch;
          if (this.sidang.tanggal_sidang) {
            this.tanggal_sidang = this.sidang.tanggal_sidang;
          }
          if (this.sidang.waktu_mulai_sidang) {
            this.waktu_mulai_sidang = this.sidang.waktu_mulai_sidang;
          }
          if (this.sidang.waktu_akhir_sidang) {
            this.waktu_akhir_sidang = this.sidang.waktu_akhir_sidang;
          }
          if (this.sidang.ruangan_sidang) {
            this.ruangan_sidang = this.sidang.ruangan_sidang;
          }
          this.changeDataTopik();
        })
        .catch((response) => {
          this.loading = false;
        });
    },
    changeDataTopik() {
      // Axios.get(
      //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //     this.sidang.topik.id_pengaju
      // )
      //   .then(response => {
      //     this.sidang.nama = response.data[0].Name;
      //   })
      //   .catch(response => {
      //     this.loading = false;
      //   });
      // Axios.get(
      //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //     this.sidang.topik.id_pembimbing
      // )
      //   .then(response => {
      //     this.nama_pembimbing = response.data[0].Name;
      //   })
      //   .catch(response => {
      //     this.loading = false;
      //   });
      // if (
      //   this.sidang.topik.id_pembimbing !=
      //   this.sidang.topik.id_pembimbing_lapangan
      // ) {
      //   Axios.get(
      //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //       this.sidang.topik.id_pembimbing_lapangan
      //   )
      //     .then(response => {
      //       this.nama_pembimbing_lapangan = response.data[0].Name;
      //     })
      //     .catch(response => {
      //       this.loading = false;
      //     });
      // }
      // Axios.get(
      //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //     this.sidang.id_penguji_sidang
      // )
      //   .then(response => {
      //     this.nama_penguji = response.data[0].Name;
      //   })
      //   .catch(response => {
      //     this.loading = false;
      //   });
      // if (this.sidang.id_penguji_sidang_dua) {
      //   Axios.get(
      //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //       this.sidang.id_penguji_sidang_dua
      //   )
      //     .then(response => {
      //       this.nama_penguji_dua = response.data[0].Name;
      //     })
      //     .catch(response => {
      //       this.loading = false;
      //     });
      // }
      this.getJadwal();
    },
    getJadwal() {
      this.isBusyPenjadwalan = true;
      var hari = [
        "Senin",
        "Selasa",
        "Rabu",
        "Kamis",
        "Jumat",
        "Sabtu",
        "Minggu",
      ];
      Axios.post(
        `${config.apiKoorKpUrl}/list-jadwal-batch-topik`,
        {
          id_batch: this.id_batch,
          id_topik: this.sidang.topik.id_topik,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.jadwal_mahasiswa = response.data.data;
          this.jadwal_mahasiswa.forEach((element) => {
            if (
              element.hari_jadwal_mahasiswa >= 1 &&
              element.hari_jadwal_mahasiswa <= 7
            )
              element.hari_jadwal_mahasiswa =
                hari[element.hari_jadwal_mahasiswa - 1];
            else element.hari_jadwal_mahasiswa = "";
          });
          this.totalRows = response.data.data.length;
          this.isBusyPenjadwalan = false;
          this.loading = false;
        })
        .catch((response) => {
          console.log(response);
          this.loading = false;
        });
    },
    formatTime(waktu) {
      var jam = Number(waktu.match(/^(\d+)/)[1]);
      var menit = Number(waktu.match(/:(\d+)/)[1]);
      var AMPM = waktu.match(/\s(.*)$/)[1];
      if ((AMPM == "pagi" || AMPM == "am") && jam == 12) jam = jam - 12;
      if (
        (AMPM == "siang" ||
          AMPM == "sore" ||
          AMPM == "malam" ||
          AMPM == "pm") &&
        jam < 12
      )
        jam = jam + 12;
      var sJam = jam.toString();
      var sMenit = menit.toString();
      if (jam < 10) sJam = "0" + sJam;
      if (menit < 10) sMenit = "0" + sMenit;
      // return sJam + ":" + sMenit + ":00";
      return sJam + ":" + sMenit;
    },
    checkFormat() {
      if (this.waktu_mulai_sidang.match(/\s(.*)$/)) {
        this.waktu_mulai_sidang = this.formatTime(this.waktu_mulai_sidang);
      }
      if (this.waktu_akhir_sidang.match(/\s(.*)$/)) {
        this.waktu_akhir_sidang = this.formatTime(this.waktu_akhir_sidang);
      }
    },
    onSubmit() {
      this.loading = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        this.submit_status = "GAGAL";
      } else {
        this.submit_status = "BERHASIL";
      }
      if (this.submit_status === "BERHASIL") {
        this.checkFormat();

        Axios.put(
          `${config.apiUrl}/sidang`,
          {
            id_sidang: this.sidang.id_sidang,
            ruangan_sidang: this.ruangan_sidang,
            tanggal_sidang: this.tanggal_sidang,
            waktu_mulai_sidang: this.waktu_mulai_sidang,
            waktu_akhir_sidang: this.waktu_akhir_sidang,
            nilai_pembimbing: this.sidang.nilai_pembimbing,
            nilai_pembimbing_lapangan: this.sidang.nilai_pembimbing_lapangan,
            nilai_penguji: this.sidang.nilai_penguji,
            nilai_penguji_dua: this.sidang.nilai_penguji_dua,
            nilai_koor_kp: this.sidang.nilai_koor_kp,
            text_revisi: this.sidang.text_revisi,
            foto_revisi: this.sidang.foto_revisi,

            status_pembimbing_utama: this.sidang.status_pembimbing_utama,
            status_pembimbing_lapangan: this.sidang.status_pembimbing_lapangan,
            status_sidang: 4,

            id_syarat_ketentuan_sidang_mahasiswa: this.sidang
              .syarat_ketentuan_sidang_mahasiswa.id_syarat_ketentuan_sidang,
            id_syarat_ketentuan_sidang_dosen: this.sidang
              .syarat_ketentuan_sidang_dosen.id_syarat_ketentuan_sidang,

            id_penguji_sidang: this.sidang.id_penguji_sidang,
            id_penguji_sidang_dua: this.sidang.id_penguji_sidang_dua,
            id_topik: this.sidang.topik.id_topik,
            id_batch: this.sidang.batch.id_batch,
            id_komponen_nilai: this.sidang.komponen_nilai.id_komponen_nilai,
            id_jenis_sidang: this.sidang.jenis_sidang.id_jenis_sidang,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then(() => {
            this.loading = false;
            this.$router.push("/sidang/penjadwalan");
          })
          .catch(() => {
            this.loading = false;
          });
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
    // ? Filter
    onFiltered(filteredItems) {
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
  },
};
</script>
<style scoped>
/* // * Penjadwalan Mahasiswa */
.penjadwalan-mahasiswa {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.pilihan-batch,
.component-filter {
  margin: auto;
}

/* // * Css Table */
.table-penjadwalan-mahasiswa {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}

/* // * CSS Tentukan Jadwal Sidang */
.tambah-jadwal {
  background-color: white;
  border-bottom: 3px solid rgb(202, 206, 147);
}
.form-tambah-jadwal-title {
  padding: 8px;
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
}
.form-tambah-jadwal-item:nth-child(odd) {
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
}
.row-konfirmasi {
  padding: 10px;
}
/* // * CSS Label Form */
.label-form {
  min-width: 180px;
  max-width: 180px;
  font-size: 18px;
  margin: auto;
  padding-left: 30px;
}
/* // * CSS Input Date Item */
.input-form-date-item {
  max-width: 100%;
  min-width: 100%;
}
/* // * CSS Input Time Item */
.input-form-time-item {
  padding: 0px;
  margin: 0px;
  max-width: 100%;
  min-width: 100%;
}

/* // * CSS Input Text Item */
.input-form-text-item {
  max-width: 100%;
  min-width: 100%;
}

/* // * CSS Simbol Sampai Dengan */
.simbol-sampai-dengan {
  min-width: 60px;
  max-width: 60px;
  font-size: 18px;
  margin: auto;
  color: rgb(243, 249, 215);
  text-align: center;
}

/* // ! CSS Validasi Form */
.feedback-validasi {
  text-align: left;
  margin: auto;
  min-width: 50px;
  max-width: 50px;
  font-size: 20px;
  padding: 0px;
}
.feedback-validasi-false {
  color: red;
}
.feedback-validasi-true {
  color: green;
}

/* // * Css Button */
.btn-form {
  padding: 4px 20px;
  font-size: 15px;
  border-radius: 5px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
}

.btn-form:disabled,
.btn-form:disabled:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
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