<template>
  <b-container>
    <div class="form-tambah-jadwal">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Update Jadwal</h3>
        </div>
      </div>
      <div v-if="loading_page">
        <ring-loader class="loading-page" color="#bada55" :loading="loading_page" :size="150" />
      </div>
      <div v-else>
        <b-form @submit.prevent="updateJadwal" enctype="multipart/form-data" method="post">
          <div class="tambah-jadwal">
            <div class="form-tambah-jadwal-title">
              <h4 class="keterangan-website my-2">Form Update Jadwal</h4>
            </div>
            <div class="form-tambah-jadwal-item">
              <div class="tambah-jadwal-item">
                <b-row>
                  <b-col class="label-form">Hari</b-col>
                  <b-col class="input-form-date">
                    <!-- // TODO: Membuat Select Option Hari Senin sampai Minggu value 1 - 7 -->
                    <b-form-select v-model="hari_jadwal_mahasiswa" :options="options_hari">
                      <template v-slot:first>
                        <option :value="null" disabled>Pilih Hari</option>
                      </template>
                    </b-form-select>
                  </b-col>
                  <b-col class="feedback-validasi">
                    <span
                      class="feedback-validasi-false"
                      v-if="!$v.hari_jadwal_mahasiswa.required"
                    >&#10006;</span>
                    <span class="feedback-validasi-true" v-else>&#10004;</span>
                  </b-col>
                </b-row>
              </div>
              <div class="tambah-jadwal-item">
                <b-row>
                  <b-col class="label-form">Waktu</b-col>
                  <b-col class="input-form-time text-left">
                    <VueCtkDateTimePicker
                      label="Waktu Mulai"
                      class="input-form-time-item"
                      id="input-waktu_mulai_jadwal_mahasiswa"
                      format="hh:mm a"
                      formatted="hh:mm a"
                      output-format="hh:mm a"
                      minute-interval="5"
                      only-time
                      v-model.trim="$v.waktu_mulai_jadwal_mahasiswa.$model"
                      noLabel
                      inputSize="sm"
                    />
                  </b-col>
                  <b-col class="simbol-sampai-dengan">&#9644;</b-col>
                  <b-col class="input-form-time text-right">
                    <VueCtkDateTimePicker
                      label="Waktu Selesai"
                      class="input-form-time-item"
                      id="input-waktu_selesai_jadwal_mahasiswa"
                      format="hh:mm a"
                      formatted="hh:mm a"
                      output-format="hh:mm a"
                      minute-interval="5"
                      only-time
                      v-model.trim="$v.waktu_selesai_jadwal_mahasiswa.$model"
                      noLabel
                      inputSize="sm"
                    />
                  </b-col>
                  <b-col class="feedback-validasi">
                    <span
                      class="feedback-validasi-false"
                      v-if="!$v.waktu_mulai_jadwal_mahasiswa.required || !$v.waktu_selesai_jadwal_mahasiswa.required"
                    >&#10006;</span>
                    <span class="feedback-validasi-true" v-else>&#10004;</span>
                  </b-col>
                </b-row>
              </div>
              <div class="tambah-jadwal-item">
                <b-row>
                  <b-col class="label-form">Keterangan</b-col>
                  <b-col class="input-form-textarea">
                    <b-form-textarea
                      class="input-form-textarea-item"
                      id="input-keterangan_jadwal_mahasiswa"
                      v-model="keterangan_jadwal_mahasiswa"
                      :placeholder="placeholder"
                      rows="5"
                      max-rows="5"
                    ></b-form-textarea>
                    <span
                      class="text-informasi"
                    >* Tambahkan tanggal-bulan-tahun untuk jadwal UTS atau UAS</span>
                  </b-col>
                  <b-col class="feedback-validasi">&nbsp;</b-col>
                </b-row>
              </div>
              <div class="tambah-jadwal-item row-konfirmasi">
                <b-row>
                  <b-col class="input-form-submit text-right">
                    <b-button type="submit" :disabled="loading" class="btn-form">
                      {{loading?'':'Update Jadwal'}}
                      <ring-loader class="loading-page" color="white" :size="25" v-if="loading" />
                    </b-button>
                  </b-col>
                </b-row>
              </div>
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
import { required, between } from "vuelidate/lib/validators";
export default {
  data() {
    return {
      jadwal: null,
      options_hari: [
        { value: 1, text: "Senin" },
        { value: 2, text: "Selasa" },
        { value: 3, text: "Rabu" },
        { value: 4, text: "Kamis" },
        { value: 5, text: "Jumat" },
        { value: 6, text: "Sabtu" },
        { value: 7, text: "Minggu" }
      ],
      placeholder:
        "Contoh Keterangan:\nMata Kuliah, Tanggal-Bulan-Tahun #Contoh Untuk Jadwal UTS atau UAS \nMata Kuliah #Contoh Untuk Jadwal Mata Kuliah",
      hari_jadwal_mahasiswa: null,
      waktu_mulai_jadwal_mahasiswa: null,
      waktu_selesai_jadwal_mahasiswa: null,
      keterangan_jadwal_mahasiswa: null,
      isBusyJadwal: true,
      submit_status: null,
      loading: false,
      loading_page: true
    };
  },
  mounted() {
    this.getJadwal();
  },
  validations: {
    hari_jadwal_mahasiswa: {
      required,
      between: between(1, 7)
    },
    waktu_mulai_jadwal_mahasiswa: {
      required
    },
    waktu_selesai_jadwal_mahasiswa: {
      required
    }
  },
  methods: {
    getJadwal() {
      this.loading_page = true;
      this.isBusyJadwal = true;
      this.loading = true;
      Axios.get(
        `${config.apiMahasiswaUrl}/jadwal-mahasiswa/${this.acakAcak(
          this.$route.params.id,
          1
        )}`
      )
        .then(response => {
          this.jadwal = response.data.data;
          this.hari_jadwal_mahasiswa = this.jadwal.hari_jadwal_mahasiswa;
          this.waktu_mulai_jadwal_mahasiswa = this.jadwal.waktu_mulai_jadwal_mahasiswa;
          this.waktu_selesai_jadwal_mahasiswa = this.jadwal.waktu_selesai_jadwal_mahasiswa;
          this.keterangan_jadwal_mahasiswa = this.jadwal.keterangan_jadwal_mahasiswa;
          this.loading_page = false;
          this.loading = false;
          this.isBusyJadwal = false;
        })
        .catch(response => {
          console.log(response);
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
    updateJadwal() {
      this.loading = true;
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.submit_status = "GAGAL";
        this.loading = false;
      } else {
        this.submit_status = "BERHASIL";
      }
      if (this.submit_status === "BERHASIL") {
        if (
          this.waktu_mulai_jadwal_mahasiswa.includes(
            "pagi" || "siang" || "sore"
          )
        )
          this.waktu_mulai_jadwal_mahasiswa = this.formatTime(
            this.waktu_mulai_jadwal_mahasiswa
          );
        if (
          this.waktu_selesai_jadwal_mahasiswa.includes(
            "pagi" || "siang" || "sore"
          )
        )
          this.waktu_selesai_jadwal_mahasiswa = this.formatTime(
            this.waktu_selesai_jadwal_mahasiswa
          );
        const formData = new FormData();
        formData.append("id_jadwal_mahasiswa", this.jadwal.id_jadwal_mahasiswa);
        formData.append("hari_jadwal_mahasiswa", this.hari_jadwal_mahasiswa);
        formData.append(
          "waktu_mulai_jadwal_mahasiswa",
          this.waktu_mulai_jadwal_mahasiswa
        );
        formData.append(
          "waktu_selesai_jadwal_mahasiswa",
          this.waktu_selesai_jadwal_mahasiswa
        );
        formData.append(
          "keterangan_jadwal_mahasiswa",
          this.keterangan_jadwal_mahasiswa
        );
        Axios.post(
          `${config.apiMahasiswaUrl}/jadwal-mahasiswa/update`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data"
            }
          }
        )
          .then(() => {
            this.loading = false;
            this.$router.push(`/sidang/${this.jadwal.id_topik.slug_topik}`);
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
        isi.forEach(element => {
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
        isi.forEach(element => {
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
    }
  }
};
</script>
<style scoped>
.tambah-jadwal {
  background-color: white;
  border-bottom: 3px solid rgb(202, 206, 147);
}
.form-tambah-jadwal-title {
  padding: 8px;
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
}
.tambah-jadwal-item:nth-child(even) {
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
}
.row-konfirmasi {
  padding: 10px;
}

/* // * CSS Label Form */
.label-form {
  min-width: 150px;
  max-width: 150px;
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
/* // * CSS Input Textarea Item */
.input-form-textarea-item {
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

.input-form-submit {
  padding-right: 60px;
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
.text-informasi {
  color: rgb(219, 38, 34);
}

/* // * Submit Button Item */
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