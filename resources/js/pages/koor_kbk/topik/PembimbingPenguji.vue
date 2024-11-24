<template>
  <b-container>
    <div>
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Pengaturan Pembimbing</h3>
        </div>
        <div class="col-3 pilihan-batch">
          <b-form-select v-model="id_batch" size="sm">
            <template v-slot:first>
              <option :value="null" disabled>-- Pilih Batch --</option>
            </template>
            <option
              v-for="pilihan in pilihan_batch"
              :key="pilihan.id_batch + 'a'"
              :value="pilihan.id_batch"
            >
              {{ pilihan.nama_batch }}
            </option>
          </b-form-select>
        </div>
        <!-- // ? Filter -->
        <div class="col-3 component-filter">
          <b-form-group label-size="sm" label-for="filterInput" class="mb-0">
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
      <div class="form-pembimbing-penguji">
        <b-table
          sticky-header
          class="table-pembimbing-penguji"
          ref="tablePengaturan"
          :items="topik"
          :fields="fields"
          hover
          striped
          responsive="sm"
          :busy="isBusyTopik"
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
            <h5 class="text-center">Tidak ada data</h5>
          </template>
          <template v-slot:cell(detail)="data">
            <div v-if="cekJabatan('Koordinator KP')">
              <router-link
                :to="`/topik/pembimbing/${data.item.slug_topik}`"
                class="btn btn-form"
                >Lihat</router-link
              >
            </div>
            <div v-else-if="cekJabatan('Koordinator KBK')">
              <router-link
                :to="`/topik/pengaturan-pembimbing/${data.item.slug_topik}`"
                class="btn btn-form"
                >Lihat</router-link
              >
            </div>
          </template>
        </b-table>
        <div class="row">
          <!-- // ? Pagination -->
          <div class="col">
            <b-pagination
              v-model="currentPage"
              :total-rows="totalRows"
              :per-page="perPage"
              align="fill"
              size="sm"
              class="mb-0"
            ></b-pagination>
          </div>
          <div class="col"></div>
          <!-- // ? ######################################################################## -->
        </div>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
export default {
  data() {
    return {
      pilihan_batch: [],
      // * Filter
      filter: null,
      // * Pagination
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      // * Sorting
      sortBy: "nrp",
      sortDesc: false,
      // * Table
      topik: [],
      fields: [
        { key: "id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "detail", sortable: false },
      ],
      isBusyTopik: true,
      id_kbk: null,
      id_batch: null,
      id_semester: null,
      semester: null,
      isBusySemester: true,
    };
  },
  mounted() {
    this.getListSemester();
  },
  methods: {
    cekJabatan(jabatan) {
      return this.$store.getters.jabatan.includes(jabatan);
    },
    getListSemester() {
      this.isBusySemester = true;
      Axios.get(`${config.apiUrl}/semester/aktif`)
        .then((response) => {
          this.semester = response.data;
          this.id_semester = this.semester.id_semester;
          this.isBusySemester = false;
          this.getListBatch();
        })
        .catch((response) => {
          this.isBusySemester = false;
        });
    },
    getListBatch() {
      this.loading = true;
      const formData = new FormData();
      if (this.$store.getters.listBatchTopik) {
        this.pilihan_batch = this.$store.getters.listBatchTopik;
        this.getNewestBatch();
      } else {
        const jenis_batch = 1;
        formData.append("jenis_batch", jenis_batch);
        formData.append("status_batch", [0, 1]);
        this.$store
          .dispatch("listBatch", {
            data_batch: formData,
            jenis_batch: jenis_batch,
          })
          .then((response) => {
            this.pilihan_batch = this.$store.getters.listBatchTopik;
            this.getNewestBatch();
          });
      }
    },
    getNewestBatch() {
      this.isBusyTopik = true;
      for (let index = 0; index < this.pilihan_batch.length; index++) {
        const element = this.pilihan_batch[index];
        if (element.semester.id_semester == this.id_semester) {
          this.id_batch = element.id_batch;
          break;
        }
        if (index == this.pilihan_batch.length - 1) {
          this.id_batch = this.pilihan_batch[0].id_batch;
        }
      }
      // if (!!this.$store.getters.batchTopik) {
      //   this.id_batch = this.$store.getters.batchTopik.id_batch;
      // } else {
      //   this.$store.dispatch("batchTopik").then(response => {
      //     this.id_batch = this.$store.getters.batchTopik.id_batch;
      //   });
      // }
    },
    getListTopik() {
      if (this.cekJabatan("Koordinator KP")) {
        this.getTopikKp();
      } else if (this.cekJabatan("Koordinator KBK")) {
        this.getIdKbkJabatan();
      }
    },
    getTopikKbk() {
      const formData = new FormData();
      formData.append("role_pengguna", "koorkbk");
      formData.append("id_batch", this.id_batch);
      formData.append("id_kbk", this.id_kbk);
      formData.append("status_topik", [5]);
      formData.append("status_persetujuan_dosen", [0, 1, 2, 4]);
      Axios.post(`${config.apiUrl}/topik/list-topik`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.topik = response.data.data;
            this.totalRows = response.data.data.length;
            this.getListMahasiswa();
          } else {
            this.topik = [];
            this.totalRows = 0;
            this.isBusyTopik = false;
          }
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getTopikKp() {
      const formData = new FormData();
      formData.append("id_batch", this.id_batch);
      formData.append("status_topik", [5]);
      formData.append("status_persetujuan_dosen", [0, 1, 2, 4]);
      Axios.post(`${config.apiUrl}/topik/list-topik`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.topik = response.data.data;
            this.totalRows = response.data.data.length;
            this.getListMahasiswa();
          } else {
            this.topik = [];
            this.totalRows = 0;
            this.isBusyTopik = false;
          }
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getListMahasiswa() {
      if (this.$store.getters.listMahasiswa) {
        this.changeDataTopik();
      } else {
        this.$store.dispatch("listMahasiswa").then((response) => {
          this.changeDataTopik();
        });
      }
    },
    cekNamaMahasiswa(nrp) {
      const listMahasiswa = this.$store.getters.listMahasiswa;
      for (let index = 0; index < listMahasiswa.length; index++) {
        const element = listMahasiswa[index];
        if (element.UserId == nrp) {
          return element.Name;
        }
      }
    },
    changeDataTopik() {
      // for (let index = 0; index < this.topik.length; index++) {
      //   const element = this.topik[index];
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     element.id_pengaju
      //   // ).then(response => {
      //   Object.assign(element, {
      //     nama: this.cekNamaMahasiswa(element.id_pengaju)
      //   });
      //   this.$refs.tablePengaturan.refresh();
      //   // });
      //   if (index == this.topik.length - 1) {
      //     this.$refs.tablePengaturan.refresh();
      //   }
      // }
      this.isBusyTopik = false;
      // this.$refs.tablePengaturan.refresh();
    },
    getIdKbkJabatan() {
      this.$store.getters.jabatan.forEach((element) => {
        if (element.includes("Ketua KBK")) {
          this.getIdKbk(element.replace("Ketua KBK ", ""));
        } else if (element.includes("Sekretaris KBK")) {
          this.getIdKbk(element.replace("Sekretaris KBK ", ""));
        }
      });
    },
    getIdKbk(nama_kbk) {
      const formData = new FormData();
      formData.append("nama_kbk", nama_kbk);
      Axios.post(`${config.apiUrl}/kbk-nama`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.id_kbk = response.data.data[0].id_kbk;
          this.getTopikKbk();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    // ? Filter
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
  },
  watch: {
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getListTopik();
      }
    },
  },
};
</script>
<style scoped>
.form-pembimbing-penguji {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
.pilihan-batch,
.component-filter {
  margin: auto;
}
/* // * Css Table */
.table-pembimbing-penguji {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
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