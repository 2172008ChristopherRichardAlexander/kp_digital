<template>
  <b-container>
    <div class="form-daftar-revisi">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Konfirmasi Revisi</h3>
        </div>
      </div>
      <!-- // ? Revisi -->
      <div class="daftar-revisi">
        <div class="row my-2">
          <div class="col text-left">
            <h4 class="keterangan-website">
              Daftar Permintaan Konfirmasi Revisi
            </h4>
          </div>
          <div class="col-3 pilihan-batch">
            <b-form-select v-model="id_batch" size="sm">
              <!-- This slot appears above the options from 'options' prop -->
              <template v-slot:first>
                <option :value="null" disabled>-- Pilih Batch --</option>
              </template>

              <!-- These options will appear after the ones from 'options' prop -->
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
        <div class="daftar-revisi-item">
          <b-table
            sticky-header
            class="table-daftar-revisi"
            ref="tableKonfirmasi"
            selectable
            :select-mode="selectMode"
            selected-variant="active"
            :items="list_revisi"
            :fields="fields_revisi"
            hover
            striped
            @row-selected="onRowSelected"
            responsive="sm"
            :busy="isBusyRevisi"
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
              <h5 class="text-center">
                Tidak ada data permintaan konfirmasi revisi
              </h5>
            </template>
            <template v-slot:cell(nama)="data">
              <!-- <div v-if="data.item.nama"> -->
              {{ data.item.nama }}
              <!-- </div> -->
              <!-- <div v-else>{{data.item.topik.id_pengaju}}</div> -->
            </template>

            <!-- A custom formatted header cell for field 'name' -->
            <template v-slot:head(selected)>
              <div class="box-select">
                <template
                  v-if="
                    selected.length < list_revisi.length && selected.length > 0
                  "
                >
                  <span aria-hidden="true" @click="selectAllRows" class="simbol"
                    >&minus;</span
                  >
                  <span class="sr-only" @click="selectAllRows">Select All</span>
                </template>
                <template
                  v-else-if="Array.isArray(selected) && selected.length"
                >
                  <span aria-hidden="true" @click="clearSelected" class="simbol"
                    >&check;</span
                  >
                  <span class="sr-only" @click="clearSelected"
                    >Clear Selected</span
                  >
                </template>
                <template v-else>
                  <span aria-hidden="true" @click="selectAllRows" class="simbol"
                    >&#9744;</span
                  >
                  <span class="sr-only" @click="selectAllRows">Select All</span>
                </template>
              </div>
            </template>
            <!-- Example scoped slot for select state illustrative purposes -->
            <template v-slot:cell(selected)="{ rowSelected }">
              <template v-if="rowSelected">
                <span aria-hidden="true" class="simbol">&check;</span>
                <span class="sr-only">Selected</span>
              </template>
              <template v-else>
                <span aria-hidden="true" class="simbol">&#9744;</span>
                <span class="sr-only">Not selected</span>
              </template>
            </template>

            <template v-slot:cell(judul_topik)="data">{{
              data.item.topik.judul_topik
            }}</template>

            <template v-slot:cell(revisi)="data">
              <router-link
                :to="`/sidang/list-revisi/${acakAcak(data.item.id_sidang, 0)}`"
                class="btn btn-form"
                >Lihat</router-link
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
            <b-col class="text-right">
              <template
                v-if="Array.isArray(selected) && selected.length"
                class="btn-select"
              >
                <div class="btn-select">
                  <b-button class="btn-form" @click="konfirmasiRevisi"
                    >Konfirmasi</b-button
                  >
                </div>
              </template>
              <template v-else class="btn-select">
                <div class="btn-select">
                  <b-button class="btn-form" disabled>Konfirmasi</b-button>
                </div>
              </template>
            </b-col>
          </b-row>
        </div>

        <!-- // ? History -->
        <div class="row my-2">
          <div class="col text-left">
            <h4 class="keterangan-website">Daftar History Revisi</h4>
          </div>
          <!-- // ? Pilih Batch -->
          <div class="col-3 pilihan-batch">
            <b-form-select v-model="id_batch" size="sm">
              <!-- This slot appears above the options from 'options' prop -->
              <template v-slot:first>
                <option :value="null" disabled>-- Pilih Batch --</option>
              </template>

              <!-- These options will appear after the ones from 'options' prop -->
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
        <div class="daftar-revisi-item">
          <b-table
            sticky-header
            class="table-daftar-revisi"
            :items="list_history_revisi"
            :fields="fields_history"
            ref="tableHistoryRevisi"
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
              <h5 class="text-center">Tidak ada data</h5>
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

            <template v-slot:cell(status_revisi_pembimbing)="data">{{
              cekStatusPembimbing(data.item.status_revisi_pembimbing)
            }}</template>
            <template v-slot:cell(status_revisi_penguji)="data">{{
              cekStatusPenguji(data.item.status_revisi_penguji)
            }}</template>

            <template v-slot:cell(revisi)="data">
              <router-link
                :to="`/sidang/list-revisi/${acakAcak(data.item.id_sidang, 0)}`"
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
      sortBy: "topik.id_pengaju",
      sortDesc: false,
      sortByHistory: "topik.id_pengaju",
      sortDescHistory: false,
      // * Table
      selectMode: "multi",
      list_revisi: [],
      list_revisi_penguji: [],
      id_pengguna: this.$store.getters.pengguna.UserId,
      fields_revisi: [
        { key: "selected", label: "", sortable: false },
        { key: "topik.id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "revisi", sortable: false },
      ],
      selected: [],
      fields_history: [
        { key: "topik.id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        {
          key: "status_revisi_pembimbing",
          label: "Status Pembimbing",
          sortable: true,
        },
        {
          key: "status_revisi_penguji",
          label: "Status Penguji",
          sortable: true,
        },
        { key: "revisi", sortable: false },
      ],
      list_history_revisi: [],
      list_history_revisi_penguji: [],
      loading: false,
      isBusyRevisi: true,
      isBusyHistory: true,
      id_batch: null,
      jenis_konfirmasi: "",
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
    onRowSelected(items) {
      this.selected = items;
    },
    selectAllRows() {
      this.$refs.tableKonfirmasi.selectAllRows();
    },
    clearSelected() {
      this.$refs.tableKonfirmasi.clearSelected();
    },
    getListSidang() {
      this.loading = true;
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-konfirmasi-revisi-pembimbing-utama`,
        {
          id_batch: this.id_batch,
          id_pembimbing: this.id_pengguna,
          status_sidang: 1,
          status_revisi_pembimbing: 2,
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
            this.list_revisi = response.data.data;
          } else {
            this.list_revisi = [];
          }
          this.getListSidangPenguji();
        })
        .catch((response) => {
          console.log(response);
          this.loading = false;
        });
    },
    getListSidangPenguji() {
      this.loading = true;
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-konfirmasi-revisi-penguji`,
        {
          id_batch: this.id_batch,
          id_penguji: this.id_pengguna,
          status_sidang: 1,
          status_revisi_penguji: 2,
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
            this.list_revisi_penguji = response.data.data;
            this.list_revisi_penguji.forEach((element) => {
              this.list_revisi.push(element);
            });
          } else {
            this.list_revisi_penguji = [];
          }
          this.getListMahasiswa(1);
        })
        .catch((response) => {
          console.log(response);
          this.loading = false;
        });
    },
    getListMahasiswa(status) {
      if (this.$store.getters.listMahasiswa) {
        if (status == 1) this.changeDataTopik();
        else if (status == 2) this.changeDataTopikHistory();
      } else {
        this.$store.dispatch("listMahasiswa").then((response) => {
          if (status == 1) this.changeDataTopik();
          else if (status == 2) this.changeDataTopikHistory();
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
      this.totalRows = this.list_revisi.length;
      // for (let index = 0; index < this.list_revisi.length; index++) {
      //   const element = this.list_revisi[index];
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     element.topik.id_pengaju
      //   // ).then(response => {
      //   element.nama = this.cekNamaMahasiswa(element.topik.id_pengaju);
      //   // });
      //   if (index == this.list_revisi.length - 1) {
      //     this.$refs.tableKonfirmasi.refresh();
      //   }
      // }
      this.isBusyRevisi = false;
      this.getListHistorySidang();
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
            this.list_history_revisi = response.data.data;
          } else {
            this.list_history_revisi = [];
          }
          this.getListHistorySidangPenguji();
        })
        .catch((response) => {
          console.log(response);
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
          if (response.data.message != "kosong") {
            this.list_history_revisi_penguji = response.data.data;
            this.list_history_revisi_penguji.forEach((element) => {
              this.list_history_revisi.push(element);
            });
          } else {
            this.list_history_revisi_penguji = [];
          }
          this.getListMahasiswa(2);
        })
        .catch((response) => {
          console.log(response);
          this.loading = false;
        });
    },
    changeDataTopikHistory() {
      this.totalRowsHistory = this.list_history_revisi.length;
      // for (let index = 0; index < this.list_history_revisi.length; index++) {
      //   const element = this.list_history_revisi[index];
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     element.topik.id_pengaju
      //   // ).then(response => {
      //   element.nama = this.cekNamaMahasiswa(element.topik.id_pengaju);
      //   // });
      //   if (index == this.list_history_revisi.length - 1) {
      //     this.$refs.tableHistoryRevisi.refresh();
      //   }
      // }
      this.isBusyHistory = false;
    },
    konfirmasiRevisi() {
      this.selected.forEach((element) => {
        if (element.topik.id_pembimbing == this.id_pengguna) {
          this.jenis_konfirmasi = "status_revisi_pembimbing";
        } else if (element.id_penguji_sidang == this.id_pengguna) {
          this.jenis_konfirmasi = "status_revisi_penguji";
        }
        Axios.put(
          `${config.apiDosenUrl}/sidang/revisi/konfirmasi`,
          {
            id_sidang: element.id_sidang,
            jenis_konfirmasi: this.jenis_konfirmasi,
            status_revisi: 1,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then((response) => {
            this.loading = false;
            this.getListSidang();
          })
          .catch((response) => {
            this.loading = false;
            console.log(response);
          });
      });
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
    cekStatusPembimbing(index) {
      var status = ["ditolak", "diterima", "belum di konfirmasi"];
      if (index >= 0 && index <= status.length) {
        return status[index];
      } else {
        return "";
      }
    },
    cekStatusPenguji(index) {
      var status = ["ditolak", "diterima", "belum di konfirmasi"];
      if (index >= 0 && index <= status.length) {
        return status[index];
      } else {
        return "";
      }
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
.daftar-revisi {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.pilihan-batch,
.component-filter {
  margin: auto;
}

/* // * Css Table */
.table-daftar-revisi {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}
/* // * CSS Button */
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

/* // * Sibol Select ALl, Clear All */
.simbol {
  color: rgb(175, 170, 6);
  cursor: pointer;
  font-size: 15px;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>