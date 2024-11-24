<template>
  <b-container>
    <div class="form-laporan-nilai">
      <!-- // ? Laporan Nilai Sidang Mahasiswa -->
      <div class="history">
        <div class="row">
          <div class="col">
            <h4 class="keterangan-website mt-2">
              Laporan Nilai Sidang Mahasiswa
            </h4>
          </div>
          <!-- // ? Pilih Batch -->
          <div class="col-3 pilihan-batch" v-if="!loading_page">
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
          </b-table>
          <b-row>
            <!-- // ? Pagination -->
            <b-col>
              <b-row>
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
      <!-- // ? ################################################################################ -->
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
      filter_sidang: null,
      // * Pagination
      totalRowsSidang: 1,
      currentPageSidang: 1,
      perPage: 10,
      pageOptions: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
      // * Sorting
      sortBySidang: "topik.id_pengaju",
      sortDescSidang: false,
      // * Table
      fields_sidang: [
        { key: "topik.id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "nilai_pembimbing", label: "Pembimbing", sortable: true },
        { key: "nilai_pembimbing_lapangan", label: "Lapangan", sortable: true },
        { key: "nilai_penguji", label: "Penguji", sortable: true },
        { key: "nilai_koor_kp", label: "KP", sortable: true },
        { key: "nilai_sidang", sortable: true },
        { key: "nilai_akhir", sortable: true },
      ],
      list_sidang: [],
      isBusySidang: true,
      id_pengguna: this.$store.getters.pengguna.UserId,
      id_batch: null,
      pilihan_batch: [],
      id_semester: null,
      semester: null,
      isBusySemester: true,
      loading_page: false,
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
          this.getListMahasiswa();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getListMahasiswa() {
      if (this.$store.getters.listMahasiswa) {
        this.changeDataHasilSidang();
      } else {
        this.$store.dispatch("listMahasiswa").then((response) => {
          this.changeDataHasilSidang();
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
    changeDataHasilSidang() {
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
        element.nilai_sidang = this.hitungNilaiSidang(element);

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
        // ).then(response => {
        element.nama = this.cekNamaMahasiswa(element.topik.id_pengaju);
        // });
        if (index == this.list_sidang.length - 1)
          this.$refs.tableSidang.refresh();
      }
      this.isBusySidang = false;
    },
    hitungNilaiSidang(penilaian) {
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
    // ? Filter
    onFilteredSidang(filteredItems) {
      this.currentPageSidang = 1;
      this.totalRowsSidang = filteredItems.length;
    },
  },
  watch: {
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getHasilSidang();
      }
    },
  },
};
</script>
<style scoped>
.form-laporan-nilai {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

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

.pilihan-batch,
.component-filter {
  margin: auto;
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