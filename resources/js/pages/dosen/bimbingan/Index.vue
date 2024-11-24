<template>
  <b-container>
    <!-- // ? Mahasiswa Bimbingan -->
    <div class="form-konfirmasi-bimbingan">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Daftar Mahasiswa Bimbingan</h3>
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
            label-for="filterInputMahasiswa"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                v-model="filter_mahasiswa"
                type="search"
                id="filterInputMahasiswa"
                placeholder="Ketik untuk mencari"
              ></b-form-input>
              <b-input-group-append>
                <b-button
                  class="btn-form"
                  :disabled="!filter_mahasiswa"
                  @click="filter_mahasiswa = ''"
                  >Hapus</b-button
                >
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </div>
        <!-- // ? ######################################################################## -->
      </div>
      <div class="konfirmasi-bimbingan">
        <b-table
          sticky-header
          class="table-konfirmasi-bimbingan"
          ref="tabelHistory"
          hover
          striped
          :items="daftar_mahasiswa"
          :fields="fields_mahasiswa"
          responsive="sm"
          :busy="isBusyMahasiswa"
          show-empty
          :current-page="currentPageMahasiswa"
          :per-page="perPage"
          :sort-by.sync="sortByMahasiswa"
          :sort-desc.sync="sortDescMahasiswa"
          :filter="filter_mahasiswa"
          @filtered="onFilteredMahasiswa"
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
          <template v-slot:cell(bimbingan)="data">
            <router-link
              :to="`/bimbingan/Detail/${data.item.slug_topik}`"
              class="btn btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>
        <b-row>
          <!-- // ? Pagination -->
          <b-col>
            <b-pagination
              v-model="currentPageMahasiswa"
              :total-rows="totalRowsMahasiswa"
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
    <!-- // ? Konfirmasi -->
    <div class="form-konfirmasi-bimbingan">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Permintaan Konfirmasi Bimbingan</h3>
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
              :key="pilihan.id_batch + 'b'"
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
      <div class="konfirmasi-bimbingan">
        <b-table
          sticky-header
          class="table-konfirmasi-bimbingan"
          ref="tabelKonfirmasi"
          selectable
          :select-mode="selectMode"
          selected-variant="active"
          hover
          striped
          :items="bimbingan_konfirmasi"
          :fields="fields_konfirmasi"
          @row-selected="onRowSelected"
          responsive="sm"
          :busy="isBusyKonfirmasi"
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
            <h5 class="text-center">Tidak ada data yang perlu dikonfirmasi</h5>
          </template>
          <!-- A custom formatted header cell for field 'name' -->
          <template v-slot:head(selected)>
            <div class="box-select">
              <template
                v-if="
                  selected_konfirmasi.length < bimbingan_konfirmasi.length &&
                  selected_konfirmasi.length > 0
                "
              >
                <span aria-hidden="true" @click="selectAllRows" class="simbol"
                  >&minus;</span
                >
                <span class="sr-only" @click="selectAllRows">Select All</span>
              </template>
              <template
                v-else-if="
                  Array.isArray(selected_konfirmasi) &&
                  selected_konfirmasi.length
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

          <template v-slot:cell(nama_mahasiswa)="data">{{
            data.item.id_topik.nama
          }}</template>
          <template v-slot:cell(judul_topik)="data">{{
            data.item.id_topik.judul_topik
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
            <!-- Button Selected Konfirmasi -->
            <template class="btn-select">
              <b-button
                class="btn-form"
                id="show-btn"
                :disabled="
                  (Array.isArray(selected_konfirmasi) &&
                    !selected_konfirmasi.length) ||
                  loading
                "
                @click="konfirmasiListBimbingan(selected_konfirmasi)"
              >
                {{ loading ? "" : "Konfirmasi" }}
                <ring-loader
                  class="loading-page"
                  color="white"
                  :size="25"
                  v-if="loading"
                />
              </b-button>
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
      pilihan_batch: [],
      // * Filter
      filter: null,
      filter_mahasiswa: null,
      // * Pagination
      totalRows: 1,
      totalRowsMahasiswa: 1,
      currentPage: 1,
      currentPageMahasiswa: 1,
      perPage: 10,
      // * Sorting
      sortBy: "tanggal_bimbingan",
      sortDesc: false,
      sortByMahasiswa: "id_pengaju",
      sortDescMahasiswa: false,
      // * Table
      id_pembimbing_bimbingan: this.$store.getters.pengguna.UserId,
      fields_konfirmasi: [
        { key: "selected", label: "", sortable: false },
        { key: "tanggal_bimbingan", label: "Tanggal", sortable: true },
        { key: "nama_mahasiswa", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "deskripsi_bimbingan", label: "Deskripsi", sortable: true },
      ],
      fields_mahasiswa: [
        { key: "nrp", label: "NRP", sortable: true },
        { key: "nama", label: "Nama Mahasiswa", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "bimbingan", sortable: true },
      ],
      bimbingan_konfirmasi: [],
      daftar_mahasiswa: [],
      selectMode: "multi",
      selected_konfirmasi: [],
      loading: false,
      show: true,
      isBusyKonfirmasi: true,
      isBusyMahasiswa: true,
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
    onRowSelected(items) {
      this.selected_konfirmasi = items;
    },
    selectAllRows() {
      this.$refs.tabelKonfirmasi.selectAllRows();
    },
    clearSelected() {
      this.$refs.tabelKonfirmasi.clearSelected();
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
      // if (this.$store.getters.batchTopik) {
      //   this.id_batch = this.$store.getters.batchTopik.id_batch;
      //   // this.getBimbinganKonfirmasi();
      // } else {
      //   this.$store.dispatch("batchTopik").then(response => {
      //     this.id_batch = this.$store.getters.batchTopik.id_batch;
      //     // this.getBimbinganKonfirmasi();
      //   });
      // }
    },
    getBimbinganKonfirmasi() {
      this.isBusyKonfirmasi = true;
      this.loading = true;
      Axios.post(
        `${config.apiDosenUrl}/bimbingan`,
        {
          id_batch: this.id_batch,
          id_pembimbing_bimbingan: this.id_pembimbing_bimbingan,
          status_bimbingan: 2,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.bimbingan_konfirmasi = response.data.data;
          this.totalRows = response.data.data.length;
          this.getDaftarMahasiswa();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getDaftarMahasiswa() {
      this.isBusyMahasiswa = true;
      Axios.post(
        `${config.apiDosenUrl}/topik/mahasiswa-bimbingan`,
        {
          id_batch: this.id_batch,
          id_pembimbing: this.id_pembimbing_bimbingan,
          role_pembimbing: "id_pembimbing",
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          if (response.data.message != "kosong") {
            this.daftar_mahasiswa = response.data;
            this.totalRowsMahasiswa = response.data.length;
          } else {
            this.daftar_mahasiswa = [];
            this.totalRowsMahasiswa = 0;
          }
          this.getListMahasiswa();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getListMahasiswa() {
      if (this.$store.getters.listMahasiswa) {
        this.changeDataBimbingan();
      } else {
        this.$store.dispatch("listMahasiswa").then((response) => {
          this.changeDataBimbingan();
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
    changeDataBimbingan() {
      for (let index = 0; index < this.bimbingan_konfirmasi.length; index++) {
        const element = this.bimbingan_konfirmasi[index];
        // Axios.get(
        //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //     element.id_topik.id_pengaju
        // ).then(response => {
        // element.id_topik.id_pengaju = this.cekNamaMahasiswa(
        //   element.id_topik.id_pengaju
        // );
        element.status_bimbingan = this.cekStatusBimbingan(
          element.status_bimbingan
        );
        // });
        if (index == this.bimbingan_konfirmasi.length - 1) {
          this.$refs.tabelHistory.refresh();
        }
      }
      this.isBusyKonfirmasi = false;
      this.loading = false;
      // for (let i = 0; i < this.daftar_mahasiswa.length; i++) {
      // const e = this.daftar_mahasiswa[i];
      // Axios.get(
      //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` + e.nrp
      // ).then(response => {
      // e.nama = this.cekNamaMahasiswa(e.nrp);
      // });
      // if (i == this.daftar_mahasiswa.length - 1) {
      this.$refs.tabelKonfirmasi.refresh();
      // }
      // }
      this.isBusyMahasiswa = false;
    },
    konfirmasiBimbingan(bimbingan) {
      this.loading = true;
      bimbingan.forEach((element) => {
        Axios.put(
          `${config.apiDosenUrl}/bimbingan`,
          {
            id_bimbingan: element.id_bimbingan,
            status_bimbingan: 1,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          // .then(response => {
          //   this.loading = false;
          // })
          .catch((response) => {
            console.log(response);
          });
      });
      this.loading = false;
      this.getBimbinganKonfirmasi();
    },
    konfirmasiListBimbingan(bimbingan) {
      let list_bimbingan = bimbingan.map((e) => e.id_bimbingan);
      this.loading = true;
      const params = {
        list_bimbingan: list_bimbingan,
        status_bimbingan: 1,
      };
      Axios.put(`${config.apiDosenUrl}/bimbingan/list`, params, {
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((res) => {
          console.log("response konfirmasi", res);
          this.getBimbinganKonfirmasi();
          this.makeToast("Success", "Konfirmasi Bimbingan Berhasil");
          this.loading = false;
        })
        .catch((err) => {
          console.log("error konfirmasi", err);
          this.makeToast("Gagal", "Konfirmasi Bimbingan Gagal", "danger");
          this.loading = false;
        });
    },
    cekStatusBimbingan(status_bimbingan) {
      if (status_bimbingan == 0) {
        return "ditolak";
      } else if (status_bimbingan == 1) {
        return "diterima";
      } else if (status_bimbingan == 2) {
        return "menunggu konfirmasi";
      } else {
        return "";
      }
    },
    makeToast(title = "", message = "", variant = "success", append = false) {
      this.$bvToast.toast(`${message}`, {
        title: `${title}`,
        variant: variant,
        autoHideDelay: 5000,
        appendToast: append,
      });
    },
    // ? Filter
    onFiltered(filteredItems) {
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
    // ? Filter
    onFilteredMahasiswa(filteredItems) {
      this.currentPageMahasiswa = 1;
      this.totalRowsMahasiswa = filteredItems.length;
    },
  },
  watch: {
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getBimbinganKonfirmasi();
      }
    },
  },
};
</script>
<style scoped>
.konfirmasi-bimbingan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
.table-konfirmasi-bimbingan {
  /* border-top: 3px solid rgb(202, 206, 147); */
  /* padding: 5px 30px 10px 10px; */
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
  white-space: pre-wrap;
}
/* // * Css Button */
.btn-select {
  margin: 10px 0px 10px 0px;
}
.pilihan-batch,
.component-filter {
  margin: auto;
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