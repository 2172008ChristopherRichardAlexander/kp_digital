<template>
  <b-container>
    <div>
      <!-- // ? Kelayakan -->
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Permeriksaan Kelayakan Topik</h3>
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
          <template v-if="selected_kelayakan.length != topik.length">
            <b-button size="sm" @click="selectAllRows" class="btn-form">Select all</b-button>
          </template>
          <template v-if="Array.isArray(selected) && selected_kelayakan.length">
            <b-button size="sm" @click="clearSelected" class="btn-form">Clear selected</b-button>
          </template>
        </div>-->
        <b-table
          sticky-header
          class="table-persetujuan"
          ref="tableKelayakan"
          selectable
          :select-mode="selectMode"
          selected-variant="active"
          hover
          striped
          :items="topik_kelayakan"
          :fields="fields_kelayakan"
          @row-selected="onRowSelected"
          responsive="sm"
          :busy="isBusyKelayakan"
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
              Tidak ada data yang perlu dilakukan pemeriksaan kelayakan
            </h5>
          </template>
          <!-- A custom formatted header cell for field 'name' -->
          <template v-slot:head(selected)>
            <div class="box-select">
              <!-- // TODO: Belum di uji coba -->
              <template
                v-if="
                  selected_kelayakan.length < topik_kelayakan.length &&
                  selected_kelayakan.length > 0
                "
              >
                <span aria-hidden="true" @click="selectAllRows" class="simbol"
                  >&minus;</span
                >
                <span class="sr-only" @click="selectAllRows">Select All</span>
              </template>
              <template
                v-else-if="
                  Array.isArray(selected_kelayakan) && selected_kelayakan.length
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
              :to="`/topik/kelayakan-topik/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>

        <!-- // * Form Komentar -->
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
            <!-- // * Button Terima Tolak Revisi -->
            <template
              v-if="
                Array.isArray(selected_kelayakan) && selected_kelayakan.length
              "
              class="btn-select"
            >
              <div class="btn-select">
                <b-button
                  class="btn-form"
                  @click="terimaTopik(selected_kelayakan)"
                  >Terima</b-button
                >
                <b-button
                  class="btn-form"
                  @click="tolakTopik(selected_kelayakan)"
                  >Tolak</b-button
                >
                <b-button
                  class="btn-form"
                  @click="revisiTopik(selected_kelayakan)"
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
      <!-- // ? ##################################### KELAYAKAN ##################################### -->
      <!-- // ? Revisi -->
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Dalam Proses Revisi</h3>
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
              :to="`/topik/kelayakan-topik/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>

        <!-- // * Form Komentar -->
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
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">History Permeriksaan Kelayakan Topik</h3>
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
              :to="`/topik/kelayakan-topik/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>

        <!-- // * Form Komentar -->
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
      sortByRevisi: "id_pengaju",
      sortDescRevisi: false,
      // * Table
      fields_kelayakan: [
        { key: "selected", label: "", sortable: false },
        { key: "nrp", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "detail", sortable: false },
      ],
      fields_revisi: [
        { key: "selected", label: "", sortable: false },
        { key: "id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "detail", sortable: false },
      ],
      fields_history: [
        { key: "selected", label: "", sortable: false },
        { key: "id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "status_topik", label: "Status Topik", sortable: true },
        { key: "detail", sortable: false },
      ],
      topik_kelayakan: [],
      topik_revisi: [],
      topik_history: [],
      selectMode: "multi",
      selected_kelayakan: [],
      selected_revisi: [],
      selected_history: [],
      loading: false,
      text_komentar: "",
      loading: false,
      id_pengguna: this.$store.getters.pengguna.UserId,
      isBusyKelayakan: true,
      isBusyRevisi: true,
      isBusyHistory: true,
      id_batch: null,
      id_kbk: null,
      data_layak: 0,
      data_revisi: 0,
      data_history: 0,
      arr_kbk: [],
      ulang: 0,
      batas: 0,
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
    getKbkJabatan() {
      this.batas = 0;
      this.ulang = 0;
      this.arr_kbk = [];
      var nama_kbk = "";
      var jabatan = this.$store.getters.jabatan;
      for (let index = 0; index < jabatan.length; index++) {
        const element = jabatan[index];
        if (element.includes("Ketua KBK")) {
          nama_kbk = element.replace("Ketua KBK ", "");
          if (!this.arr_kbk.includes(nama_kbk)) {
            this.arr_kbk.push(nama_kbk);
          }
        } else if (element.includes("Sekretaris KBK")) {
          nama_kbk = element.replace("Sekretaris KBK ", "");
          if (!this.arr_kbk.includes(nama_kbk)) {
            this.arr_kbk.push(nama_kbk);
          }
        }
        if (index == jabatan.length - 1) {
          if (this.arr_kbk.length > 0) {
            this.getDataKbk(this.arr_kbk);
          }
        }
      }
    },
    getDataKbk(arr_kbk) {
      this.isBusyKelayakan = this.isBusyHistory = this.isBusyRevisi = true;
      this.data_layak = this.data_revisi = this.data_history = this.ulang = 0;
      this.topik_kelayakan = [];
      this.topik_revisi = [];
      this.topik_history = [];
      this.batas = arr_kbk.length * 3;
      for (let ikbk = 0; ikbk < arr_kbk.length; ikbk++) {
        const elmt = arr_kbk[ikbk];
        this.getIdKbk(elmt);
      }
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
          this.getListKelayakanTopik(response.data.data[0].id_kbk);
        })
        .catch((response) => {
          console.log(response);
        });
    },
    onRowSelected(items) {
      this.selected_kelayakan = items;
    },
    selectAllRows() {
      this.$refs.tableKelayakan.selectAllRows();
    },
    clearSelected() {
      this.$refs.tableKelayakan.clearSelected();
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
    getListKelayakanTopik(id_kbk) {
      this.isBusyKelayakan = true;
      const formData = new FormData();
      formData.append("id_batch", this.id_batch);
      formData.append("id_kbk", id_kbk);

      Axios.post(`${config.apiKbkUrl}/topik-kelayakan`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.topik_kelayakan = this.topik_kelayakan.concat(response.data);
            this.totalRows = this.topik_kelayakan.length;
          } else {
            this.totalRows =
              this.topik_kelayakan.length > 0 ? this.topik_kelayakan.length : 0;
          }
          this.selected_kelayakan = [];
          this.ulang += 1;
          this.getProsesRevisiKelayakanTopik(id_kbk);
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getProsesRevisiKelayakanTopik(id_kbk) {
      this.isBusyRevisi = true;
      const formData = new FormData();
      formData.append("role_pengguna", "koorkbk");
      formData.append("id_batch", this.id_batch);
      formData.append("id_kbk", id_kbk);

      formData.append("status_topik", [6]);
      formData.append("status_persetujuan_dosen", [0, 1, 2, 4]);
      Axios.post(`${config.apiUrl}/topik/list-topik`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.topik_revisi = this.topik_revisi.concat(response.data.data);
            this.totalRowsRevisi = this.topik_revisi.length;
          } else {
            this.totalRowsRevisi =
              this.topik_revisi.length > 0 ? this.topik_revisi.length : 0;
          }
          this.selected_revisi = [];
          this.ulang += 1;
          this.getListHistoryKelayakanTopik(id_kbk);
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getListHistoryKelayakanTopik(id_kbk) {
      this.isBusyHistory = true;
      const formData = new FormData();
      formData.append("role_pengguna", "koorkbk");
      formData.append("id_batch", this.id_batch);

      formData.append("id_kbk", id_kbk);
      formData.append("status_topik", [4, 5]);
      formData.append("status_persetujuan_dosen", [0, 1, 2, 4]);
      Axios.post(`${config.apiUrl}/topik/list-topik`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.topik_history = this.topik_history.concat(response.data.data);
            this.totalRowsHistory = this.topik_history.length;
          } else {
            this.totalRowsHistory =
              this.topik_history.length > 0 ? this.topik_history.length : 0;
          }
          this.selected_history = [];
          this.ulang += 1;
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
      // * Data Topik Kelayakan
      // for (
      //   let ilayak = this.data_layak;
      //   ilayak < this.topik_kelayakan.length;
      //   ilayak++
      // ) {
      //   const elayak = this.topik_kelayakan[ilayak];
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` + elayak.nrp
      //   // ).then(response => {
      //   elayak.nama = this.cekNamaMahasiswa(elayak.nrp);
      //   // });
      //   if (ilayak == this.topik_kelayakan.length - 1) {
      //     this.data_layak += this.topik_kelayakan.length;
      //     this.$refs.tableKelayakan.refresh();
      //   }
      // }
      this.isBusyKelayakan = false;
      // * Data Topik Revisi
      // for (
      //   let irevisi = this.data_revisi;
      //   irevisi < this.topik_revisi.length;
      //   irevisi++
      // ) {
      //   const erevisi = this.topik_revisi[irevisi];
      //   // Axios.get(
      //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //   //     erevisi.id_pengaju
      //   // ).then(response => {
      //   Object.assign(erevisi, {
      //     nama: this.cekNamaMahasiswa(erevisi.id_pengaju),
      //   });
      //   this.$refs.tableRevisi.refresh();
      //   // });
      //   if (irevisi == this.topik_revisi.length - 1) {
      //     this.data_revisi += this.topik_revisi.length;
      //     this.$refs.tableRevisi.refresh();
      //   }
      // }
      this.isBusyRevisi = false;
      // * Data Topik History
      for (
        let ihistory = this.data_history;
        ihistory < this.topik_history.length;
        ihistory++
      ) {
        const ehistory = this.topik_history[ihistory];

        //   // Axios.get(
        //   //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //   //     ehistory.id_pengaju
        //   // ).then(response => {
        //   Object.assign(ehistory, {
        //     nama: this.cekNamaMahasiswa(ehistory.id_pengaju),
        //   });
        //   this.$refs.tableHistory.refresh();
        //   // });
        ehistory.status_topik = this.cekStatusTopik(ehistory.status_topik);
        if (ihistory == this.topik_history.length - 1) {
          this.data_history += this.topik_history.length;
          this.$refs.tableHistory.refresh();
        }
      }
      this.isBusyHistory = false;
    },
    terimaTopik(topik) {
      this.loading = true;
      for (let index = 0; index < topik.length; index++) {
        const element = topik[index];
        Axios.put(
          `${config.apiKbkUrl}/topik`,
          {
            id_topik: element.id_topik,
            status_topik: 5,
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
        if (index == topik.length - 1) {
          this.getDataKbk(this.arr_kbk);
        }
      }
    },
    tolakTopik(topik) {
      this.loading = true;
      for (let index = 0; index < topik.length; index++) {
        const element = topik[index];
        Axios.put(
          `${config.apiKbkUrl}/topik`,
          {
            id_topik: element.id_topik,
            status_topik: 4,
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
        if (index == topik.length - 1) {
          this.getDataKbk(this.arr_kbk);
        }
      }
    },
    revisiTopik(topik) {
      this.loading = true;
      for (let index = 0; index < topik.length; index++) {
        const element = topik[index];
        Axios.put(
          `${config.apiKbkUrl}/topik`,
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
        if (index == topik.length - 1) {
          this.getDataKbk(this.arr_kbk);
        }
      }
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
      // this.$router.push("/topik/kelayakan-topik");
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
  watch: {
    ulang: function (val) {
      if (val >= this.batas) {
        this.getListMahasiswa();
      }
    },
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getKbkJabatan();
      }
    },
  },
};
</script>
<style scoped>
.form-persetujuan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
/* // * CSS Table */
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

/* // * CSS Button */
.btn-select {
  margin: 10px 0px 10px 0px;
}

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