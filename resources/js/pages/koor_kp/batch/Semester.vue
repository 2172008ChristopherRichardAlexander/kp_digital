<template>
  <b-container>
    <div>
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Pengaturan Semester</h3>
        </div>
        <div class="button-keterangan-website col-3 text-right">
          <b-button
            class="btn-form"
            v-b-modal.modal-tambah-semester
            :disabled="loading"
          >
            {{ loading ? "" : "Buka Semester Baru" }}
            <ring-loader
              class="loading-page"
              color="white"
              :size="25"
              v-if="loading"
            />
          </b-button>
        </div>
      </div>
      <b-table
        sticky-header
        class="table-semester"
        striped
        hover
        :items="semester"
        :fields="fields"
        responsive="sm"
        :busy="isBusySemester"
        show-empty
      >
        <template v-slot:table-busy>
          <div class="text-center my-2">
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
        </template>
        <template v-slot:empty>
          <h5 class="text-center">Tidak ada data</h5>
        </template>
        <template v-slot:cell(aktifasi)="data">
          <b-button
            size="sm"
            @click="info(data.item, data.index, $event.target)"
            class="btn-form"
            >Update</b-button
          >
        </template>
      </b-table>
      <!-- // ? Modal Tambah Semester -->
      <b-modal
        id="modal-tambah-semester"
        ref="modal-tambah-semester"
        title="Buka Semester Baru"
        @ok="tambahSemester"
      >
        <form
          ref="form"
          @submit.stop.prevent="tambahSemester"
          enctype="multipart/form-data"
          method="post"
        >
          <b-table-simple borderless>
            <b-tbody>
              <b-tr>
                <b-td class="label-form">Nama</b-td>
                <b-td class="input-form-text">
                  <b-form-input
                    class="input-form-text-item"
                    id="nama_semester"
                    v-model.trim="$v.nama_semester.$model"
                    placeholder="Nama Semester"
                    size="sm"
                  ></b-form-input>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.nama_semester.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>
              <b-tr>
                <b-td class="label-form">Tanggal Mulai</b-td>
                <b-td class="input-form-date">
                  <VueCtkDateTimePicker
                    label="Tanggal Mulai"
                    class="input-form-date-item"
                    id="input-tanggal_mulai_semester"
                    format="DD-MM-YYYY"
                    formatted="LL"
                    outputFormat="YYYY-MM-DD"
                    v-model.trim="tanggal_mulai_semester"
                    noLabel
                    onlyDate
                    inputSize="sm"
                  />
                </b-td>
                <b-td class="feedback-validasi">&nbsp;</b-td>
              </b-tr>
              <b-tr>
                <b-td class="label-form">Tanggal Selesai</b-td>
                <b-td class="input-form-date">
                  <VueCtkDateTimePicker
                    label="Tanggal Selesai"
                    class="input-form-date-item"
                    id="input-tanggal_selesai_semester"
                    format="DD-MM-YYYY"
                    formatted="LL"
                    outputFormat="YYYY-MM-DD"
                    v-model.trim="tanggal_selesai_semester"
                    noLabel
                    onlyDate
                    inputSize="sm"
                  />
                </b-td>
                <b-td class="feedback-validasi">&nbsp;</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </form>
        <template v-slot:modal-footer="{ cancel }">
          <b-button size="sm" class="btn-form" @click="cancel()"
            >Batal</b-button
          >
          <b-button size="sm" class="btn-form" @click="tambahSemester()"
            >Tambah</b-button
          >
          <div class="space-kosong">&nbsp;</div>
        </template>
      </b-modal>
      <!-- // ? Modal Update Semester -->
      <b-modal
        :id="infoModal.id"
        ref="modal-update-semester"
        title="Update Semester"
        @ok="updateSemester"
      >
        <b-table-simple borderless>
          <b-tbody>
            <b-tr>
              <b-td class="label-form">Nama</b-td>
              <b-td class="input-form-text">
                <b-form-input
                  class="input-form-text-item"
                  id="nama_semester"
                  v-model.trim="infoModal.content.nama_semester"
                  placeholder="Nama Semester"
                  size="sm"
                ></b-form-input>
              </b-td>
              <b-td class="feedback-validasi">&nbsp;</b-td>
            </b-tr>
            <b-tr>
              <b-td class="label-form">Tanggal Mulai</b-td>
              <b-td class="input-form-date">
                <VueCtkDateTimePicker
                  label="Tanggal Mulai"
                  class="input-form-date-item"
                  id="input-tanggal_mulai_semester"
                  format="DD-MM-YYYY"
                  formatted="LL"
                  outputFormat="YYYY-MM-DD"
                  v-model.trim="infoModal.content.tanggal_mulai_semester"
                  noLabel
                  onlyDate
                  inputSize="sm"
                />
              </b-td>
              <b-td class="feedback-validasi">&nbsp;</b-td>
            </b-tr>
            <b-tr>
              <b-td class="label-form">Tanggal Selesai</b-td>
              <b-td class="input-form-date">
                <VueCtkDateTimePicker
                  label="Tanggal Selesai"
                  class="input-form-date-item"
                  id="input-tanggal_selesai_semester"
                  format="DD-MM-YYYY"
                  formatted="LL"
                  outputFormat="YYYY-MM-DD"
                  v-model.trim="infoModal.content.tanggal_selesai_semester"
                  noLabel
                  onlyDate
                  inputSize="sm"
                />
              </b-td>
              <b-td class="feedback-validasi">&nbsp;</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
        <pre></pre>
        <template v-slot:modal-footer="{ cancel }">
          <template v-if="infoModal.content.status_semester === 1">
            <b-button
              size="sm"
              class="btn-form"
              v-on:click="nonAktifSemester(infoModal.content)"
              >Non-Aktifkan</b-button
            >
          </template>
          <b-button size="sm" class="btn-form" @click="cancel()"
            >Batal</b-button
          >
          <b-button
            size="sm"
            class="btn-form"
            @click="updateSemester(infoModal.content)"
            >Update</b-button
          >
          <div class="space-kosong">&nbsp;</div>
        </template>
      </b-modal>
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
      fields: [
        { key: "nama_semester", label: "Nama" },
        { key: "tanggal_mulai_semester", label: "Tanggal Mulai" },
        { key: "tanggal_selesai_semester", label: "Tanggal Selesai" },
        { key: "status_semester", label: "Status" },
        "aktifasi",
      ],
      semester: [],
      loading: false,
      tanggal_sekarang:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate(),
      tanggal_mulai_semester:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate(),
      tanggal_selesai_semester: null,
      nama_semester: "",
      tanggal: null,
      aktifasi_semester: [],
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
      status: 2,
      isBusySemester: true,
    };
  },
  mounted() {
    this.getSemester();
  },
  validations: {
    nama_semester: {
      required,
    },
  },
  methods: {
    getSemester() {
      this.isBusySemester = true;
      Axios.get(`${config.apiUrl}/semester/list`)
        .then((response) => {
          this.semester = response.data.data;
          this.changeStatus();
        })
        .catch((response) => {
          this.isBusySemester = false;
        });
    },
    changeStatus() {
      var status = ["Tidak Aktif", "Aktif", "Belum Aktif"];
      for (let index = 0; index < this.semester.length; index++) {
        const element = this.semester[index];
        element.status_semester = status[element.status_semester];
        if (index >= this.semester.length - 1) this.isBusySemester = false;
      }
      if (this.semester.length <= 0) this.isBusySemester = false;
    },
    info(item, index, button) {
      this.infoModal.title = `Row index: ${index}`;
      this.infoModal.content = item;
      this.$root.$emit("bv::show::modal", this.infoModal.id, button);
    },
    async tambahSemester() {
      this.loading = true;
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.loading = false;
      } else {
        var status = await this.cekStatus(this.aktifasiSemester);
        Axios.post(
          `${config.apiUrl}/semester`,
          {
            nama_semester: this.nama_semester,
            tanggal_mulai_semester: this.tanggal_mulai_semester,
            tanggal_selesai_semester: this.tanggal_selesai_semester,
            status_semester: status,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then(() => {
            this.loading = false;
            this.$refs["modal-tambah-semester"].hide();
            this.getSemester();
          })
          .catch(() => {
            this.loading = false;
          });
      }
    },
    cekStatus(func) {
      var status = 2;
      var mulai = new Date(this.tanggal_mulai_semester).setHours(0);
      var sekarang = new Date(this.tanggal_sekarang).getTime();
      if (this.tanggal_selesai_semester) {
        var selesai = new Date(this.tanggal_selesai_semester).setHours(
          23,
          59,
          59
        );
        if (selesai < sekarang) {
          status = 0;
        } else if (mulai <= sekarang && selesai >= sekarang) {
          status = 1;
        }
      } else {
        if (mulai <= sekarang) {
          status = 1;
        }
      }
      if (status == 1) {
        func();
      }
      return status;
    },
    async aktifasiSemester() {
      const formData = new FormData();
      formData.append("status_semester", [1]);
      await Axios.post(`${config.apiUrl}/semester/list-status`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.aktifasi_semester = response.data.data;
          this.aktifasi_semester.forEach((element) => {
            this.nonAktifSemester(element);
          });
        })
        .catch((response) => {
          console.log(response);
        });
      return;
    },
    cekStatusUpdateSemester(semester) {
      var status = 2;
      var sekarang = new Date(this.tanggal_sekarang).getTime();
      var mulai = new Date(semester.tanggal_mulai_semester).setHours(0);
      if (semester.tanggal_selesai_semester) {
        var selesai = new Date(semester.tanggal_selesai_semester).setHours(
          23,
          59,
          59
        );
        if (selesai < sekarang) {
          status = 0;
        } else if (mulai <= sekarang && selesai >= sekarang) {
          status = 1;
        }
      } else {
        if (mulai <= sekarang) {
          status = 1;
        }
      }
      return status;
    },
    async updateSemester(semester) {
      this.loading = true;
      var status = await this.cekStatusUpdateSemester(semester);
      if (status == 1) {
        await this.aktifasiSemester();
      }
      Axios.put(
        `${config.apiUrl}/semester`,
        {
          id_semester: semester.id_semester,
          nama_semester: semester.nama_semester,
          tanggal_mulai_semester: semester.tanggal_mulai_semester,
          tanggal_selesai_semester: semester.tanggal_selesai_semester,
          status_semester: status,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then(() => {
          this.loading = false;
          this.$refs["modal-update-semester"].hide();
          this.getSemester();
        })
        .catch(() => {
          this.loading = false;
        });
    },
    nonAktifSemester(semester) {
      this.loading = true;
      Axios.put(
        `${config.apiUrl}/semester`,
        {
          id_semester: semester.id_semester,
          nama_semester: semester.nama_semester,
          tanggal_mulai_semester: semester.tanggal_mulai_semester,
          tanggal_selesai_semester: this.tanggal_sekarang,
          status_semester: 0,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
  },
};
</script>
<style scoped>
.keterangan-website {
  margin-left: 10px;
  padding-left: 10px;
}
.button-keterangan-website {
  margin-top: 5px;
  margin-right: 10px;
  padding-right: 12px;
}
.button-tambah-semester,
.button-non-aktifkan {
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
}
.button-tambah-semester:hover,
.button-non-aktifkan:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
}

.table-semester {
  border-top: 3px solid rgb(202, 206, 147);
  /* padding: 5px 30px 10px 10px; */
  margin: 0px 10px 0px 20px;
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
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