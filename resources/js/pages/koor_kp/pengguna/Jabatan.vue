<template>
  <b-container>
    <div class="judul-website">
      <h3 class="keterangan-website mt-2">Daftar Dosen</h3>
    </div>
    <div class="isi-website">
      <b-row>
        <b-col>
          <!-- // ? Filter -->
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
          <!-- // ? ######################################################################## -->
          <!-- // ? Table -->
          <b-table
            sticky-header
            :current-page="currentPage"
            :per-page="perPage"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :filter="filter"
            class="table-dosen"
            ref="tableDosen"
            hover
            striped
            :items="kumpulan_dosen"
            :fields="fields"
            responsive="sm"
            :busy="isBusyDosen"
            show-empty
            @filtered="onFiltered"
          >
            <template v-slot:table-busy>
              <div class="text-center my-2">
                <ring-loader class="loading-page" color="#20a506" :size="50" />
                <strong class="loading-text">Loading...</strong>
              </div>
            </template>
            <template v-slot:empty>
              <h5 class="text-center">Tidak ada data Dosen</h5>
            </template>
            <!-- // ? Jabatan -->
            <template v-slot:cell(jabatan)="row">
              <!-- <router-link :to="`/pengguna/jabatan/update/${row.item.UserId}`" class="btn-form">Ubah</router-link> -->
              <b-button class="btn-form" size="sm" @click="row.toggleDetails">{{
                row.detailsShowing ? "Sembunyikan" : "Lihat"
              }}</b-button>
            </template>
            <template v-slot:row-details="row">
              <b-card>
                <JabatanPengguna :id_pengguna="row.item.UserId" />
              </b-card>
            </template>
            <!-- // ? ######################################################################## -->
          </b-table>
          <!-- // ? Pagination -->
          <b-pagination
            v-model="currentPage"
            :total-rows="totalRows"
            :per-page="perPage"
            align="fill"
            size="sm"
            class="mb-0"
          ></b-pagination>
          <!-- // ? ######################################################################## -->
        </b-col>
        <b-col>
          <template class="mb-0">
            <JabatanKbk />
          </template>
        </b-col>
      </b-row>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import JabatanPengguna from "../../../components/JabatanPengguna";
import JabatanKbk from "../kbk/Index";
export default {
  components: {
    JabatanPengguna,
    JabatanKbk,
  },
  data() {
    return {
      // * Filter
      filter: null,
      // * Pagination
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      // * Sorting
      sortBy: "UserId",
      sortDesc: false,
      // * Table
      fields: [
        { key: "UserId", label: "NIK", sortable: true },
        { key: "Name", label: "Nama", sortable: true },
        { key: "jabatan", sortable: false },
      ],
      kumpulan_dosen: [],
      isBusyDosen: false,
    };
  },
  mounted() {
    this.getKumpulanDosen();
  },
  methods: {
    // ? Data Table
    getKumpulanDosen() {
      this.isBusyDosen = true;
      Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered`)
        .then((response) => {
          this.kumpulan_dosen = response.data;
          this.totalRows = response.data.length;
          this.isBusyDosen = false;
        })
        .catch((response) => {
        });
    },
    getJabatan(row) {
      if (!row.item.jabatan) {
        Axios.post(`${config.apiUrl}/jabatan-pengguna/list`, {
          id_pengguna: row.item.UserId,
        })
          .then((response) => {
            this.kumpulan_dosen.forEach((element) => {
              if (element.UserId == row.item.UserId) {
                Object.assign(element, { jabatan: response.data.data });
                this.$refs.tableDosen.refresh();
              }
            });
          })
          .catch((response) => {

          });
      }
    },
    // ? Filter
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
  },
};
</script>
<style scoped>
.isi-website {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
/* // * CSS Table */
.table-dosen {
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