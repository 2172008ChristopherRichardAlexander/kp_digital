<template>
  <b-container>
    <div class="form-pembimbing-lapangan">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">
            List Mahasiswa Bimbingan Lapangan
          </h3>
        </div>
        <!-- // ? Pilih Batch -->
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
      <div class="form-nilai">
        <b-table
          sticky-header
          class="table-pembimbing-lapangan"
          :items="list_pembimbing_lapangan"
          :fields="fields"
          ref="tablePembimbingLapangan"
          hover
          striped
          responsive="sm"
          :busy="isBusyLapangan"
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
            <h5 class="text-center">Tidak ada data penilaian</h5>
          </template>
          <template v-slot:cell(judul_topik)="data">{{
            data.item.topik.judul_topik
          }}</template>
          <template v-slot:cell(nilai_pembimbing_lapangan)="data">{{
            data.item.nilai_pembimbing_lapangan
              ? data.item.nilai_pembimbing_lapangan
              : "Belum di Nilai"
          }}</template>
          <template v-slot:cell(penilaian)="data">
            <router-link
              :to="`/sidang/pembimbing-lapangan/${acakAcak(
                data.item.id_sidang,
                0
              )}`"
              class="btn btn-form"
              >{{
                data.item.nilai_pembimbing_lapangan ? "Lihat" : "Nilai"
              }}</router-link
            >
          </template>
        </b-table>
        <b-row>
          <!-- // ? Pagination -->
          <b-col>
            <b-row>
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
              <b-col lg="2">
                <b-form-select
                  v-model="perPage"
                  id="perPageSelect"
                  size="sm"
                  :options="pageOptions"
                ></b-form-select>
              </b-col>
            </b-row>
          </b-col>
          <!-- // ? ######################################################################## -->
          <b-col class="text-right">&nbsp;</b-col>
        </b-row>
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
      // * Filter
      filter: null,
      // * Pagination
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
      // * Sorting
      sortBy: "topik.id_pengaju",
      sortDesc: false,
      // * Table
      list_pembimbing_lapangan: [],
      id_pengguna: this.$store.getters.pengguna.UserId,
      fields: [
        { key: "topik.id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "nilai_pembimbing_lapangan", label: "Nilai", sortable: true },
        { key: "penilaian", sortable: false },
      ],
      loading: false,
      show: true,
      isBusyLapangan: true,
      id_batch: null,
      pilihan_batch: [],
      id_semester: null,
      semester: null,
      isBusySemester: true,
    };
  },
  mounted() {
    this.getListSemester();
  },
  methods: {
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
      if (this.$store.getters.listBatchSidang) {
        this.pilihan_batch = this.$store.getters.listBatchSidang;
        this.getNewestBatch();
      } else {
        const jenis_batch = 2;
        formData.append("jenis_batch", jenis_batch);
        formData.append("status_batch", [0, 1]);
        this.$store
          .dispatch("listBatch", {
            data_batch: formData,
            jenis_batch: jenis_batch,
          })
          .then((response) => {
            this.pilihan_batch = this.$store.getters.listBatchSidang;
            this.getNewestBatch();
          });
      }
    },
    getNewestBatch() {
      this.loading = true;
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
      // if (this.$store.getters.batchSidang) {
      //   this.id_batch = this.$store.getters.batchSidang.id_batch;
      //   // this.getListPembimbingLapangan();
      // } else {
      //   this.$store.dispatch("batchSidang").then(response => {
      //     this.id_batch = this.$store.getters.batchSidang.id_batch;
      //     // this.getListPembimbingLapangan();
      //   });
      // }
    },
    getListPembimbingLapangan() {
      this.loading = true;
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-pembimbing-lapangan`,
        {
          status_pembimbing_lapangan: 1,
          // id_pembimbing_lapangan: this.id_pengguna,
          id_batch: this.id_batch,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          if (response.data.message != "kosong") {
            this.list_pembimbing_lapangan = response.data.data;
            this.totalRows = response.data.data.length;
          } else {
            this.list_pembimbing_lapangan = [];
            this.totalRows = 0;
          }
          this.loading = false;
          this.getListMahasiswa();
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    getListMahasiswa() {
      if (this.$store.getters.listMahasiswa) {
        this.changeDataSidang();
      } else {
        this.$store.dispatch("listMahasiswa").then((response) => {
          this.changeDataSidang();
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
    changeDataSidang() {
      // for (
      //   let index = 0;
      //   index < this.list_pembimbing_lapangan.length;
      //   index++
      // ) {
      //   const element = this.list_pembimbing_lapangan[index];
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     element.topik.id_pengaju
      //   // ).then(response => {
      //   element.nama = this.cekNamaMahasiswa(element.topik.id_pengaju);
      //   // });
      //   if (index == this.list_pembimbing_lapangan.length - 1) {
      //     this.$refs.tablePembimbingLapangan.refresh();
      //   }
      // }
      this.isBusyLapangan = false;
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
  watch: {
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getListPembimbingLapangan();
      }
    },
  },
};
</script>
<style scoped>
/* .keterangan-website {
  margin-left: 10px;
  padding-left: 10px;
} */
.form-nilai {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.pilihan-batch,
.component-filter {
  margin: auto;
}

/* // * Css Table */
.table-pembimbing-lapangan {
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