<template>
  <b-container>
    <div>
      <!-- // ? Pemeriksaan -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website mt-2">
            Pemeriksaan Kelengkapan Dokumen
          </h3>
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
      <div class="form-persetujuan">
        <!-- // * Button Select All, Clear All -->
        <!-- <div class="btn-select">
          <template v-if="selected.length != topik_pemeriksaan.length">
            <b-button size="sm" @click="selectAllRows" class="btn-form">Select all</b-button>
          </template>
          <template v-if="Array.isArray(selected) && selected.length">
            <b-button size="sm" @click="clearSelected" class="btn-form">Clear selected</b-button>
          </template>
        </div>-->
        <b-table
          sticky-header
          class="table-persetujuan"
          ref="tablePemeriksaan"
          selectable
          :select-mode="selectMode"
          selected-variant="active"
          hover
          striped
          :items="topik_pemeriksaan"
          :fields="fields_pemeriksaan"
          @row-selected="onRowSelected"
          responsive="sm"
          :busy="isBusyPemeriksaan"
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
              Tidak ada data yang perlu dilakukan pemeriksaan dokumen
            </h5>
          </template>
          <!-- A custom formatted header cell for field 'name' -->
          <template v-slot:head(selected)>
            <div class="box-select">
              <!-- // TODO: Belum di uji coba -->
              <template
                v-if="
                  selected_pemeriksaan.length < topik_pemeriksaan.length &&
                  selected_pemeriksaan.length > 0
                "
              >
                <span aria-hidden="true" @click="selectAllRows" class="simbol"
                  >&minus;</span
                >
                <span class="sr-only" @click="selectAllRows">Select All</span>
              </template>
              <template
                v-else-if="
                  Array.isArray(selected_pemeriksaan) &&
                  selected_pemeriksaan.length
                "
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

          <template v-slot:cell(detail)="data">
            <router-link
              :to="`/topik/kelengkapan-dokumen/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>

        <!-- // * Form Komentar -->
        <b-form-textarea
          id="komentar-permintaan"
          placeholder="Komentar (Opsional)"
          rows="3"
          no-resize
          v-model="text_komentar"
        ></b-form-textarea>

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
            <!-- // * Button Terima Tolak Revisi -->
            <template
              v-if="
                Array.isArray(selected_pemeriksaan) &&
                selected_pemeriksaan.length
              "
              class="btn-select"
            >
              <div class="btn-select">
                <b-button
                  class="btn-form"
                  @click="terimaTopik(selected_pemeriksaan)"
                  >Terima</b-button
                >
                <b-button
                  class="btn-form"
                  @click="tolakTopik(selected_pemeriksaan)"
                  >Tolak</b-button
                >
                <b-button
                  class="btn-form"
                  @click="revisiTopik(selected_pemeriksaan)"
                  >Revisi</b-button
                >
              </div>
            </template>
            <template v-else class="btn-select">
              <div class="btn-select">
                <b-button class="btn-form" disabled>Terima</b-button>
                <b-button class="btn-form" disabled>Tolak</b-button>
                <b-button class="btn-form" disabled>Revisi</b-button>
              </div>
            </template>
          </b-col>
        </b-row>
      </div>
      <!-- // ? ##################################### PERMINTAAN ##################################### -->
      <!-- // ? Revisi -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website mt-2">Dalam Proses Revisi</h3>
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
          <b-form-group
            label-size="sm"
            label-for="filterInputRevisi"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                v-model="filter_revisi"
                type="search"
                id="filterInputRevisi"
                placeholder="Ketik untuk mencari"
              ></b-form-input>
              <b-input-group-append>
                <b-button
                  class="btn-form"
                  :disabled="!filter_revisi"
                  @click="filter_revisi = ''"
                  >Hapus</b-button
                >
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </div>
        <!-- // ? ######################################################################## -->
      </div>
      <div class="form-persetujuan">
        <b-table
          sticky-header
          class="table-persetujuan"
          ref="tableRevisi"
          selectable
          :select-mode="selectMode"
          selected-variant="active"
          hover
          striped
          :items="topik_revisi"
          :fields="fields_revisi"
          @row-selected="onRowSelectedRevisi"
          responsive="sm"
          :busy="isBusyRevisi"
          show-empty
          :current-page="currentPageRevisi"
          :per-page="perPage"
          :sort-by.sync="sortByRevisi"
          :sort-desc.sync="sortDescRevisi"
          :filter="filter_revisi"
          @filtered="onFilteredRevisi"
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
          <!-- A custom formatted header cell for field 'name' -->
          <template v-slot:head(selected)>
            <div class="box-select">
              <!-- // TODO: Belum di uji coba -->
              <template
                v-if="
                  selected_revisi.length < topik_revisi.length &&
                  selected_revisi.length > 0
                "
              >
                <span
                  aria-hidden="true"
                  @click="selectAllRowsRevisi"
                  class="simbol"
                  >&minus;</span
                >
                <span class="sr-only" @click="selectAllRowsRevisi"
                  >Select All</span
                >
              </template>
              <template
                v-else-if="
                  Array.isArray(selected_revisi) && selected_revisi.length
                "
              >
                <span
                  aria-hidden="true"
                  @click="clearSelectedRevisi"
                  class="simbol"
                  >&check;</span
                >
                <span class="sr-only" @click="clearSelectedRevisi"
                  >Clear Selected</span
                >
              </template>
              <template v-else>
                <span
                  aria-hidden="true"
                  @click="selectAllRowsRevisi"
                  class="simbol"
                  >&#9744;</span
                >
                <span class="sr-only" @click="selectAllRowsRevisi"
                  >Select All</span
                >
              </template>
            </div>
          </template>
          <!-- Example scoped slot for select state illustrative purposes -->
          <template v-slot:cell(selected)="{ rowSelected }">
            <template v-if="rowSelected">
              <span aria-hidden="true" style="color: rgb(175, 170, 6)"
                >&check;</span
              >
              <span class="sr-only">Selected</span>
            </template>
            <template v-else>
              <span aria-hidden="true" style="color: rgb(175, 170, 6)"
                >&#9744;</span
              >
              <span class="sr-only">Not selected</span>
            </template>
          </template>

          <template v-slot:cell(detail)="data">
            <router-link
              :to="`/topik/kelengkapan-dokumen/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>

        <!-- // * Form Komentar -->
        <b-form-textarea
          id="komentar-permintaan"
          placeholder="Komentar (Opsional)"
          rows="3"
          no-resize
          v-model="text_komentar"
        ></b-form-textarea>
        <b-row>
          <!-- // ? Pagination -->
          <b-col>
            <b-pagination
              v-model="currentPageRevisi"
              :total-rows="totalRowsRevisi"
              :per-page="perPage"
              align="fill"
              size="sm"
              class="mb-0"
            ></b-pagination>
          </b-col>
          <!-- // ? ######################################################################## -->
          <b-col class="text-right">
            <!-- // * Button Terima Tolak -->
            <template
              v-if="Array.isArray(selected_revisi) && selected_revisi.length"
              class="btn-select"
            >
              <div class="btn-select">
                <b-button class="btn-form" @click="terimaTopik(selected_revisi)"
                  >Terima</b-button
                >
                <b-button class="btn-form" @click="tolakTopik(selected_revisi)"
                  >Tolak</b-button
                >
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
      <!-- // ? ##################################### REVISI ##################################### -->
      <!-- // ? History -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website mt-2">
            History Pemeriksaan Kelengkapan Dokumen
          </h3>
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
      <div class="form-persetujuan">
        <b-table
          sticky-header
          class="table-persetujuan"
          ref="tableHistory"
          selectable
          :select-mode="selectMode"
          selected-variant="active"
          hover
          striped
          :items="topik_history"
          :fields="fields_history"
          @row-selected="onRowSelectedHistory"
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
          <!-- A custom formatted header cell for field 'name' -->
          <template v-slot:head(selected)>
            <div class="box-select">
              <!-- // TODO: Belum di uji coba -->
              <template
                v-if="
                  selected_history.length < topik_history.length &&
                  selected_history.length > 0
                "
              >
                <span
                  aria-hidden="true"
                  @click="selectAllRowsHistory"
                  class="simbol"
                  >&minus;</span
                >
                <span class="sr-only" @click="selectAllRowsHistory"
                  >Select All</span
                >
              </template>
              <template
                v-else-if="
                  Array.isArray(selected_history) && selected_history.length
                "
              >
                <span
                  aria-hidden="true"
                  @click="clearSelectedHistory"
                  class="simbol"
                  >&check;</span
                >
                <span class="sr-only" @click="clearSelectedHistory"
                  >Clear Selected</span
                >
              </template>
              <template v-else>
                <span
                  aria-hidden="true"
                  @click="selectAllRowsHistory"
                  class="simbol"
                  >&#9744;</span
                >
                <span class="sr-only" @click="selectAllRowsHistory"
                  >Select All</span
                >
              </template>
            </div>
          </template>
          <!-- Example scoped slot for select state illustrative purposes -->
          <template v-slot:cell(selected)="{ rowSelected }">
            <template v-if="rowSelected">
              <span aria-hidden="true" style="color: rgb(175, 170, 6)"
                >&check;</span
              >
              <span class="sr-only">Selected</span>
            </template>
            <template v-else>
              <span aria-hidden="true" style="color: rgb(175, 170, 6)"
                >&#9744;</span
              >
              <span class="sr-only">Not selected</span>
            </template>
          </template>

          <template v-slot:cell(detail)="data">
            <router-link
              :to="`/topik/kelengkapan-dokumen/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>

        <!-- // * Form Komentar -->
        <b-form-textarea
          id="komentar-permintaan"
          placeholder="Komentar (Opsional)"
          rows="3"
          no-resize
          v-model="text_komentar"
        ></b-form-textarea>
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
          <b-col class="text-right">
            <!-- // * Button Terima Tolak Revisi -->
            <template
              v-if="Array.isArray(selected_history) && selected_history.length"
              class="btn-select"
            >
              <div class="btn-select">
                <b-button
                  class="btn-form"
                  @click="terimaTopik(selected_history)"
                  >Terima</b-button
                >
                <b-button class="btn-form" @click="tolakTopik(selected_history)"
                  >Tolak</b-button
                >
                <b-button
                  class="btn-form"
                  @click="revisiTopik(selected_history)"
                  >Revisi</b-button
                >
              </div>
            </template>
            <template v-else class="btn-select">
              <div class="btn-select">
                <b-button class="btn-form" disabled>Terima</b-button>
                <b-button class="btn-form" disabled>Tolak</b-button>
                <b-button class="btn-form" disabled>Revisi</b-button>
              </div>
            </template>
          </b-col>
        </b-row>
      </div>
      <!-- // ? ##################################### HISTORY ##################################### -->
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
      filter_history: null,
      filter_revisi: null,
      // * Pagination
      totalRows: 1,
      totalRowsHistory: 1,
      totalRowsRevisi: 1,
      currentPage: 1,
      currentPageHistory: 1,
      currentPageRevisi: 1,
      perPage: 10,
      // * Sorting
      sortBy: "nrp",
      sortDesc: false,
      sortByHistory: "id_pengaju",
      sortDescHistory: false,
      sortByRevisi: "id_topik",
      sortDescRevisi: true,
      // * Table
      fields_pemeriksaan: [
        { key: "selected", label: "", sortable: false },
        { key: "nrp", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        {
          key: "status_persetujuan_dosen",
          label: "Status Dosen",
          sortable: true,
        },
        { key: "detail", sortable: false },
      ],
      fields_revisi: [
        { key: "selected", label: "", sortable: false },
        { key: "id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        {
          key: "status_persetujuan_dosen",
          label: "Status Dosen",
          sortable: true,
        },
        { key: "detail", sortable: false },
      ],
      fields_history: [
        { key: "selected", label: "", sortable: false },
        { key: "id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        {
          key: "status_persetujuan_dosen",
          label: "Status Dosen",
          sortable: true,
        },
        { key: "status_topik", label: "Status Topik", sortable: true },
        { key: "detail", sortable: false },
      ],
      topik_pemeriksaan: [],
      topik_revisi: [],
      topik_history: [],
      selectMode: "multi",
      selected_pemeriksaan: [],
      selected_revisi: [],
      selected_history: [],
      loading: false,
      text_komentar: "",
      id_pengguna: this.$store.getters.pengguna.UserId,
      isBusyPemeriksaan: true,
      isBusyRevisi: true,
      isBusyHistory: true,
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
      // if (!!this.$store.getters.batchTopik) {
      //   this.id_batch = this.$store.getters.batchTopik.id_batch;
      // } else {
      //   this.$store.dispatch("batchTopik").then(response => {
      //     this.id_batch = this.$store.getters.batchTopik.id_batch;
      //   });
      // }
    },
    onRowSelected(items) {
      this.selected_pemeriksaan = items;
    },
    selectAllRows() {
      this.$refs.tablePemeriksaan.selectAllRows();
    },
    clearSelected() {
      this.$refs.tablePemeriksaan.clearSelected();
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
    onRowSelectedRevisi(items) {
      this.selected_revisi = items;
    },
    selectAllRowsRevisi() {
      this.$refs.tableRevisi.selectAllRows();
    },
    clearSelectedRevisi() {
      this.$refs.tableRevisi.clearSelected();
    },
    getListTopikPemeriksaanDokumen() {
      this.isBusyPemeriksaan = true;
      const formData = new FormData();
      formData.append("id_batch", this.id_batch);
      Axios.post(`${config.apiKoorKpUrl}/topik-pemeriksaan-dokumen`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.topik_pemeriksaan = response.data;
            this.totalRows = response.data.length;
          } else {
            this.topik_pemeriksaan = [];
            this.totalRows = 0;
          }
          this.selected_pemeriksaan = [];
          this.getListRevisiTopikPemeriksaanDokumen();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getListRevisiTopikPemeriksaanDokumen() {
      this.isBusyRevisi = true;
      const formData = new FormData();
      formData.append("status_topik", 6);
      formData.append("status_persetujuan_dosen", [1, 2, 4]);
      formData.append("id_batch", this.id_batch);
      Axios.post(`${config.apiUrl}/topik/list-topik`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      }).then((response) => {
        if (response.data.message != "kosong") {
          this.topik_revisi = response.data.data;
          this.totalRowsRevisi = response.data.data.length;
        } else {
          this.topik_revisi = [];
          this.totalRowsRevisi = 0;
        }
      });
      this.selected_revisi = [];
      this.getListTopikHistoryPemeriksaanDokumen();
    },
    getListTopikHistoryPemeriksaanDokumen() {
      this.isBusyHistory = true;
      const formData = new FormData();
      formData.append("id_batch", this.id_batch);
      formData.append("status_topik", [0, 1, 4, 5]);
      formData.append("status_persetujuan_dosen", [0, 1, 2, 4]);
      Axios.post(`${config.apiUrl}/topik/list-topik`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.topik_history = response.data.data;
            this.totalRowsHistory = response.data.data.length;
          } else {
            this.topik_history = [];
            this.totalRowsHistory = 0;
          }
          this.selected_history = [];
          this.getListMahasiswa();
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
      this.topik_pemeriksaan.forEach((element) => {
        // Axios.get(
        //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //     element.nrp
        // ).then(response => {
        // element.nama = this.cekNamaMahasiswa(element.nrp);
        // });
        element.status_topik = this.cekStatusTopik(element.status_topik);
        element.status_persetujuan_dosen = this.cekStatusDosen(
          element.status_persetujuan_dosen
        );
      });
      this.isBusyPemeriksaan = false;
      for (let index = 0; index < this.topik_revisi.length; index++) {
        const element = this.topik_revisi[index];
        // Axios.get(
        //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //     element.id_pengaju
        // ).then(response => {
        // Object.assign(element, {
        //   nama: this.cekNamaMahasiswa(element.id_pengaju)
        // });
        // this.$refs.tableRevisi.refresh();
        // });
        element.status_persetujuan_dosen = this.cekStatusDosen(
          element.status_persetujuan_dosen
        );
        // this.$refs.tableRevisi.refresh();
        if (index == this.topik_revisi.length - 1) {
          this.$refs.tableRevisi.refresh();
        }
      }
      this.isBusyRevisi = false;

      for (let index = 0; index < this.topik_history.length; index++) {
        const element = this.topik_history[index];
        // Axios.get(
        //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //     element.id_pengaju
        // ).then(response => {
        Object.assign(element, {
          nama: this.cekNamaMahasiswa(element.id_pengaju),
        });
        this.$refs.tableHistory.refresh();
        // });
        element.status_topik = this.cekStatusTopik(element.status_topik);
        element.status_persetujuan_dosen = this.cekStatusDosen(
          element.status_persetujuan_dosen
        );
        this.$refs.tableHistory.refresh();
        if (index == this.topik_history.length - 1) {
          this.$refs.tableHistory.refresh();
        }
      }
      this.isBusyHistory = false;
    },
    terimaTopik(topik) {
      this.loading = true;
      topik.forEach((element) => {
        Axios.put(
          `${config.apiKoorKpUrl}/topik`,
          {
            id_topik: element.id_topik,
            status_topik: 1,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then((response) => {
            this.postKomentar(element.id_topik);
          })
          .catch((response) => {
            console.log(response);
          });
      });
      this.getListTopikPemeriksaanDokumen();
      // location.reload();
    },
    tolakTopik(topik) {
      this.loading = true;
      topik.forEach((element) => {
        Axios.put(
          `${config.apiKoorKpUrl}/topik`,
          {
            id_topik: element.id_topik,
            status_topik: 0,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then((response) => {
            this.postKomentar(element.id_topik);
          })
          .catch((response) => {
            console.log(response);
          });
      });
      this.getListTopikPemeriksaanDokumen();
    },
    revisiTopik(topik) {
      this.loading = true;
      topik.forEach((element) => {
        Axios.put(
          `${config.apiKoorKpUrl}/topik`,
          {
            id_topik: element.id_topik,
            status_topik: 6,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then((response) => {
            this.postKomentar(element.id_topik);
          })
          .catch((response) => {
            console.log(response);
          });
      });
      this.getListTopikPemeriksaanDokumen();
    },
    postKomentar(id_topik) {
      if (this.text_komentar != "" && this.text_komentar.length !== 0) {
        const formData = new FormData();
        formData.append("isi_komentar", this.text_komentar);
        formData.append("id_topik", id_topik);
        formData.append("id_pengguna", this.id_pengguna);
        Axios.post(`${config.apiUrl}/komentar`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
          .then((response) => {
            this.loading = false;
          })
          .catch((response) => {
            console.log(response);
          });
      }
      // location.reload();
    },
    cekStatusTopik(status) {
      if (status == 0) {
        return "ditolak Koordinator KP / Dosen";
      } else if (status == 1) {
        return "diterima Koordinator KP";
      } else if (status == 2) {
        return "sedang diajukan";
      } else if (status == 3) {
        return "menunggu persetujuan dosen";
      } else if (status == 4) {
        return "ditolak Koordinator KBK";
      } else if (status == 5) {
        return "diterima Koordinator KBK";
      } else if (status == 6) {
        return "topik dapat di Revisi";
      } else if (status == 7) {
        return "Masa Sidang";
      } else if (status == 8) {
        return "Topik Tidak Aktif";
      } else {
        return "";
      }
    },
    cekStatusDosen(status_dosen) {
      if (status_dosen == 0) {
        return "ditolak";
      } else if (status_dosen == 1) {
        return "diterima";
      } else if (status_dosen == 2) {
        return "menunggu persetujuan";
      } else if (status_dosen == 3) {
        return "belum meminta persetujuan";
      } else if (status_dosen == 4) {
        return "tidak perlu persetujuan";
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
    onFilteredRevisi(filteredItems) {
      this.currentPageRevisi = 1;
      this.totalRowsRevisi = filteredItems.length;
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
        this.getListTopikPemeriksaanDokumen();
      }
    },
  },
};
</script>

<style scoped>
.keterangan-website {
  margin-left: 0px;
  padding-left: 0px;
}

.pilihan-batch,
.component-filter {
  margin: auto;
}

.form-persetujuan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.table-persetujuan {
  /* padding: 5px 30px 10px 10px; */
  /* margin: 0px 10px 0px 20px; */
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}

.btn-select {
  margin: 10px 0px 10px 0px;
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