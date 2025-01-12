<template>
  <b-container>
    <div class="form-penjadwalan-sidang">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Konfirmasi MBKM</h3>
        </div>
        <!-- // ? Pilih Semester -->
        <div class="col-3 pilihan-semester">
          <b-form-select v-model="id_semester" size="sm" @change="getListMbkm">
            <template v-slot:first>
              <option :value="null" disabled>-- Semester --</option>
            </template>
            <option v-for="pilihan in pilihan_semester" :key="pilihan.id_semester" :value="pilihan.id_semester">
              {{ pilihan.nama_semester }}
            </option>
          </b-form-select>
        </div>
        <!-- // ? Filter -->
        <div class="col-3" style="margin: auto">
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
      </div>

      <div class="penjadwalan-sidang">
        <div class="penjadwalan-sidang-item">
          <b-table sticky-header class="tabel-penjadwalan-sidang" ref="tablePenjadwalanSidang" no-border-collapse
            :items="kumpulan_mbkm" :fields="fields" hover striped responsive="sm" :current-page="currentPage"
            :per-page="perPage" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :filter="filter"
            @filtered="onFiltered">
            <template v-slot:table-busy>
              <div class="text-center my-2">
                <ring-loader class="loading-page" color="#20a506" :size="50" />
                <strong class="loading-text">Loading...</strong>
              </div>
            </template>
            <template v-slot:empty>
              <div class="text-center">
                <h5 class="text-muted">Tidak ada data mahasiswa MBKM yang tersedia untuk semester ini.</h5>
                <p class="text-muted">Silakan pilih semester yang berbeda atau periksa kembali data yang dimasukkan.</p>
              </div>
            </template>
            <template v-slot:cell(no)="data">
              {{ (currentPage - 1) * perPage + data.index + 1 }}
            </template>
            <template v-slot:cell(detail_konversi)="data">
              <b-button size="sm" @click="goToKonversiSKS(data.item)" variant="info">Detail</b-button>
            </template>
            <!-- Kolom Status dengan Simbol -->
            <template v-slot:cell(status)="data">
              <span v-if="data.item.status === 0">❌</span>
              <span v-else>✔️</span>
            </template>
          </b-table>
          <b-row>
            <!-- // ? Pagination -->
            <b-col>
              <b-row>
                <b-col>
                  <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage" align="fill" size="sm"
                    class="mb-0"></b-pagination>
                </b-col>
                <b-col lg="2">
                  <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
                </b-col>
              </b-row>
            </b-col>
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
      // * Pagination
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
      // * Sorting
      sortBy: "nama",
      sortDesc: false,
      // * Table
      kumpulan_mbkm: [],
      fields: [
        { key: "no", label: "No", sortable: true },
        { key: "mahasiswa.kode_pengguna", label: "NRP", sortable: true },
        { key: "mahasiswa.nama_pengguna", label: "Nama", sortable: true },
        { key: "jenis_mbkm.nama_mbkm", label: "Jenis MBKM", sortable: true },
        { key: "nama_instansi", sortable: true },
        { key: "detail_konversi", sortable: true },
        { key: "status", sortable: true }
      ],
      isBusyPenjadwalan: true,
      tataUsaha: true,
      loading: false,
      id_semester: null,
      pilihan_semester: [],
      semester: null,
      isBusySemester: true,
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
    };
  },
  mounted() {
    this.getListSemester();
  },
  methods: {
    goToKonversiSKS(item) {
      this.$router.push({ name: 'KonversiMBKM', params: { id: item.id_mbkm } });
    },
    // Mengambil data MBKM berdasarkan semester yang dipilih
    getListMbkm() {
      if (this.id_semester) {
        Axios.get(`${config.apiUrl}/mbkm/list/${this.id_semester}`).then((response) => {
          console.log(response);
          if (response.data.data.length === 0) {
            this.kumpulan_mbkm = [];
          } else {
            this.kumpulan_mbkm = response.data.data;
          }
        })
          .catch((error) => {
            console.error(error);
            this.kumpulan_mbkm = [];
          });
      }
    },
    getListSemester() {
      this.isBusySemester = true;
      Axios.get(`${config.apiUrl}/semester/list`)
        .then((response) => {
          this.pilihan_semester = response.data.data;
          const activeSemester = this.pilihan_semester.find(semester => semester.status_semester === 1);
          if (activeSemester) {
            this.id_semester = activeSemester.id_semester;
            this.getListMbkm();
          }
        })
        .catch((response) => {
          this.isBusySemester = false;
        });
    },
    info(item, index, button) {
      this.infoModal.title = `Row index: ${index}`;
      this.infoModal.content = item;
      this.$root.$emit("bv::show::modal", this.infoModal.id, button);
    },
    resetInfoModal() {
      this.infoModal.title = "";
      this.infoModal.content = "";
    },
    // ? Filter
    onFiltered(filteredItems) {
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
  },
  watch: {
    // Memanggil getListMbkm setiap kali semester berubah
    id_semester(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.getListMbkm();
      }
    }
  }
};
</script>
<style scoped>
/* // * Penjadwalan Sidang */
.penjadwalan-sidang {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.pilihan-semester,
.component-filter {
  margin: auto;
}

/* // * Css Table */
.tabel-penjadwalan-sidang {
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

.btn-form:hover,
.btn-modal:hover {
  font-size: 15px;
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}

.btn-modal {
  font-size: 15px;
  border-radius: 5px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
  width: 80px;
}

/* // ! CSS Icon */
.i-icon {
  font-size: 15px;
}

.icon-remove {
  color: red;
  font-size: 21px;
}

.icon-update {
  color: white;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}

.loading-text {
  color: rgb(32, 165, 6);
}
</style>