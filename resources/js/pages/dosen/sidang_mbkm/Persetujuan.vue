<template>
  <b-container>
    <!-- // ? Permintaan -->
    <div class="permintaan">
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website mt-2">Permintaan Persetujuan Sidang MBKM</h3>
        </div>
        <!-- // ? Pilih Batch -->
        <div class="col-3 pilihan-batch">
          <b-form-select v-model="id_batch" size="sm">
            <!-- This slot appears above the options from 'options' prop -->
            <template v-slot:first>
              <option :value="null" disabled>-- Pilih Batch --</option>
            </template>

            <!-- These options will appear after the ones from 'options' prop -->
            <option v-for="pilihan in pilihan_batch" :key="pilihan.id_batch + 'a'" :value="pilihan.id_batch">
              {{ pilihan.nama_batch }}
            </option>
          </b-form-select>
        </div>
        <!-- // ? Filter -->
        <div class="col-3 component-filter">
          <b-form-group label-size="sm" label-for="filterInput" class="mb-0">
            <b-input-group size="sm">
              <b-form-input v-model="filter" type="search" id="filterInput"
                placeholder="Ketik untuk mencari"></b-form-input>
              <b-input-group-append>
                <b-button class="btn-form" :disabled="!filter" @click="filter = ''">Hapus</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </div>
        <!-- // ? ######################################################################## -->
      </div>
      <div class="form-persetujuan">
        <!-- // * Button Select All, Clear All -->
        <!-- <div class="btn-select">
          <template v-if="selected.length != list_pembimbing.length">
            <b-button size="sm" @click="selectAllRows" class="btn-form">Select all</b-button>
          </template>
          <template v-if="Array.isArray(selected) && selected.length">
            <b-button size="sm" @click="clearSelected" class="btn-form">Clear selected</b-button>
          </template>
        </div>-->

        <b-table sticky-header class="table-persetujuan" ref="tablePersetujuan" selectable :select-mode="selectMode"
          selected-variant="active" hover striped :items="list_pembimbing" :fields="fields"
          @row-selected="onRowSelected" responsive="sm" :busy="isBusyPersetujuan" show-empty :current-page="currentPage"
          :per-page="perPage" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :filter="filter" @filtered="onFiltered">
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <ring-loader class="loading-page" color="#20a506" :size="50" />
              <strong class="loading-text">Loading...</strong>
            </div>
          </template>
          <template v-slot:empty>
            <h5 class="text-center">Tidak ada data permintaan persetujuan</h5>
          </template>
          <!-- A custom formatted header cell for field 'name' -->
          <template v-slot:head(selected)>
            <div class="box-select">
              <template v-if="
                selected.length < list_pembimbing.length &&
                selected.length > 0
              ">
                <span aria-hidden="true" @click="selectAllRows" class="simbol">&minus;</span>
                <span class="sr-only" @click="selectAllRows">Select All</span>
              </template>
              <template v-else-if="Array.isArray(selected) && selected.length">
                <span aria-hidden="true" @click="clearSelected" class="simbol">&check;</span>
                <span class="sr-only" @click="clearSelected">Clear Selected</span>
              </template>
              <template v-else>
                <span aria-hidden="true" @click="selectAllRows" class="simbol">&#9744;</span>
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
          <template v-slot:cell(nrp)="data">{{
            data.item.topik.id_pengaju
          }}</template>
          <template v-slot:cell(nama)="data">{{ data.item.nama }}</template>
          <template v-slot:cell(judul_topik)="data">{{
            data.item.topik.judul_topik
          }}</template>
          <template v-slot:cell(bimbingan)="data">
            <router-link :to="`/sidang/bimbingan/${acakAcak(data.item.id_sidang, 0)}`"
              class="btn btn-form">Lihat</router-link>
          </template>
        </b-table>

        <b-row>
          <!-- // ? Pagination -->
          <b-col>
            <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage" align="fill" size="sm"
              class="mb-0"></b-pagination>
          </b-col>
          <!-- // ? ######################################################################## -->
          <b-col class="text-right">
            <template v-if="Array.isArray(selected) && selected.length" class="btn-select">
              <div class="btn-select">
                <b-button class="btn-form" @click="terimaPengajuanSidang(selected)">Terima</b-button>
                <b-button class="btn-form" @click="tolakPengajuanSidang(selected)">Tolak</b-button>
              </div>
            </template>
            <template v-else class="btn-select">
              <div class="btn-select">
                <b-button class="btn-form" disabled>Terima</b-button>
                <b-button class="btn-form" disabled>Tolak</b-button>
              </div>
            </template>
          </b-col>
        </b-row>
      </div>
    </div>

    <!-- // ? History -->
    <div class="history">
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website mt-2">History Persetujuan Sidang</h3>
        </div>
        <div class="col-3 pilihan-batch">
          <b-form-select v-model="id_batch" size="sm">
            <!-- This slot appears above the options from 'options' prop -->
            <template v-slot:first>
              <option :value="null" disabled>-- Pilih Batch --</option>
            </template>

            <!-- These options will appear after the ones from 'options' prop -->
            <option v-for="pilihan in pilihan_batch" :key="pilihan.id_batch + 'a'" :value="pilihan.id_batch">
              {{ pilihan.nama_batch }}
            </option>
          </b-form-select>
        </div>
        <!-- // ? Filter -->
        <div class="col-3 component-filter">
          <b-form-group label-size="sm" label-for="filterInputHistory" class="mb-0">
            <b-input-group size="sm">
              <b-form-input v-model="filter_history" type="search" id="filterInputHistory"
                placeholder="Ketik untuk mencari"></b-form-input>
              <b-input-group-append>
                <b-button class="btn-form" :disabled="!filter_history" @click="filter_history = ''">Hapus</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </div>
        <!-- // ? ######################################################################## -->
      </div>
      <div class="form-persetujuan">
        <b-table sticky-header class="table-persetujuan" ref="tableHistory" selectable :select-mode="selectMode"
          selected-variant="active" hover striped :items="list_history_pembimbing" :fields="fields_history"
          @row-selected="onRowSelectedHistory" responsive="sm" :busy="isBusyHistory" show-empty
          :current-page="currentPageHistory" :per-page="perPage" :sort-by.sync="sortByHistory"
          :sort-desc.sync="sortDescHistory" :filter="filter_history" @filtered="onFilteredHistory">
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <ring-loader class="loading-page" color="#20a506" :size="50" />
              <strong class="loading-text">Loading...</strong>
            </div>
          </template>
          <template v-slot:empty>
            <h5 class="text-center">Tidak ada data</h5>
          </template>
          <!-- A custom formatted header cell for field 'name' -->
          <template v-slot:head(selected)>
            <div class="box-select">
              <template v-if="
                selected_history.length < list_history_pembimbing.length &&
                selected_history.length > 0
              ">
                <span aria-hidden="true" @click="selectAllRowsHistory" class="simbol">&minus;</span>
                <span class="sr-only" @click="selectAllRowsHistory">Select All</span>
              </template>
              <template v-else-if="
                Array.isArray(selected_history) && selected_history.length
              ">
                <span aria-hidden="true" @click="clearSelectedHistory" class="simbol">&check;</span>
                <span class="sr-only" @click="clearSelectedHistory">Clear Selected</span>
              </template>
              <template v-else>
                <span aria-hidden="true" @click="selectAllRowsHistory" class="simbol">&#9744;</span>
                <span class="sr-only" @click="selectAllRowsHistory">Select All</span>
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
          <template v-slot:cell(nrp)="data">{{
            data.item.topik.id_pengaju
          }}</template>
          <template v-slot:cell(nama)="data">{{ data.item.nama }}</template>
          <template v-slot:cell(judul_topik)="data">{{
            data.item.topik.judul_topik
          }}</template>
          <template v-slot:cell(status_pembimbing_utama)="data">{{
            cekStatusPembimbingUtama(data.item.status_pembimbing_utama)
          }}</template>
          <template v-slot:cell(status_pembimbing_lapangan)="data">{{
            cekStatusPembimbingLapangan(data.item.status_pembimbing_lapangan)
          }}</template>
          <template v-slot:cell(bimbingan)="data">
            <router-link :to="`/sidang/bimbingan/${acakAcak(data.item.id_sidang, 0)}`"
              class="btn btn-form">Lihat</router-link>
          </template>
        </b-table>

        <b-row>
          <!-- // ? Pagination -->
          <b-col>
            <b-pagination v-model="currentPageHistory" :total-rows="totalRowsHistory" :per-page="perPage" align="fill"
              size="sm" class="mb-0"></b-pagination>
          </b-col>
          <!-- // ? ######################################################################## -->
          <b-col class="text-right">
            <template v-if="Array.isArray(selected_history) && selected_history.length" class="btn-select">
              <div class="btn-select">
                <b-button class="btn-form" @click="terimaPengajuanSidang(selected_history)">Terima</b-button>
                <b-button class="btn-form" @click="tolakPengajuanSidang(selected_history)">Tolak</b-button>
              </div>
            </template>
            <template v-else class="btn-select">
              <div class="btn-select">
                <b-button class="btn-form" disabled>Terima</b-button>
                <b-button class="btn-form" disabled>Tolak</b-button>
              </div>
            </template>
          </b-col>
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
      fields: [
        { key: "selected", label: "", sortable: false },
        { key: "nrp", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "bimbingan", sortable: false },
      ],
      topik: [],
      list_pembimbing_utama: [],
      list_pembimbing_lapangan: [],
      list_pembimbing: [],
      selectMode: "multi",
      selected: [],
      loading: false,
      id_pengguna: this.$store.getters.pengguna.UserId,
      id_batch: null,
      isBusyPersetujuan: true,
      isBusyHistory: true,
      list_history_pembimbing_utama: [],
      list_history_pembimbing_lapangan: [],
      list_history_pembimbing: [],
      selected_history: [],
      fields_history: [
        { key: "selected", label: "", sortable: false },
        { key: "nrp", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        {
          key: "status_pembimbing_utama",
          label: "Status Pembimbing",
          sortable: true,
        },
        { key: "status_pembimbing_lapangan", sortable: true },
        { key: "bimbingan", sortable: false },
      ],
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
      //   // this.getListPersetujuanPembimbingUtama();
      // } else {
      //   this.$store.dispatch("batchSidang").then(response => {
      //     this.id_batch = this.$store.getters.batchSidang.id_batch;
      //     // this.getListPersetujuanPembimbingUtama();
      //   });
      // }
    },
    onRowSelected(items) {
      this.selected = items;
    },
    selectAllRows() {
      this.$refs.tablePersetujuan.selectAllRows();
    },
    clearSelected() {
      this.$refs.tablePersetujuan.clearSelected();
    },
    onRowSelectedHistory(items) {
      this.selected_history = items;
    },
    selectAllRowsHistory() {
      this.$refs.tableHistory.selectAllRows();
    },
    clearSelectedHistory() {
      this.$refs.tableHistory.clearSelected();
    },
    getListPersetujuanPembimbingUtama() {
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-persetujuan-pembimbing-utama`,
        {
          id_pembimbing: this.id_pengguna,
          id_batch: this.id_batch,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          console.log(response)
          if (response.data.message != "kosong") {
            this.list_pembimbing = response.data.data;
          } else {
            this.list_pembimbing = [];
          }
          this.getListPersetujuanPembimbingLapangan();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getListPersetujuanPembimbingLapangan() {
      this.isBusyPersetujuan = true;
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-persetujuan-pembimbing-lapangan`,
        {
          id_pembimbing_lapangan: this.id_pengguna,
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
          } else {
            this.list_pembimbing_lapangan = [];
          }
          this.updateList();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    updateList() {
      this.list_pembimbing_lapangan.forEach((element) => {
        if (this.list_pembimbing.indexOf(element) === -1) {
          this.list_pembimbing.push(element);
        }
      });
      this.getListMahasiswa(1);
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
      this.totalRows = this.list_pembimbing.length;
      // for (let index = 0; index < this.list_pembimbing.length; index++) {
      //   const element = this.list_pembimbing[index];
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     element.topik.id_pengaju
      //   // ).then(response => {
      //   element.nama = this.cekNamaMahasiswa(element.topik.id_pengaju);
      //   // });
      //   if (index == this.list_pembimbing.length - 1) {
      //     this.$refs.tablePersetujuan.refresh();
      //   }
      // }
      this.isBusyPersetujuan = false;
      this.getListHistoryPersetujuanPembimbingUtama();
    },
    getListHistoryPersetujuanPembimbingUtama() {
      this.isBusyHistory = true;
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-history-persetujuan-pembimbing-utama`,
        {
          id_pembimbing: this.id_pengguna,
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
            this.list_history_pembimbing = response.data.data;
          } else {
            this.list_history_pembimbing = [];
          }
          this.getListHistoryPersetujuanPembimbingLapangan();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getListHistoryPersetujuanPembimbingLapangan() {
      Axios.post(
        `${config.apiDosenUrl}/sidang/list-history-persetujuan-pembimbing-lapangan`,
        {
          id_pembimbing_lapangan: this.id_pengguna,
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
            this.list_history_pembimbing_lapangan = response.data.data;
          } else {
            this.list_history_pembimbing_lapangan = [];
          }
          this.updateListHistory();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    updateListHistory() {
      this.list_history_pembimbing_lapangan.forEach((element) => {
        if (this.list_history_pembimbing.indexOf(element) === -1) {
          this.list_history_pembimbing.push(element);
        }
      });
      this.getListMahasiswa(2);
    },
    changeDataHistoryTopik() {
      this.totalRowsHistory = this.list_history_pembimbing.length;
      // for (
      //   let index = 0;
      //   index < this.list_history_pembimbing.length;
      //   index++
      // ) {
      //   const element = this.list_history_pembimbing[index];
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     element.topik.id_pengaju
      //   // ).then(response => {
      //   element.nama = this.cekNamaMahasiswa(element.topik.id_pengaju);
      //   // });
      //   if (index == this.list_history_pembimbing.length) {
      //     this.$refs.tableHistory.refresh();
      //   }
      // }
      this.isBusyHistory = false;
    },
    terimaPengajuanSidang(sidang) {
      this.loading = true;
      sidang.forEach((element) => {
        this.statusPembimbingUtama(element.id_sidang, 1);
        if (element.topik.id_pembimbing_lapangan) {
          this.statusPembimbingLapangan(element.id_sidang, 1);
        } else {
          this.statusPembimbingLapangan(element.id_sidang, 3);
        }
      });
      this.getListPersetujuanPembimbingUtama();
    },
    tolakPengajuanSidang(sidang) {
      this.loading = true;
      sidang.forEach((element) => {
        this.statusPembimbingUtama(element.id_sidang, 0);
        if (element.topik.id_pembimbing_lapangan) {
          this.statusPembimbingLapangan(element.id_sidang, 0);
        } else {
          this.statusPembimbingLapangan(element.id_sidang, 3);
        }
      });
      this.getListPersetujuanPembimbingUtama();
    },
    statusPembimbingUtama(id, status) {
      Axios.put(
        `${config.apiDosenUrl}/sidang/update-persetujuan-pembimbing-utama`,
        {
          id_sidang: id,
          id_pembimbing: this.id_pengguna,
          status_pembimbing_utama: status,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          // this.postKomentar(element.id_topik);
        })
        .catch((response) => {
          console.log(response);
        });
    },
    statusPembimbingLapangan(id, status) {
      Axios.put(
        `${config.apiDosenUrl}/sidang/update-persetujuan-pembimbing-lapangan`,
        {
          id_sidang: id,
          id_pembimbing_lapangan: this.id_pengguna,
          status_pembimbing_lapangan: status,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          // this.postKomentar(element.id_topik);
        })
        .catch((response) => {
          console.log(response);
        });
    },
    cekStatusPembimbingUtama(index) {
      const status = ["ditolak", "diterima", "menunggu persetujuan"];
      if ((index) => 0 && index <= status.length) {
        return status[index];
      } else {
        return "";
      }
    },
    cekStatusPembimbingLapangan(index) {
      const status = [
        "ditolak",
        "diterima",
        "menunggu persetujuan",
        "tidak membutuhkan persetujuan",
      ];
      if ((index) => 0 && index <= status.length) {
        return status[index];
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
  computed: {
    authUser() {
      return this.$root.auth;
    },
  },
  watch: {
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getListPersetujuanPembimbingUtama();
      }
    },
  },
};
</script>

<style scoped>
.table-persetujuan {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}

.form-persetujuan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.pilihan-batch,
.component-filter {
  margin: auto;
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