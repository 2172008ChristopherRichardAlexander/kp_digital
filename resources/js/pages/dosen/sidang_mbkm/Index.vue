<template>
  <b-container>
    <div class="form-acara-sidang">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Acara Sidang</h3>
        </div>
      </div>
      <!-- // ? Acara -->
      <div class="acara-sidang">
        <div class="row my-2">
          <div class="col text-left">
            <h4 class="keterangan-website">Daftar Acara Sidang</h4>
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
        <div class="acara-sidang-item">
          <b-table
            sticky-header
            class="table-acara-sidang"
            :items="list_sidang"
            :fields="fields"
            ref="tableAcaraSidang"
            hover
            striped
            responsive="sm"
            :busy="isBusyAcara"
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
              <h5 class="text-center">Tidak ada data acara sidang</h5>
            </template>
            <template v-slot:cell(nama)="data">
              <!-- <div v-if="data.item.nama"> -->
              {{ data.item.nama }}
              <!-- </div> -->
              <!-- <div v-else>{{data.item.topik.id_pengaju}}</div> -->
            </template>
            <template v-slot:cell(judul_topik)="data">{{
              data.item.topik.judul_topik
            }}</template>

            <template v-slot:cell(tanggal)="data">{{
              data.item.tanggal_sidang
            }}</template>

            <template v-slot:cell(waktu)="data"
              >{{ data.item.waktu_mulai_sidang }}-{{
                data.item.waktu_akhir_sidang
              }}</template
            >

            <template v-slot:cell(acara)="data">
              <router-link
                :to="`/sidang/acara/${acakAcak(data.item.id_sidang, 0)}`"
                class="btn btn-form"
                >Sidang</router-link
              >
            </template>
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

        <!-- // ? History -->
        <div class="row my-2">
          <div class="col text-left">
            <h4 class="keterangan-website">Daftar History Acara Sidang</h4>
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
            <b-form-group
              label-size="sm"
              label-for="filterInputHistory"
              class="mb-0"
            >
              <b-input-group size="sm">
                <b-form-input
                  v-model="filter_history"
                  type="search"
                  id="filterInputHistory"
                  placeholder="Ketik untuk mencari"
                ></b-form-input>
                <b-input-group-append>
                  <b-button
                    class="btn-form"
                    :disabled="!filter_history"
                    @click="filter_history = ''"
                    >Hapus</b-button
                  >
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </div>
          <!-- // ? ######################################################################## -->
        </div>
        <div class="acara-sidang-item">
          <b-table
            sticky-header
            class="table-acara-sidang"
            :items="list_history_sidang"
            :fields="fields_history"
            ref="tableHistorySidang"
            hover
            striped
            responsive="sm"
            :busy="isBusyHistory"
            show-empty
            :current-page="currentPageHistory"
            :per-page="perPage"
            :sort-by.sync="sortByHistory"
            :sort-desc.sync="sortDescHistory"
            :filter="filter_history"
            @filtered="onFilteredHistory"
          >
            <template v-slot:table-busy>
              <div class="text-center my-2">
                <ring-loader class="loading-page" color="#20a506" :size="50" />
                <strong class="loading-text">Loading...</strong>
              </div>
            </template>
            <template v-slot:empty>
              <h5 class="text-center">Tidak ada data history acara sidang</h5>
            </template>
            <template v-slot:cell(nama)="data">{{ data.item.nama }}</template>
            <template v-slot:cell(judul_topik)="data">{{
              data.item.topik.judul_topik
            }}</template>

            <template v-slot:cell(tanggal)="data">{{
              data.item.tanggal_sidang
            }}</template>

            <template v-slot:cell(waktu)="data"
              >{{ data.item.waktu_mulai_sidang }}-{{
                data.item.waktu_akhir_sidang
              }}</template
            >

            <template v-slot:cell(nilai)="data">
              <div v-if="data.item.topik.id_pembimbing == id_pengguna">
                {{ data.item.nilai_pembimbing }}
              </div>
              <div v-else-if="data.item.id_penguji_sidang == id_pengguna">
                {{ data.item.nilai_penguji }}
              </div>
              <div v-else-if="data.item.id_penguji_sidang_dua == id_pengguna">
                {{ data.item.nilai_penguji_dua }}
              </div>
            </template>

            <template v-slot:cell(acara)="data">
              <router-link
                :to="`/sidang/acara/${acakAcak(data.item.id_sidang, 0)}`"
                class="btn btn-form"
                >Lihat</router-link
              >
            </template>
          </b-table>
          <b-row>
            <!-- // ? Pagination -->
            <b-col>
              <b-pagination
                v-model="currentPageHistory"
                :total-rows="totalRowsHistory"
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
      filter_history: null,
      // * Pagination
      totalRows: 1,
      totalRowsHistory: 1,
      currentPage: 1,
      currentPageHistory: 1,
      perPage: 10,
      // * Sorting
      sortBy: "nrp",
      sortDesc: false,
      sortByHistory: "nrp",
      sortDescHistory: false,
      // * Table
      list_sidang: [],
      list_history_sidang: [],
      list_sidang_penguji: [],
      list_history_sidang_penguji: [],
      id_pengguna: this.$store.getters.pengguna.UserId,
      fields: [
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "ruangan_sidang", sortable: true },
        { key: "tanggal", sortable: true },
        { key: "waktu", sortable: true },
        { key: "acara", sortable: false },
      ],
      fields_history: [
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "ruangan_sidang", sortable: true },
        { key: "tanggal", sortable: true },
        { key: "waktu", sortable: true },
        { key: "nilai", sortable: true },
        { key: "acara", sortable: false },
      ],
      loading: false,
      isBusyAcara: true,
      isBusyHistory: true,
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
      // if (!!this.$store.getters.batchSidang) {
      //   this.id_batch = this.$store.getters.batchSidang.id_batch;
      //   // this.getListSidang();
      // } else {
      //   this.$store.dispatch("batchSidang").then(response => {
      //     this.id_batch = this.$store.getters.batchSidang.id_batch;
      //     // this.getListSidang();
      //   });
      // }
    },
    getListSidang() {
      this.isBusyAcara = true;
      this.isBusyHistory = true;
      this.loading = true;
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-pembimbing-utama`,
        {
          id_batch: this.id_batch,
          id_pembimbing: this.id_pengguna,
          status_sidang: "4,1,0",
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.loading = false;
          if (!response.data.message) {
            this.list_sidang = response.data.data;
          } else {
            this.list_sidang = [];
          }
          this.getListSidangPenguji();
        })
        .catch((response) => {
          this.loading = false;
        });
    },
    getListSidangPenguji() {
      this.loading = true;
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-penguji`,
        {
          id_batch: this.id_batch,
          id_penguji: this.id_pengguna,
          status_sidang: "4,1,0",
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          if (response.data.message != "kosong") {
            this.list_sidang_penguji = response.data.data;
            this.list_sidang_penguji.forEach((element) => {
              this.list_sidang.push(element);
            });
          } else {
            this.list_sidang_penguji = [];
            this.isBusyAcara = false;
          }
          this.loading = false;
          this.getListMahasiswa(1);
        })
        .catch((response) => {
          console.log(response);
          this.loading = false;
        });
    },
    getListHistorySidang() {
      this.loading = true;
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-history-pembimbing-utama`,
        {
          id_batch: this.id_batch,
          id_pembimbing: this.id_pengguna,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.loading = false;
          if (response.data.message != "kosong") {
            this.list_history_sidang = response.data.data;
          } else {
            this.list_history_sidang = [];
          }
          this.getListHistorySidangPenguji();
        })
        .catch((response) => {
          this.loading = false;
        });
    },
    getListHistorySidangPenguji() {
      this.loading = true;
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-history-penguji`,
        {
          id_batch: this.id_batch,
          id_penguji: this.id_pengguna,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.loading = false;
          this.list_history_sidang_penguji = response.data.data;
          if (response.data.message != "kosong") {
            this.list_history_sidang_penguji.forEach((element) => {
              this.list_history_sidang.push(element);
            });
          } else {
            this.list_history_sidang_penguji = [];
            this.isBusyHistory = false;
          }
          this.getListMahasiswa(2);
        })
        .catch((response) => {
          console.log(response);
          this.loading = false;
        });
    },
    getListMahasiswa(status) {
      if (this.$store.getters.listMahasiswa) {
        if (status == 1) this.changeDataTopik();
        else if (status == 2) this.changeDataHistoryTopik();
      } else {
        this.$store.dispatch("listMahasiswa").then((response) => {
          if (status == 1) this.changeDataTopik();
          else if (status == 2) this.changeDataHistoryTopik();
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
      this.totalRows = this.list_sidang.length;
      // for (let index = 0; index < this.list_sidang.length; index++) {
      // const element = this.list_sidang[index];
      // Axios.get(
      //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //     element.topik.id_pengaju
      // ).then(response => {
      // element.nama = this.cekNamaMahasiswa(element.topik.id_pengaju);
      // });
      //   if (index == this.list_sidang.length - 1) {
      //     this.$refs.tableAcaraSidang.refresh();
      //   }
      // }
      this.isBusyAcara = false;
      this.getListHistorySidang();
    },
    changeDataHistoryTopik() {
      this.totalRowsHistory = this.list_history_sidang.length;
      // for (let index = 0; index < this.list_history_sidang.length; index++) {
      //   const element = this.list_history_sidang[index];
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     element.topik.id_pengaju
      //   // ).then(response => {
      //   element.nama = this.cekNamaMahasiswa(element.topik.id_pengaju);
      //   // });
      //   if (index == this.list_history_sidang.length - 1) {
      //     this.$refs.tableHistorySidang.refresh();
      //   }
      // }
      this.isBusyHistory = false;
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
    onFilteredHistory(filteredItems) {
      this.currentPageHistory = 1;
      this.totalRowsHistory = filteredItems.length;
    },
  },
  watch: {
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getListSidang();
      }
    },
  },
};
</script>
<style scoped>
/* // * Acara Sidang */
.acara-sidang {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.pilihan-batch,
.component-filter {
  margin: auto;
}

/* // * Css Table */
.table-acara-sidang {
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