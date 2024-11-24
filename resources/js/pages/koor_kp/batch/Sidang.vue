<template>
  <b-container>
    <div>
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">
            Pengaturan Batch Pengajuan Sidang
          </h3>
        </div>
        <div class="button-keterangan-website col-3 text-right">
          <b-button class="btn-form" v-b-modal.modal-tambah-batch
            >Buka Batch Baru</b-button
          >
        </div>
      </div>
      <b-table
        sticky-header
        class="table-batch"
        striped
        hover
        :items="batch"
        :fields="fields"
        responsive="sm"
        :busy="isBusyBatch"
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
      <!-- // ? Modal Tambah Batch -->
      <b-modal
        id="modal-tambah-batch"
        ref="modal-tambah-batch"
        title="Buka Batch Baru"
        @ok="tambahBatch"
      >
        <form
          ref="form"
          @submit.stop.prevent="tambahBatch"
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
                    id="nama_batch"
                    v-model.trim="$v.nama_batch.$model"
                    placeholder="Nama Batch"
                    size="sm"
                  ></b-form-input>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.nama_batch.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>
              <b-tr>
                <b-td class="label-form">Minimal Bimbingan</b-td>
                <b-td class="input-form-number">
                  <b-form-input
                    type="number"
                    min="0"
                    class="input-form-number-item"
                    id="nama_batch"
                    v-model.trim="$v.minimal_bimbingan.$model"
                    placeholder="Minimal Bimbingan"
                    size="sm"
                  ></b-form-input>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.minimal_bimbingan.required"
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
                    id="input-tanggal_mulai_batch"
                    format="DD-MM-YYYY"
                    formatted="LL"
                    outputFormat="YYYY-MM-DD"
                    v-model.trim="$v.tanggal_mulai_batch.$model"
                    noLabel
                    onlyDate
                    inputSize="sm"
                  />
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.tanggal_mulai_batch.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>
              <b-tr>
                <b-td class="label-form">Tanggal Selesai</b-td>
                <b-td class="input-form-date">
                  <VueCtkDateTimePicker
                    label="Tanggal Selesai"
                    class="input-form-date-item"
                    id="input-tanggal_selesai_batch"
                    format="DD-MM-YYYY"
                    formatted="LL"
                    outputFormat="YYYY-MM-DD"
                    v-model.trim="tanggal_selesai_batch"
                    noLabel
                    onlyDate
                    inputSize="sm"
                  />
                </b-td>
                <b-td class="feedback-validasi">&nbsp;</b-td>
              </b-tr>
              <b-tr>
                <b-td class="label-form">Semester</b-td>
                <b-td class="input-form-select">
                  <b-form-select v-model="$v.id_semester.$model" size="sm">
                    <!-- This slot appears above the options from 'options' prop -->
                    <template v-slot:first>
                      <option :value="null" disabled>
                        -- Pilih Semester --
                      </option>
                    </template>

                    <!-- These options will appear after the ones from 'options' prop -->
                    <option
                      v-for="semester in list_semester"
                      :key="semester.id_semester + 'a'"
                      :value="semester.id_semester"
                    >
                      {{ semester.nama_semester }}
                    </option>
                  </b-form-select>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.id_semester.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </form>
        <template v-slot:modal-footer="{ cancel }">
          <!-- Emulate built in modal footer ok and cancel button actions -->
          <b-button size="sm" class="btn-form" @click="cancel()"
            >Batal</b-button
          >
          <!-- <b-button size="sm" class="btn-form" @click="ok()">Tambah</b-button> -->
          <b-button size="sm" class="btn-form" @click="tambahBatch()"
            >Tambah</b-button
          >
          <div class="space-kosong">&nbsp;</div>
        </template>
      </b-modal>
      <!-- // ? Modal Update Batch -->
      <b-modal
        :id="infoModal.id"
        ref="modal-update-batch"
        title="Update Batch"
        @ok="updateBatch"
      >
        <b-table-simple borderless>
          <b-tbody>
            <b-tr>
              <b-td class="label-form">Nama</b-td>
              <b-td class="input-form-text">
                <b-form-input
                  class="input-form-text-item"
                  id="nama_batch"
                  v-model.trim="infoModal.content.nama_batch"
                  placeholder="Nama Batch"
                  size="sm"
                ></b-form-input>
              </b-td>
              <b-td class="feedback-validasi">&nbsp;</b-td>
            </b-tr>
            <b-tr>
              <b-td class="label-form">Minimal Bimbingan</b-td>
              <b-td class="input-form-number">
                <b-form-input
                  type="number"
                  min="0"
                  class="input-form-number-item"
                  id="nama_batch"
                  v-model.trim="infoModal.content.minimal_bimbingan"
                  placeholder="Minimal Bimbingan"
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
                  id="input-tanggal_mulai_batch"
                  format="DD-MM-YYYY"
                  formatted="LL"
                  outputFormat="YYYY-MM-DD"
                  v-model.trim="infoModal.content.tanggal_mulai_batch"
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
                  id="input-tanggal_selesai_batch"
                  format="DD-MM-YYYY"
                  formatted="LL"
                  outputFormat="YYYY-MM-DD"
                  v-model.trim="infoModal.content.tanggal_selesai_batch"
                  noLabel
                  onlyDate
                  inputSize="sm"
                />
              </b-td>
              <b-td class="feedback-validasi">&nbsp;</b-td>
            </b-tr>
            <b-tr>
              <b-td class="label-form">Semester</b-td>
              <b-td class="input-form-select">
                <ring-loader
                  v-if="!infoModal.content.semester"
                  class="loading-page"
                  color="#20a506"
                  :size="25"
                />
                <b-form-select
                  v-else
                  v-model="infoModal.content.semester.id_semester"
                  size="sm"
                >
                  <!-- This slot appears above the options from 'options' prop -->
                  <template v-slot:first>
                    <option :value="null" disabled>-- Pilih Semester --</option>
                  </template>

                  <!-- These options will appear after the ones from 'options' prop -->
                  <option
                    v-for="semester in list_semester"
                    :key="semester.id_semester + 'b'"
                    :value="semester.id_semester"
                  >
                    {{ semester.nama_semester }}
                  </option>
                </b-form-select>
              </b-td>
              <b-td class="feedback-validasi">
                <b-td class="feedback-validasi">&nbsp;</b-td>
              </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
        <template v-slot:modal-footer="{ cancel }">
          <template v-if="infoModal.content.status_batch === 1">
            <b-button
              size="sm"
              class="btn-form"
              v-on:click="nonAktifBatch(infoModal.content)"
              >Non-Aktifkan</b-button
            >
          </template>
          <!-- Emulate built in modal footer ok and cancel button actions -->
          <b-button size="sm" class="btn-form" @click="cancel()"
            >Batal</b-button
          >
          <b-button
            size="sm"
            class="btn-form"
            @click="updateBatch(infoModal.content)"
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
import { required } from "vuelidate/lib/validators";
export default {
  data() {
    return {
      fields: [
        { key: "nama_batch", label: "Nama" },
        { key: "tanggal_mulai_batch", label: "Tanggal Mulai" },
        { key: "tanggal_selesai_batch", label: "Tanggal Selesai" },
        { key: "minimal_bimbingan", label: "Bimbingan" },
        { key: "semester.nama_semester", label: "Semester" },
        { key: "status_batch", label: "Status" },
        "aktifasi",
      ],
      batch: [],
      loading: false,
      isBusyBatch: true,
      tanggal_sekarang:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate(),
      tanggal_mulai_batch:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate(),
      tanggal_selesai_batch: null,
      nama_batch: "",
      minimal_bimbingan: 8,
      id_jurusan: 2,
      kumpulan_jurusan: {},
      tanggal: null,
      aktifasi_batch: [],
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
      isBusySemester: true,
      list_semester: null,
      id_semester: null,
    };
  },
  mounted() {
    this.getListSemester();
    this.getBatch();
  },
  validations: {
    nama_batch: {
      required,
    },
    minimal_bimbingan: {
      required,
    },
    tanggal_mulai_batch: {
      required,
    },
    id_semester: {
      required,
    },
  },
  methods: {
    getListSemester() {
      this.isBusySemester = true;
      const formData = new FormData();
      formData.append("status_semester", [1, 2]);
      Axios.post(`${config.apiUrl}/semester/list-status`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.list_semester = response.data.data;
          this.isBusySemester = false;
        })
        .catch((response) => {
          this.isBusySemester = false;
        });
    },
    getBatch() {
      this.isBusyBatch = true;
      Axios.get(`${config.apiUrl}/batch-list/2`)
        .then((response) => {
          this.batch = response.data.data;
          this.changeStatus();
        })
        .catch((response) => {
          console.log(response);
          this.isBusyBatch = false;
        });
    },
    changeStatus() {
      var status = ["Tidak Aktif", "Aktif", "Belum Aktif"];
      for (let index = 0; index < this.batch.length; index++) {
        const element = this.batch[index];
        element.status_batch = status[element.status_batch];
        if (index >= this.batch.length - 1) this.isBusyBatch = false;
      }
      if (this.batch.length <= 0) this.isBusyBatch = false;
    },
    info(item, index, button) {
      this.infoModal.title = `Row index: ${index}`;
      this.infoModal.content = item;
      this.$root.$emit("bv::show::modal", this.infoModal.id, button);
    },
    async aktifasiBatch() {
      const formData = new FormData();
      formData.append("jenis_batch", 2);
      formData.append("status_batch", [1]);
      await Axios.post(`${config.apiUrl}/batch/daftar`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.aktifasi_batch = response.data.data;
          this.aktifasi_batch.forEach((element) => {
            this.nonAktifBatch(element);
          });
        })
        .catch((response) => {
          console.log(response);
        });
      return;
    },
    cekStatus() {
      var status = 2;
      var mulai = new Date(this.tanggal_mulai_batch).setHours(0);
      var sekarang = new Date(this.tanggal_sekarang).getTime();
      if (this.tanggal_selesai_batch) {
        var selesai = new Date(this.tanggal_selesai_batch).setHours(23, 59, 59);
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
    async tambahBatch() {
      this.loading = true;
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.loading = false;
      } else {
        var status = await this.cekStatus();
        if (status == 1) {
          await this.aktifasiBatch();
        }
        Axios.post(
          `${config.apiUrl}/batch`,
          {
            nama_batch: this.nama_batch,
            tanggal_mulai_batch: this.tanggal_mulai_batch,
            tanggal_selesai_batch: this.tanggal_selesai_batch,
            jenis_batch: 2,
            minimal_bimbingan: this.minimal_bimbingan,
            status_batch: status,
            id_jurusan: this.id_jurusan,
            id_semester: this.id_semester,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then(() => {
            this.loading = false;
            this.$refs["modal-tambah-batch"].hide();
            this.getBatch();
          })
          .catch(() => {
            this.loading = false;
          });
      }
    },
    async updateBatch(batch) {
      this.loading = true;
      var status = await this.cekStatusUpdateBatch(batch);
      if (status == 1) {
        await this.aktifasiBatch();
      }
      Axios.put(
        `${config.apiUrl}/batch`,
        {
          id_batch: batch.id_batch,
          nama_batch: batch.nama_batch,
          tanggal_mulai_batch: batch.tanggal_mulai_batch,
          tanggal_selesai_batch: batch.tanggal_selesai_batch,
          jenis_batch: 2,
          minimal_bimbingan: batch.minimal_bimbingan,
          status_batch: status,
          id_jurusan: batch.id_jurusan,
          id_semester: batch.semester.id_semester,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then(() => {
          this.loading = false;
          this.$refs["modal-update-batch"].hide();
          this.getBatch();
        })
        .catch(() => {
          this.loading = false;
        });
    },
    cekStatusUpdateBatch(batch) {
      var status = 2;
      var sekarang = new Date(this.tanggal_sekarang).getTime();
      var mulai = new Date(batch.tanggal_mulai_batch).setHours(0);
      if (batch.tanggal_selesai_batch) {
        var selesai = new Date(batch.tanggal_selesai_batch).setHours(
          23,
          59,
          59
        );
        if (selesai < sekarang) {
          status = 0;
        }
        if (mulai <= sekarang && selesai >= sekarang) {
          status = 1;
        }
      } else {
        if (mulai <= sekarang) {
          status = 1;
        }
      }
      return status;
    },
    nonAktifBatch: function (batch) {
      this.loading = true;
      Axios.put(
        `${config.apiUrl}/batch`,
        {
          id_batch: batch.id_batch,
          nama_batch: batch.nama_batch,
          tanggal_mulai_batch: batch.tanggal_mulai_batch,
          tanggal_selesai_batch: this.tanggal_sekarang,
          jenis_batch: 2,
          minimal_bimbingan: batch.minimal_bimbingan,
          status_batch: 0,
          id_jurusan: batch.id_jurusan,
          id_semester: batch.semester.id_semester,
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
/* // * CSS Button */
.button-tambah-batch,
.button-non-aktifkan {
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
}
.button-tambah-batch:hover,
.button-non-aktifkan:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
}

.table-batch {
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