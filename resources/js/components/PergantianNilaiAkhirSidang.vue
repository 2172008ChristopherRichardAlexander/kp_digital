<template>
  <div>
    <!-- // ? Permintaan -->
    <div class="permintaan">
      <div class="row">
        <div class="col">
          <h4 class="keterangan-website mt-2">
            Permintaan Perubahan Nilai Akhir Sidang
          </h4>
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
      <div class="form-permintaan">
        <b-table
          sticky-header
          class="table-permintaan"
          ref="tablePermintaan"
          selectable
          :select-mode="selectMode"
          selected-variant="active"
          hover
          striped
          :items="list_permintaan"
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
          <template v-slot:head(selected)>
            <div class="box-select">
              <template
                v-if="
                  selected_permintaan.length < list_permintaan.length &&
                  selected_permintaan.length > 0
                "
              >
                <span aria-hidden="true" @click="selectAllRows" class="simbol"
                  >&minus;</span
                >
                <span class="sr-only" @click="selectAllRows">Select All</span>
              </template>
              <template
                v-else-if="
                  Array.isArray(selected_permintaan) &&
                  selected_permintaan.length
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
          <template v-slot:cell(nrp)="data">{{
            data.item.topik.id_pengaju
          }}</template>
          <template v-slot:cell(nama)="data">{{ data.item.nama }}</template>
          <template v-slot:cell(judul_topik)="data">{{
            data.item.topik.judul_topik
          }}</template>
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
              v-if="
                Array.isArray(selected_permintaan) && selected_permintaan.length
              "
              class="btn-select"
            >
              <div class="btn-select">
                <b-button class="btn-form" @click="konfirmasiPergantianNilai(1)"
                  >Terima</b-button
                >
                <b-button class="btn-form" @click="konfirmasiPergantianNilai(0)"
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
        <!-- // ? Status antara terima, tolak -->
      </div>
    </div>
    <!-- // ? ################################################################################ -->
    <!-- // ? History -->
    <div class="history">
      <div class="row">
        <div class="col">
          <h4 class="keterangan-website mt-2">
            History Perubahan Nilai Akhir Sidang
          </h4>
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
      <div class="form-history">
        <b-table
          sticky-header
          class="table-history"
          ref="tableHistory"
          hover
          striped
          :items="list_history"
          :fields="fields_history"
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
            <div class="text-center text-danger my-2">
              <ring-loader class="loading-page" color="#20a506" :size="50" />
              <strong class="loading-text">Loading...</strong>
            </div>
          </template>
          <template v-slot:empty>
            <h5 class="text-center">Tidak ada data</h5>
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
    <!-- // ? ################################################################################ -->
    <!-- // ? Hasil Sidang Mahasiswa -->
    <div class="history">
      <div class="row">
        <div class="col">
          <h4 class="keterangan-website mt-2">Hasil Sidang Mahasiswa</h4>
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
            label-for="filterInputSidang"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                v-model="filter_sidang"
                type="search"
                id="filterInputSidang"
                placeholder="Ketik untuk mencari"
              ></b-form-input>
              <b-input-group-append>
                <b-button
                  class="btn-form"
                  :disabled="!filter_sidang"
                  @click="filter_sidang = ''"
                  >Hapus</b-button
                >
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </div>
        <!-- // ? ######################################################################## -->
      </div>
      <div class="form-history">
        <b-table
          sticky-header
          class="table-sidang"
          ref="tableSidang"
          hover
          striped
          :items="list_sidang"
          :fields="fields_sidang"
          responsive="sm"
          :busy="isBusySidang"
          show-empty
          :current-page="currentPageSidang"
          :per-page="perPage"
          :sort-by.sync="sortBySidang"
          :sort-desc.sync="sortDescSidang"
          :filter="filter_sidang"
          @filtered="onFilteredSidang"
        >
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <ring-loader class="loading-page" color="#20a506" :size="50" />
              <strong class="loading-text">Loading...</strong>
            </div>
          </template>
          <template v-slot:empty>
            <h5 class="text-center">Tidak ada data</h5>
          </template>
          <template v-slot:cell(nilai_sidang)="data">
            {{
              data.item.nilai_sidang % 1 == 0
                ? data.item.nilai_sidang.toFixed(0)
                : data.item.nilai_sidang.toFixed(2)
            }}
          </template>
          <template v-slot:cell(nilai_akhir)="data">
            {{
              data.item.nilai_akhir % 1 == 0
                ? data.item.nilai_akhir.toFixed(0)
                : data.item.nilai_akhir.toFixed(2)
            }}
          </template>
          <template v-slot:cell(ganti)="data">
            <b-button
              class="btn-form"
              @click="openModalGantiNilaiAkhir(data.item)"
              >Ganti</b-button
            >
          </template>
        </b-table>
        <b-row>
          <!-- // ? Pagination -->
          <b-col>
            <b-pagination
              v-model="currentPageSidang"
              :total-rows="totalRowsSidang"
              :per-page="perPage"
              align="fill"
              size="sm"
              class="mb-0"
            ></b-pagination>
          </b-col>
          <!-- // ? ######################################################################## -->
          <b-col class="text-right">&nbsp;</b-col>
        </b-row>
        <!-- // ? Modal -->
        <b-modal
          id="modal-pergantian"
          ref="modal-pergantian"
          centered
          title="Pergantian Nilai Akhir"
          @ok="onSubmit"
        >
          <b-table-simple>
            <b-tbody>
              <b-tr>
                <b-td>NRP</b-td>
                <b-td>:</b-td>
                <b-td>{{ nrp }}</b-td>
              </b-tr>
              <b-tr>
                <b-td>Nama Mahasiswa</b-td>
                <b-td>:</b-td>
                <b-td>{{ nama }}</b-td>
              </b-tr>
              <b-tr>
                <b-td>Nilai Sekarang</b-td>
                <b-td>:</b-td>
                <b-td>{{
                  nilai % 1 == 0 ? nilai.toFixed(0) : nilai.toFixed(2)
                }}</b-td>
              </b-tr>
              <b-tr>
                <b-td>Nilai Pengganti</b-td>
                <b-td>:</b-td>
                <b-td>
                  <b-form-input
                    type="number"
                    step="1"
                    min="0"
                    max="100"
                    v-model.trim.lazy="ganti"
                    class="input-nilai-number"
                  ></b-form-input>
                </b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <span class="feedback-validasi-false" v-if="pesan">{{ pesan }}</span>
          <template v-slot:modal-footer="{ cancel }">
            <b-button size="sm" class="btn-form" @click="cancel"
              >Batal</b-button
            >
            <b-button
              size="sm"
              type="submit"
              @click="periksaNilaiGanti"
              class="btn-form"
              >Ganti</b-button
            >
          </template>
        </b-modal>
      </div>
    </div>
    <!-- // ? ################################################################################ -->
  </div>
</template>
<script>
import Axios from "axios";
import config from "../config";
import { between } from "vuelidate/lib/validators";
export default {
  data() {
    return {
      // * Filter
      filter: null,
      filter_history: null,
      filter_sidang: null,
      // * Pagination
      totalRows: 1,
      currentPage: 1,
      totalRowsHistory: 1,
      currentPageHistory: 1,
      totalRowsSidang: 1,
      currentPageSidang: 1,
      perPage: 5,
      // * Sorting
      sortBy: "sidang.topik.id_pengaju",
      sortDesc: false,
      sortByHistory: "sidang.topik.id_pengaju",
      sortDescHistory: false,
      sortBySidang: "topik.id_pengaju",
      sortDescSidang: false,
      // * Table
      selectMode: "multi",
      fields_permintaan: [
        { key: "selected", label: "", sortable: false },
        { key: "sidang.topik.id_pengaju", label: "NRP", sortable: true },
        { key: "sidang.nama", label: "Nama", sortable: true },
        {
          key: "sidang.topik.judul_topik",
          label: "Judul Topik",
          sortable: true,
        },
        { key: "nilai_awal", sortable: true },
        { key: "nilai_ubah", sortable: false },
      ],
      fields_history: [
        { key: "sidang.topik.id_pengaju", label: "NRP", sortable: true },
        { key: "sidang.nama", label: "Nama", sortable: true },
        {
          key: "sidang.topik.judul_topik",
          label: "Judul Topik",
          sortable: true,
        },
        { key: "nilai_awal", sortable: true },
        { key: "nilai_ubah", sortable: true },
        {
          key: "status_pembimbing_pergantian_nilai_akhir_sidang",
          label: "Status Pembimbing",
          sortable: true,
        },
        {
          key: "status_penguji_pergantian_nilai_akhir_sidang",
          label: "Status Penguji",
          sortable: true,
        },
        // { key: "status_penguji_dua_pergantian_nilai_akhir_sidang", label: "Status Penguji Dua" ,sortable:true},
        {
          key: "status_koordinator_kp_pergantian_nilai_akhir_sidang",
          label: "Status Koordinator KP",
          sortable: true,
        },
      ],
      fields_sidang: [
        { key: "topik.id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "topik.judul_topik", label: "Judul Topik", sortable: true },
        { key: "nilai_sidang", sortable: true },
        { key: "nilai_akhir", sortable: true },
        { key: "ganti", label: "Ganti", sortable: false },
      ],
      list_permintaan: [],
      list_permintaan_pembimbing: [],
      list_permintaan_penguji: [],
      list_permintaan_penguji_dua: [],
      list_history: [],
      list_history_pembimbing: [],
      list_history_penguji: [],
      list_history_penguji_dua: [],
      list_sidang: [],
      isBusyPermintaan: true,
      isBusyHistory: true,
      isBusySidang: true,
      id_pengguna: this.$store.getters.pengguna.UserId,
      selected_permintaan: [],
      pesan: "",
      nrp: null,
      nama: null,
      nilai: 0,
      ganti: 0,
      id_sidang: null,
      id_penguji_dua: null,
      role: null,
      id_batch: null,
      pilihan_batch: [],
      id_semester: null,
      semester: null,
      isBusySemester: true,
    };
  },
  validations: {
    ganti: {
      between: between(0, 100),
    },
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
      //   // this.getPermintaanPersetujuan();
      // } else {
      //   this.$store.dispatch("batchSidang").then(response => {
      //     this.id_batch = this.$store.getters.batchSidang.id_batch;
      //     // this.getPermintaanPersetujuan();
      //   });
      // }
    },
    getPermintaanPersetujuan() {
      this.isBusyPermintaan = true;
      this.isBusyHistory = true;
      this.isBusySidang = true;
      if (this.$store.getters.jabatan.includes("Koordinator KP")) {
        const formData = new FormData();
        formData.append("jenis_role", "koordinator_kp");
        formData.append("id_pengguna", this.id_pengguna);
        formData.append("id_batch", this.id_batch);
        formData.append("status", [2]);
        formData.append(
          "status_pergantian_nilai_akhir",
          "status_koordinator_kp_pergantian_nilai_akhir_sidang"
        );
        Axios.post(
          `${config.apiUrl}/pergantian-nilai-akhir-sidang/list-role`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
          .then((response) => {
            if (response.data.message != "kosong") {
              this.list_permintaan = response.data.data;
            } else {
              this.list_permintaan = [];
            }
            this.isBusyPermintaan = false;
            this.getListMahasiswa();
          })
          .catch((response) => {
            console.log(response);
          });
      } else {
        this.getPermintaanPersetujuanPembimbing();
      }
    },
    getListMahasiswa() {
      if (this.$store.getters.listMahasiswa) {
        this.changeDataPermintaan();
      } else {
        this.$store.dispatch("listMahasiswa").then((response) => {
          this.changeDataPermintaan();
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
    getPermintaanPersetujuanPembimbing() {
      const formData = new FormData();
      formData.append("jenis_role", "pembimbing");
      formData.append("id_pengguna", this.id_pengguna);
      formData.append("id_batch", this.id_batch);
      formData.append("status", [2]);
      formData.append(
        "status_pergantian_nilai_akhir",
        "status_pembimbing_pergantian_nilai_akhir_sidang"
      );
      Axios.post(
        `${config.apiUrl}/pergantian-nilai-akhir-sidang/list-role`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      )
        .then((response) => {
          if (response.data.message != "kosong") {
            // this.list_permintaan_pembimbing = response.data.data;
            this.list_permintaan.concat(response.data.data);
          } else {
            // this.list_permintaan_pembimbing = [];
          }
          this.getPermintaanPersetujuanPenguji();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getPermintaanPersetujuanPenguji() {
      const formData = new FormData();
      formData.append("jenis_role", "penguji");
      formData.append("id_pengguna", this.id_pengguna);
      formData.append("id_batch", this.id_batch);
      formData.append("status", [2]);
      formData.append(
        "status_pergantian_nilai_akhir",
        "status_penguji_pergantian_nilai_akhir_sidang"
      );
      Axios.post(
        `${config.apiUrl}/pergantian-nilai-akhir-sidang/list-role`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      )
        .then((response) => {
          if (response.data.message != "kosong") {
            // this.list_permintaan_penguji= response.data.data;
            this.list_permintaan.concat(response.data.data);
          } else {
            // this.list_permintaan_penguji= [];
          }
          this.changeDataPermintaan();
          // this.getPermintaanPersetujuanPengujiDua();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    // getPermintaanPersetujuanPengujiDua() {
    //   const formData = new FormData();
    //   formData.append("jenis_role", "penguji_dua");
    //   formData.append("id_pengguna", this.id_pengguna);
    //   formData.append("id_batch", this.id_batch);
    //   formData.append("status", [2]);
    //   formData.append(
    //     "status_pergantian_nilai_akhir",
    //     "status_penguji_dua_pergantian_nilai_akhir_sidang"
    //   );
    //   Axios.post(
    //     `${config.apiUrl}/pergantian-nilai-akhir-sidang/list-role`,
    //     formData,
    //     {
    //       headers: {
    //         "Content-Type": "multipart/form-data"
    //       }
    //     }
    //   )
    //     .then(response => {
    //       if (response.data.message != "kosong") {
    //         // this.list_permintaan_penguji_dua= response.data.data;
    //         this.list_permintaan.concat(response.data.data);
    //       } else {
    //         // this.list_permintaan_penguji_dua= [];
    //       }
    //       this.isBusyPermintaan = false;
    //       this.changeDataPermintaan();
    //     })
    //     .catch(response => {
    //       console.log(response);
    //     });
    // },
    changeDataPermintaan() {
      this.totalRows = this.list_permintaan.length;
      // for (let index = 0; index < this.list_permintaan.length; index++) {
      //   const element = this.list_permintaan[index];
      //   // ! Nama Pengaju
      //   // Axios.get(
      //   // `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +

      //   // ).then(response => {
      //   element.sidang.nama = this.cekNamaMahasiswa(
      //     element.sidang.topik.id_pengaju
      //   );
      //   if (index == this.list_permintaan.length - 1)
      //     this.$refs.tablePermintaan.refresh();
      //   // });
      // }
      this.isBusyPermintaan = false;
      this.getHistoryPermintaanPersetujuan();
    },
    getHistoryPermintaanPersetujuan() {
      const formData = new FormData();
      if (this.$store.getters.jabatan.includes("Koordinator KP")) {
        formData.append("jenis_role", "koordinator_kp");
        formData.append("id_pengguna", this.id_pengguna);
        formData.append("id_batch", this.id_batch);
        formData.append("status", [0, 1]);
        formData.append(
          "status_pergantian_nilai_akhir",
          "status_koordinator_kp_pergantian_nilai_akhir_sidang"
        );
        Axios.post(
          `${config.apiUrl}/pergantian-nilai-akhir-sidang/list-role`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
          .then((response) => {
            if (response.data.message != "kosong") {
              this.list_history = response.data.data;
            } else {
              this.list_history = [];
            }
            this.getMahasiswa();
          })
          .catch((response) => {
            console.log(response);
          });
      } else {
        this.getHistoryPermintaanPersetujuanPembimbing();
      }
    },
    getMahasiswa() {
      if (this.$store.getters.listMahasiswa) {
        this.changeDataHistory();
      } else {
        this.$store.dispatch("listMahasiswa").then((response) => {
          this.changeDataHistory();
        });
      }
    },
    getHistoryPermintaanPersetujuanPembimbing() {
      const formData = new FormData();
      formData.append("jenis_role", "pembimbing");
      formData.append("id_pengguna", this.id_pengguna);
      formData.append("id_batch", this.id_batch);
      formData.append("status", [0, 1]);
      formData.append(
        "status_pergantian_nilai_akhir",
        "status_pembimbing_pergantian_nilai_akhir_sidang"
      );
      Axios.post(
        `${config.apiUrl}/pergantian-nilai-akhir-sidang/list-role`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      )
        .then((response) => {
          if (response.data.message != "kosong") {
            // this.list_history_pembimbing = response.data.data;
            this.list_history.concat(response.data.data);
          } else {
            // this.list_history_pembimbing = [];
          }
          this.getHistoryPermintaanPersetujuanPenguji();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getHistoryPermintaanPersetujuanPenguji() {
      const formData = new FormData();
      formData.append("jenis_role", "penguji");
      formData.append("id_pengguna", this.id_pengguna);
      formData.append("id_batch", this.id_batch);
      formData.append("status", [0, 1]);
      formData.append(
        "status_pergantian_nilai_akhir",
        "status_penguji_pergantian_nilai_akhir_sidang"
      );
      Axios.post(
        `${config.apiUrl}/pergantian-nilai-akhir-sidang/list-role`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      )
        .then((response) => {
          if (response.data.message != "kosong") {
            // this.list_history_penguji= response.data.data;
            this.list_history.concat(response.data.data);
          } else {
            // this.list_history_penguji= [];
          }
          this.isBusyHistory = false;
          this.getMahasiswa();
          // this.getHistoryPermintaanPersetujuanPengujiDua();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    // getHistoryPermintaanPersetujuanPengujiDua() {
    //   const formData = new FormData();
    //   formData.append("jenis_role", "penguji_dua");
    //   formData.append("id_pengguna", this.id_pengguna);
    //   formData.append("id_batch", this.id_batch);
    //   formData.append("status", [0,1]);
    //   formData.append(
    //     "status_pergantian_nilai_akhir",
    //     "status_penguji_dua_pergantian_nilai_akhir_sidang"
    //   );
    //   Axios.post(
    //     `${config.apiUrl}/pergantian-nilai-akhir-sidang/list-role`,
    //     formData,
    //     {
    //       headers: {
    //         "Content-Type": "multipart/form-data"
    //       }
    //     }
    //   )
    //     .then(response => {
    //       if (response.data.message != "kosong") {
    //         // this.list_history_penguji_dua= response.data.data;
    //         this.list_history.concat(response.data.data);
    //       } else {
    //         // this.list_history_penguji_dua= [];
    //       }
    //       this.isBusyHistory= false;
    //       this.changeDataHistory();
    //     })
    //     .catch(response => {
    //       console.log(response);
    //     });
    // },
    changeDataHistory() {
      this.totalRowsHistory = this.list_history.length;
      for (let index = 0; index < this.list_history.length; index++) {
        const element = this.list_history[index];
        // ! Nama Pengaju
        // Axios.get(
        //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        // element.sidang.topik.id_pengaju
        // ).then(response => {
        // element.sidang.nama = this.cekNamaMahasiswa(
        //   element.sidang.topik.id_pengaju
        // );
        // });

        element.status_pembimbing_pergantian_nilai_akhir_sidang =
          this.cekStatusPembimbing(
            element.status_pembimbing_pergantian_nilai_akhir_sidang
          );

        element.status_penguji_pergantian_nilai_akhir_sidang =
          this.cekStatusPenguji(
            element.status_penguji_pergantian_nilai_akhir_sidang
          );
        element.status_penguji_dua_pergantian_nilai_akhir_sidang =
          this.cekStatusPenguji(
            element.status_penguji_dua_pergantian_nilai_akhir_sidang
          );

        element.status_koordinator_kp_pergantian_nilai_akhir_sidang =
          this.cekStatusKoorKP(
            element.status_koordinator_kp_pergantian_nilai_akhir_sidang
          );
        if (index == this.list_history.length - 1)
          this.$refs.tableHistory.refresh();
      }
      this.isBusyHistory = false;
      this.getHasilSidang();
    },
    cekStatusPembimbing(index) {
      var status = ["ditolak", "diterima", "menunggu dikonfirmasi"];
      if (index >= 0 && index <= status.length) {
        return status[index];
      } else {
        return "";
      }
    },
    cekStatusPenguji(index) {
      var status = [
        "ditolak",
        "diterima",
        "menunggu dikonfirmasi",
        "tidak membutuhkan konfirmasi",
      ];
      if (index >= 0 && index <= status.length) {
        return status[index];
      } else {
        return "";
      }
    },
    cekStatusKoorKP(index) {
      var status = ["ditolak", "diterima", "menunggu dikonfirmasi"];
      if (index >= 0 && index <= status.length) {
        return status[index];
      } else {
        return "";
      }
    },
    getHasilSidang() {
      const formData = new FormData();
      formData.append("id_batch", this.id_batch);
      formData.append("status_sidang", [0, 1]);
      if (this.jenis_role == "dosen")
        formData.append("id_pengguna", this.id_pengguna);
      Axios.post(`${config.apiUrl}/sidang/hasil-sidang`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.list_sidang = response.data.data;
            this.totalRowsSidang = response.data.data.length;
          } else {
            this.list_sidang = [];
            this.totalRowsSidang = 0;
          }
          this.changeDataHasilSidang();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    async changeDataHasilSidang() {
      var nilai_sidang = { nilai_sidang: 0 };
      var nilai_akhir = { nilai_akhir: 0 };
      for (let index = 0; index < this.list_sidang.length; index++) {
        const element = this.list_sidang[index];
        Object.assign(element, nilai_sidang);
        Object.assign(element, nilai_akhir);
        if (!element.nilai_pembimbing_lapangan) {
          element.nilai_pembimbing_lapangan = element.nilai_pembimbing;
        }
        // ! Nilai Sidang
        element.nilai_sidang = await this.hitungNilaiSidang(element);

        // ! Nilai Akhir Sidang
        Axios.post(
          `${config.apiUrl}/pergantian-nilai-akhir-sidang/sidang-disetujui`,
          {
            id_sidang: element.id_sidang,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then((response) => {
            if (response.data.message != "kosong") {
              element.nilai_akhir = response.data.data.nilai_ubah;
            } else {
              element.nilai_akhir = element.nilai_sidang;
            }
            this.$refs.tableSidang.refresh();
          })
          .catch((response) => {
            console.log(response);
          });
        // ! Nama Pengaju
        // Axios.get(
        //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //     element.topik.id_pengaju
        // ).then((response) => {
        //   element.nama = response.data[0].Name;
        // });
        if (index == this.list_sidang.length - 1)
          this.$refs.tableSidang.refresh();
      }
      this.isBusySidang = false;
    },
    async hitungNilaiSidang(penilaian) {
      var nilai = 0;
      if (penilaian.nilai_pembimbing) {
        nilai =
          nilai +
          (penilaian.komponen_nilai.pembimbing / 100) *
            penilaian.nilai_pembimbing;
      }
      if (penilaian.nilai_pembimbing_lapangan) {
        nilai =
          nilai +
          (penilaian.komponen_nilai.pembimbing_lapangan / 100) *
            penilaian.nilai_pembimbing_lapangan;
      }
      if (penilaian.nilai_koor_kp) {
        nilai =
          nilai +
          (penilaian.komponen_nilai.koordinator_kp / 100) *
            penilaian.nilai_koor_kp;
      }

      if (penilaian.nilai_penguji) {
        if (penilaian.id_penguji_sidang_dua) {
          nilai =
            nilai +
            ((penilaian.komponen_nilai.penguji / 100) *
              (penilaian.nilai_penguji_dua + penilaian.nilai_penguji)) /
              2;
        } else {
          nilai =
            nilai +
            (penilaian.komponen_nilai.penguji / 100) * penilaian.nilai_penguji;
        }
      }
      return nilai;
    },
    openModalGantiNilaiAkhir(data) {
      this.nrp = data.topik.id_pengaju;
      this.nama = data.nama;
      this.nilai = data.nilai_akhir;
      this.id_sidang = data.id_sidang;
      this.id_penguji_dua = data.id_penguji_sidang_dua;
      if (data.topik.id_pembimbing == this.id_pengguna) {
        this.role = "pembimbing";
      } else if (data.id_penguji_sidang) {
        this.role = "penguji";
      } else if (data.id_penguji_sidang_dua == this.id_pengguna) {
        this.role = "penguji_dua";
      }
      this.$refs["modal-pergantian"].show();
    },
    periksaNilaiGanti() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.submit_status = "GAGAL";
        this.pesan = "* Nilai harus diantara 0-100";
      } else {
        this.submit_status = "BERHASIL";
        this.pesan = "";
        this.onSubmit();
      }
    },
    konfirmasiPergantianNilai(status) {
      // TODO : Cek Masing masing data terpilih, status role nya
      this.selected_permintaan.forEach((element) => {
        if (element.sidang.topik.id_pembimbing == this.id_pengguna) {
          this.konfirmasi(
            "status_pembimbing_pergantian_nilai_akhir_sidang",
            status,
            element.id_pergantian_nilai_akhir_sidang
          );
        } else if (element.sidang.id_penguji_sidang) {
          this.konfirmasi(
            "status_penguji_pergantian_nilai_akhir_sidang",
            status,
            element.id_pergantian_nilai_akhir_sidang
          );
        } else if (element.sidang.id_penguji_sidang_dua == this.id_pengguna) {
          this.konfirmasi(
            "status_penguji_dua_pergantian_nilai_akhir_sidang",
            status,
            element.id_pergantian_nilai_akhir_sidang
          );
        }
        if (this.$store.getters.jabatan.includes("Koordinator KP")) {
          this.konfirmasiKoorKP(
            status,
            element.id_pergantian_nilai_akhir_sidang
          );
        }
      });
      this.getPermintaanPersetujuan();
    },
    konfirmasi(jenis_status, status, id_pergantian) {
      // TODO: Membuat fungsi untuk melakukan konfirmasi ke data base
      const formData = new FormData();
      formData.append("id_pergantian_nilai_akhir_sidang", id_pergantian);
      formData.append("jenis_status", jenis_status);
      formData.append("status", status);
      Axios.post(
        `${config.apiUrl}/pergantian-nilai-akhir-sidang/update-status`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      )
        .then((response) => {
          console.log(response);
        })
        .catch((response) => {
          console.log(response);
        });
    },
    konfirmasiKoorKP(status, id_pergantian) {
      // TODO: Membuat fungsi untuk melakukan konfirmasi ke data base
      const formData = new FormData();
      formData.append("id_pergantian_nilai_akhir_sidang", id_pergantian);
      formData.append(
        "jenis_status",
        "status_koordinator_kp_pergantian_nilai_akhir_sidang"
      );
      formData.append("status", status);
      formData.append("id_koordinator_kp", this.id_pengguna);

      Axios.post(
        `${config.apiUrl}/pergantian-nilai-akhir-sidang/update-status-koor-kp`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      )
        .then((response) => {
          console.log(response);
        })
        .catch((response) => {
          console.log(response);
        });
    },
    onSubmit() {
      if (this.submit_status == "BERHASIL") {
        const formData = new FormData();
        formData.append("nilai_awal", this.nilai);
        formData.append("nilai_ubah", this.ganti);
        if (this.role == "pembimbing") {
          formData.append("status_pembimbing_pergantian_nilai_akhir_sidang", 1);
        } else {
          formData.append("status_pembimbing_pergantian_nilai_akhir_sidang", 2);
        }
        if (this.role == "penguji") {
          formData.append("status_penguji_pergantian_nilai_akhir_sidang", 1);
        } else {
          formData.append("status_penguji_pergantian_nilai_akhir_sidang", 2);
        }
        if (this.id_penguji_dua) {
          if (this.role == "penguji_dua") {
            formData.append(
              "status_penguji_dua_pergantian_nilai_akhir_sidang",
              1
            );
          } else {
            formData.append(
              "status_penguji_dua_pergantian_nilai_akhir_sidang",
              2
            );
          }
        } else {
          formData.append(
            "status_penguji_dua_pergantian_nilai_akhir_sidang",
            3
          );
        }
        if (this.$store.getters.jabatan.includes("Koordinator KP")) {
          formData.append(
            "status_koordinator_kp_pergantian_nilai_akhir_sidang",
            1
          );
          formData.append("id_koordinator_kp", this.id_pengguna);
        } else {
          formData.append(
            "status_koordinator_kp_pergantian_nilai_akhir_sidang",
            2
          );
          formData.append("id_koordinator_kp", null);
        }

        formData.append("id_sidang", this.id_sidang);
        Axios.post(`${config.apiUrl}/pergantian-nilai-akhir-sidang`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
          .then((response) => {
            this.$refs["modal-pergantian"].hide();
            this.ganti = 0;
            this.getPermintaanPersetujuan();
          })
          .catch((response) => {
            console.log(response);
          });
      }
    },
    onRowSelected(items) {
      this.selected_permintaan = items;
    },
    selectAllRows() {
      this.$refs.tablePermintaan.selectAllRows();
    },
    clearSelected() {
      this.$refs.tablePermintaan.clearSelected();
    },
    // onRowSelectedHistory(items) {
    //   this.selected_history = items;
    // },
    // selectAllRowsHistory() {
    //   this.$refs.tabelHistory.selectAllRows();
    // },
    // clearSelectedHistory() {
    //   this.$refs.tabelHistory.clearSelected();
    // },
    // ? Filter
    onFiltered(filteredItems) {
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
    onFilteredHistory(filteredItems) {
      this.currentPageHistory = 1;
      this.totalRowsHistory = filteredItems.length;
    },
    onFilteredSidang(filteredItems) {
      this.currentPageSidang = 1;
      this.totalRowsSidang = filteredItems.length;
    },
  },
  watch: {
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getPermintaanPersetujuan();
      }
    },
  },
  props: ["jenis_role"],
};
</script>
<style scoped>
/* .form-persetujuan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
} */
/* // * CSS Table */
.table-permintaan,
.table-history,
.table-sidang {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
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

/* // ! CSS Validasi Form */

.feedback-validasi-false {
  color: red;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>