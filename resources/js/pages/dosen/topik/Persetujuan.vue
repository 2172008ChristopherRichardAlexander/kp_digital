<template>
  <b-container>
    <!-- // ? Permintaan -->
    <div class="permintaan-persetujuan">
      <div class="row">
        <div class="col">
          <h3 class="mt-2">Persetujuan Topik Dari Dosen</h3>
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
        <!-- <v-simple-table>
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-left">Name</th>
            <th class="text-left">Calories</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in desserts" :key="item.name">
            <td>{{ item.name }}</td>
            <td>{{ item.calories }}</td>
          </tr>
        </tbody>
      </template>
        </v-simple-table>-->
        <!-- <v-data-table
      v-model="selected"
      :headers="headers"
      :items="list_topik_permintaan"
      item-key="id_topik"
      show-select
      class="elevation-1"
        ></v-data-table>-->

        <!-- // * Button Select All, Clear All -->
        <!-- <div class="btn-select">
          <template v-if="selected.length != list_topik_permintaan.length">
            <b-button size="sm" @click="selectAllRows" class="btn-form">Select all</b-button>
          </template>
          <template v-if="Array.isArray(selected) && selected.length">
            <b-button size="sm" @click="clearSelected" class="btn-form">Clear selected</b-button>
          </template>
        </div>-->

        <b-table
          sticky-header
          class="table-persetujuan"
          ref="tablePersetujuan"
          selectable
          :select-mode="selectMode"
          selected-variant="active"
          hover
          striped
          :items="list_topik_permintaan"
          :fields="fields_permintaan"
          @row-selected="onRowSelected"
          responsive="sm"
          :busy="isBusyPermintaan"
          show-empty
          :current-page="currentPage"
          :per-page="perPage"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          :filter="filter"
          @filtered="onFiltered"
        >
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
              <!-- // TODO: Belum di uji coba -->
              <template
                v-if="
                  selected.length < list_topik_permintaan.length &&
                  selected.length > 0
                "
              >
                <span aria-hidden="true" @click="selectAllRows" class="simbol"
                  >&minus;</span
                >
                <span class="sr-only" @click="selectAllRows">Select All</span>
              </template>
              <template v-else-if="Array.isArray(selected) && selected.length">
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
              :to="`/topik/persetujuan/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>

        <b-form-textarea
          id="textarea-no-resize"
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
            <template
              v-if="Array.isArray(selected) && selected.length"
              class="btn-select"
            >
              <div class="btn-select">
                <b-button class="btn-form" @click="terimaTopik(selected)"
                  >Terima</b-button
                >
                <b-button class="btn-form" @click="tolakTopik(selected)"
                  >Tolak</b-button
                >
                <b-button class="btn-form" @click="revisiTopik(selected)"
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
    </div>
    <!-- // ? ##################################### PERMINTAAN ##################################### -->
    <!-- // ? Revisi -->
    <div class="proses-revisi">
      <div class="row">
        <div class="col">
          <h3 class="mt-2">Dalam Proses Revisi</h3>
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
          :items="list_topik_revisi"
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
            <div class="text-center text-danger my-2">
              <ring-loader class="loading-page" color="#20a506" :size="50" />
              <strong class="loading-text">Loading...</strong>
            </div>
          </template>
          <template v-slot:empty>
            <h5 class="text-center">Tidak ada data dalam proses revisi</h5>
          </template>
          <!-- A custom formatted header cell for field 'name' -->
          <template v-slot:head(selected_revisi)>
            <div class="box-select">
              <!-- // TODO: Belum di uji coba -->
              <template
                v-if="
                  selected_revisi.length < list_topik_revisi.length &&
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
          <template v-slot:cell(selected_revisi)="{ rowSelected }">
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
              :to="`/topik/persetujuan/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>

        <b-form-textarea
          id="textarea-no-resize"
          placeholder="Komentar (Opsional)"
          rows="3"
          no-resize
          v-model="text_komentar_revisi"
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
    </div>
    <!-- // ? ##################################### REVISI ##################################### -->
    <!-- // ? History -->
    <div class="history-persetujuan">
      <div class="row">
        <div class="col">
          <h3 class="mt-2">History Persetujuan Topik Dari Dosen</h3>
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
          :items="list_topik_history"
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
          <template v-slot:head(selected_history)>
            <div class="box-select">
              <!-- // TODO: Belum di uji coba -->
              <template
                v-if="
                  selected_history.length < list_topik_history.length &&
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
          <template v-slot:cell(selected_history)="{ rowSelected }">
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
              :to="`/topik/persetujuan/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>

        <b-form-textarea
          id="komentar"
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
    </div>
    <!-- // ? ##################################### HISTORY ##################################### -->
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
      fields_permintaan: [
        { key: "selected", label: "", sortable: false },
        { key: "nrp", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "detail", sortable: false },
      ],
      fields_revisi: [
        { key: "selected_revisi", label: "", sortable: false },
        { key: "id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "detail", sortable: false },
      ],
      fields_history: [
        { key: "selected_history", label: "", sortable: false },
        { key: "id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "status_persetujuan_dosen", label: "Status", sortable: true },
        { key: "detail", sortable: false },
      ],
      list_topik_permintaan: [],
      list_topik_revisi: [],
      list_topik_history: [],
      selectMode: "multi",
      selected: [],
      selected_revisi: [],
      selected_history: [],
      loading: false,
      text_komentar: "",
      text_komentar_revisi: "",
      text_komentar_history: "",
      id_batch: null,
      id_pengguna: this.$store.getters.pengguna.UserId,
      isBusyPermintaan: true,
      isBusyRevisi: true,
      isBusyHistory: true,
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
      //   // this.getListTopikPermintaanPersetujuan();
      // } else {
      //   this.$store.dispatch("batchTopik").then(response => {
      //     this.id_batch = this.$store.getters.batchTopik.id_batch;
      //     // this.getListTopikPermintaanPersetujuan();
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
    onRowSelectedRevisi(items) {
      this.selected_revisi = items;
    },
    selectAllRowsRevisi() {
      this.$refs.tableRevisi.selectAllRows();
    },
    clearSelectedRevisi() {
      this.$refs.tableRevisi.clearSelected();
    },
    // getTopik() {
    //   Axios.get(`${config.apiDosenUrl}/topik?id_pembimbing=` + this.id_pengguna)
    //     .then(response => {
    //       if (response.data.message != "kosong") {
    //         this.list_topik_permintaan = response.data;
    //         this.getListMahasiswa();
    //       }
    //     })
    //     .catch(response => {
    //       console.log(response);
    //     });
    // },
    getListTopikPermintaanPersetujuan() {
      this.isBusyPermintaan = true;
      const formData = new FormData();
      formData.append("id_pembimbing", this.id_pengguna);
      formData.append("id_batch", this.id_batch);
      Axios.post(
        `${config.apiDosenUrl}/topik-permintaan-persetujuan`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      )
        .then((response) => {
          if (response.data.message != "kosong") {
            this.list_topik_permintaan = response.data;
            this.totalRows = response.data.length;
          } else {
            this.list_topik_permintaan = [];
            this.totalRows = 0;
          }
          this.selected = [];
          this.getListTopikProsesRevisi();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getListTopikProsesRevisi() {
      this.isBusyRevisi = true;
      const formData = new FormData();
      formData.append("role_pengguna", "dosen");
      formData.append("status_topik", 6);
      formData.append("status_persetujuan_dosen", 0);
      formData.append("id_batch", this.id_batch);
      formData.append("id_pembimbing", this.id_pengguna);
      Axios.post(`${config.apiUrl}/topik/list-topik`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.list_topik_revisi = response.data.data;
            this.totalRowsRevisi = response.data.data.length;
          } else {
            this.list_topik_revisi = [];
            this.totalRowsRevisi = 0;
          }
          this.selected_revisi = [];
          this.getListTopikHistoryPermintaanPersetujuan();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getListTopikHistoryPermintaanPersetujuan() {
      this.isBusyHistory = true;
      const formData = new FormData();
      formData.append("role_pengguna", "dosen");
      formData.append("status_topik", [0, 1, 2, 4, 5, 7, 8]);
      formData.append("status_persetujuan_dosen", [0, 1]);
      formData.append("id_pembimbing", this.id_pengguna);
      formData.append("id_batch", this.id_batch);
      Axios.post(`${config.apiUrl}/topik/list-topik`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.list_topik_history = response.data.data;
            this.totalRowsHistory = response.data.data.length;
          } else {
            this.list_topik_history = [];
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
      // this.list_topik_permintaan.forEach(element => {
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     element.nrp
      //   // ).then(response => {
      //   element.nama = this.cekNamaMahasiswa(element.nrp);
      //   // });
      //   element.status_persetujuan_dosen = this.cekStatusDosen(
      //     element.status_persetujuan_dosen
      //   );
      // });
      this.isBusyPermintaan = false;
      // this.list_topik_revisi.forEach(element => {
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     element.id_pengaju
      //   // ).then(response => {
      //   Object.assign(element, {
      //     nama: this.cekNamaMahasiswa(element.id_pengaju)
      //   });
      //   this.$refs.tableRevisi.refresh();
      //   // });
      // });
      this.isBusyRevisi = false;
      this.list_topik_history.forEach((element) => {
        //   // Axios.get(
        //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //   //     element.id_pengaju
        //   // ).then(response => {
        //   Object.assign(element, {
        //     nama: this.cekNamaMahasiswa(element.id_pengaju)
        //   });
        // this.$refs.tableHistory.refresh();
        //   // });
        element.status_persetujuan_dosen = this.cekStatusDosen(
          element.status_persetujuan_dosen
        );
      });
      this.isBusyHistory = false;
    },
    terimaTopik(topik) {
      this.loading = true;
      topik.forEach((element) => {
        var status = 2;
        if (
          element.status_topik &&
          element.status_topik == 6 &&
          element.status_persetujuan_dosen == 0
        ) {
          status = 2;
        } else if (element.status_topik && element.status_topik != 3) {
          status = element.status_topik;
        }

        Axios.put(
          `${config.apiDosenUrl}/topik`,
          {
            id_topik: element.id_topik,
            status_persetujuan_dosen: 1,
            status_topik: status,
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
      this.getListTopikPermintaanPersetujuan();
    },
    tolakTopik(topik) {
      this.loading = true;
      topik.forEach((element) => {
        Axios.put(
          `${config.apiDosenUrl}/topik`,
          {
            id_topik: element.id_topik,
            status_persetujuan_dosen: 0,
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
      this.getListTopikPermintaanPersetujuan();
    },
    revisiTopik(topik) {
      this.loading = true;
      topik.forEach((element) => {
        Axios.put(
          `${config.apiDosenUrl}/topik`,
          {
            id_topik: element.id_topik,
            status_persetujuan_dosen: 0,
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
      this.getListTopikPermintaanPersetujuan();
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
        this.getListTopikPermintaanPersetujuan();
      }
    },
  },
};
</script>

<style scoped>
.table-persetujuan {
  /* padding: 5px 30px 10px 10px; */
  /* margin: 0px 10px 0px 20px; */
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
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