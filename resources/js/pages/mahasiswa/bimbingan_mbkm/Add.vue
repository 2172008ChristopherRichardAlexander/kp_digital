<template>
  <b-container>
    <div class="form-tambah-bimbingan">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Ajukan Bimbingan</h3>
        </div>
      </div>
      <b-form @submit.prevent="onSubmit" v-if="show" enctype="multipart/form-data" method="post">
        <div class="table-tambah-bimbingan">
          <div class="form-tambah-bimbingan-title">
            <h4 class="keterangan-website my-2">Form Pengajuan Bimbingan</h4>
          </div>
          <div class="form-tambah-bimbingan-item">
            <!-- Tanggal Bimbingan -->
            <div class="form-pengajuan-item">
              <b-row>
                <b-col class="label-form">Tanggal</b-col>
                <b-col class="input-form-date">
                  <VueCtkDateTimePicker
                    class="input-form-date-item"
                    id="input-tanggal_bimbingan"
                    format="DD-MM-YYYY"
                    formatted="LL"
                    outputFormat="YYYY-MM-DD"
                    v-model.trim="$v.tanggal_bimbingan.$model"
                    noLabel
                    onlyDate
                    inputSize="sm"
                  />
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.tanggal_bimbingan.required"
                  >&#10006;</span>
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-col>
              </b-row>
            </div>

            <!-- Deskripsi Bimbingan -->
            <div class="form-pengajuan-item">
              <b-row>
                <b-col class="label-form">Deskripsi</b-col>
                <b-col class="input-form-textarea">
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-deskripsi_bimbingan"
                    v-model.trim="$v.deskripsi_bimbingan.$model"
                    placeholder="Deskripsi Bimbingan"
                    rows="5"
                    max-rows="5"
                  ></b-form-textarea>
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.deskripsi_bimbingan.required"
                  >&#10006;</span>
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-col>
              </b-row>
            </div>

            <!-- Input File Logbook (CSV) -->
            <div class="form-pengajuan-item">
              <b-row>
                <b-col class="label-form">Logbook (.csv)</b-col>
                <b-col class="input-form-file">
                  <b-form-file
                    v-model="logbookFile"
                    :state="logbookFile ? true : false"
                    accept=".csv"
                    placeholder="Pilih file logbook (.csv)"
                  />
                </b-col>
                <b-col class="feedback-validasi">
                  <span class="feedback-validasi-true" v-if="logbookFile">
                    &#10004; {{ logbookFile.name }}
                  </span>
                  <span class="feedback-validasi-false" v-else>
                    &#10006;
                  </span>
                </b-col>
              </b-row>
            </div>

            <!-- Input File Laporan (PDF) -->
            <div class="form-pengajuan-item">
              <b-row>
                <b-col class="label-form">Laporan (.pdf)</b-col>
                <b-col class="input-form-file">
                  <b-form-file
                    v-model="laporanFile"
                    :state="laporanFile ? true : false"
                    accept=".pdf"
                    placeholder="Pilih file laporan (.pdf)"
                  />
                </b-col>
                <b-col class="feedback-validasi">
                  <span class="feedback-validasi-true" v-if="laporanFile">
                    &#10004; {{ laporanFile.name }}
                  </span>
                  <span class="feedback-validasi-false" v-else>
                    &#10006;
                  </span>
                </b-col>
              </b-row>
            </div>

            <!-- Submit Button -->
            <div class="form-pengajuan-item row-tombol-sumbit">
              <b-row>
                <b-col class="input-form-submit text-right">
                  <span
                    class="feedback-validasi-false"
                    v-if="!id_pembimbing_bimbingan"
                  >* Belum ada pembimbing</span>
                  <b-button
                    type="submit"
                    class="btn-form"
                    :disabled="loading || !id_pembimbing_bimbingan || !logbookFile || !laporanFile"
                  >
                    {{loading?'':'Ajukan Bimbingan'}}
                    <ring-loader class="loading-page" color="white" :size="25" v-if="loading" />
                  </b-button>
                </b-col>
              </b-row>
            </div>
          </div>
        </div>
      </b-form>
    </div>
  </b-container>
</template>

<script>
import Axios from "axios";
import config from "../../../config";
import { required, requiredIf } from "vuelidate/lib/validators";
export default {
  data() {
    return {
      topik: {},
      tanggal_bimbingan:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate(),
      deskripsi_bimbingan: "",
      show: true,
      submit_status: null,
      status_bimbingan: 2,
      id_pembimbing_bimbingan: null,
      loading: false
    };
  },
  mounted() {
    this.getTopik();
  },
  validations: {
    tanggal_bimbingan: {
      required
    },
    deskripsi_bimbingan: {
      required
    }
  },
  methods: {
    getTopik() {
      this.loading = true;
      Axios.get(`${config.apiMahasiswaUrl}/topik/${this.$route.params.id}`)
        .then(response => {
          this.topik = response.data.data;
          this.id_pembimbing_bimbingan = this.topik.id_pembimbing;
          this.loading = false;
        })
        .catch(response => {
          console.log(response);
        });
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
        const formData = new FormData();
        formData.append("tanggal_bimbingan", this.tanggal_bimbingan);
        formData.append("deskripsi_bimbingan", this.deskripsi_bimbingan);
        formData.append("status_bimbingan", this.status_bimbingan);
        formData.append("id_topik", this.topik.id_topik);
        formData.append(
          "id_pembimbing_bimbingan",
          this.id_pembimbing_bimbingan
        );
        Axios.post(
          `${config.apiMahasiswaUrl}/bimbingan`,
          formData,
          // {
          // tanggal_bimbingan: this.tanggal_bimbingan,
          // deskripsi_bimbingan: this.deskripsi_bimbingan,
          // status_bimbingan: this.status_bimbingan,
          // id_topik: this.topik.id_topik,
          // id_pembimbing_bimbingan: this.id_pembimbing_bimbingan
          // },
          {
            headers: {
              // "Content-Type": "application/json"
              "Content-Type": "multipart/form-data"
            }
          }
        )
          .then(() => {
            this.loading = false;
            this.$router.push(`/bimbingan/${this.$route.params.id}`);
          })
          .catch(response => {
            this.loading = false;
            console.log(response);
          });
      }
    }
  }
};
</script>
<style scoped>
.table-tambah-bimbingan {
  background-color: white;
  border-bottom: 3px solid rgb(202, 206, 147);
}
.form-tambah-bimbingan-title {
  padding: 8px;
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
}
.form-pengajuan-item:nth-child(even) {
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
}
.input-form-submit {
  padding-right: 60px;
}
.row-tombol-sumbit {
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
/* // * CSS Input Textarea Item */
.input-form-textarea-item {
  max-width: 100%;
  min-width: 100%;
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