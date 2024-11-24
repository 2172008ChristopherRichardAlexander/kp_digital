<template>
  <b-container>
    <!-- // ? Dosen -->
    <div class="permintaan-persetujuan">
      <div class="judul-website">
        <h3 class="keterangan-website mt-2">Reset Password</h3>
      </div>
      <div class="isi-webiste">
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
                  <b-button class="btn-form" :disabled="!filter" @click="filter = ''">Hapus</b-button>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
            <!-- // ? ######################################################################## -->
            <!-- // ? Table -->
            <b-table
              sticky-header
              class="table-pengguna"
              ref="tableDosen"
              selectable
              :select-mode="selectMode"
              selected-variant="active"
              hover
              striped
              :items="kumpulan_dosen"
              :fields="fields_dosen"
              @row-selected="onRowSelected"
              responsive="sm"
              :busy="isBusyDosen"
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
                <h5 class="text-center">Tidak ada data Dosen</h5>
              </template>
              <!-- A custom formatted header cell for field 'name' -->
              <template v-slot:head(selected)>
                <div class="box-select">
                  <!-- // TODO: Belum di uji coba -->
                  <template
                    v-if="selected_dosen.length < kumpulan_dosen.length && selected_dosen.length > 0"
                  >
                    <span aria-hidden="true" @click="selectAllRows" class="simbol">&minus;</span>
                    <span class="sr-only" @click="selectAllRows">Select All</span>
                  </template>
                  <template v-else-if="Array.isArray(selected_dosen) && selected_dosen.length">
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
                  <span aria-hidden="true" style="color:rgb(175,170,6);">&check;</span>
                  <span class="sr-only">Selected</span>
                </template>
                <template v-else>
                  <span aria-hidden="true" style="color:rgb(175,170,6);">&#9744;</span>
                  <span class="sr-only">Not selected</span>
                </template>
              </template>

              <!-- // ? Reset -->
              <template v-slot:cell(reset)="data">
                <b-button class="btn-form" @click="ubahPassword(data.item.UserId)">Reset</b-button>
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
            <!-- // ? Filter -->
            <b-form-group label-size="sm" label-for="filterInputMahasiswa" class="mb-0">
              <b-input-group size="sm">
                <b-form-input
                  v-model="filterMahasiswa"
                  type="search"
                  id="filterInputMahasiswa"
                  placeholder="Ketik untuk mencari"
                ></b-form-input>
                <b-input-group-append>
                  <b-button
                    class="btn-form"
                    :disabled="!filterMahasiswa"
                    @click="filterMahasiswa = ''"
                  >Hapus</b-button>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
            <!-- // ? ######################################################################## -->
            <!-- // ? Table -->
            <b-table
              sticky-header
              class="table-pengguna"
              ref="tableMahasiswa"
              selectable
              :select-mode="selectMode"
              selected-variant="active"
              hover
              striped
              :items="kumpulan_mahasiswa"
              :fields="fields_mahasiswa"
              @row-selected="onRowSelectedMahasiswa"
              responsive="sm"
              :busy="isBusyMahasiswa"
              show-empty
              :current-page="currentPageMahasiswa"
              :per-page="perPage"
              :sort-by.sync="sortByMahasiswa"
              :sort-desc.sync="sortDescMahasiswa"
              :filter="filterMahasiswa"
              @filtered="onFilteredMahasiswa"
            >
              <template v-slot:table-busy>
                <div class="text-center my-2">
                  <ring-loader class="loading-page" color="#20a506" :size="50" />
                  <strong class="loading-text">Loading...</strong>
                </div>
              </template>
              <template v-slot:empty>
                <h5 class="text-center">Tidak ada data Mahasiswa</h5>
              </template>
              <!-- A custom formatted header cell for field 'name' -->
              <template v-slot:head(selected)>
                <div class="box-select">
                  <template
                    v-if="selected_mahasiswa.length < kumpulan_mahasiswa.length && selected_mahasiswa.length > 0"
                  >
                    <span aria-hidden="true" @click="selectAllRowsMahasiswa" class="simbol">&minus;</span>
                    <span class="sr-only" @click="selectAllRowsMahasiswa">Select All</span>
                  </template>
                  <template
                    v-else-if="Array.isArray(selected_mahasiswa) && selected_mahasiswa.length"
                  >
                    <span aria-hidden="true" @click="clearSelectedMahasiswa" class="simbol">&check;</span>
                    <span class="sr-only" @click="clearSelectedMahasiswa">Clear Selected</span>
                  </template>
                  <template v-else>
                    <span aria-hidden="true" @click="selectAllRowsMahasiswa" class="simbol">&#9744;</span>
                    <span class="sr-only" @click="selectAllRowsMahasiswa">Select All</span>
                  </template>
                </div>
              </template>
              <!-- Example scoped slot for select state illustrative purposes -->
              <template v-slot:cell(selected)="{ rowSelected }">
                <template v-if="rowSelected">
                  <span aria-hidden="true" style="color:rgb(175,170,6);">&check;</span>
                  <span class="sr-only">Selected</span>
                </template>
                <template v-else>
                  <span aria-hidden="true" style="color:rgb(175,170,6);">&#9744;</span>
                  <span class="sr-only">Not selected</span>
                </template>
              </template>

              <!-- // ? Reset -->
              <template v-slot:cell(reset)="data">
                <b-button class="btn-form" @click="ubahPassword(data.item.UserId)">Reset</b-button>
              </template>
              <!-- // ? ######################################################################## -->
            </b-table>
            <!-- // ? Pagination -->
            <b-pagination
              v-model="currentPageMahasiswa"
              :total-rows="totalRowsMahasiswa"
              :per-page="perPage"
              align="fill"
              size="sm"
              class="mb-0"
            ></b-pagination>
            <!-- // ? ######################################################################## -->
          </b-col>
        </b-row>
        <b-row>
          <b-col class="text-right">
            <template
              v-if="(Array.isArray(selected_dosen) && selected_dosen.length)||(Array.isArray(selected_mahasiswa) && selected_mahasiswa.length)"
              class="btn-select"
            >
              <div class="btn-select">
                <b class="text-success" v-if="pesan && pesan.id == 1">Berhasil</b>
                <b-button
                  class="btn-form"
                  @click="$bvModal.show('bv-modal-reset-password')"
                  :disabled="loading_reset"
                >
                  {{loading_reset?'':'Reset Password'}}
                  <ring-loader class="loading-page" color="white" :size="25" v-if="loading_reset" />
                </b-button>
              </div>
            </template>
            <template v-else class="btn-select">
              <div class="btn-select">
                <b class="text-success" v-if="pesan && pesan.id == 1">Berhasil</b>
                <b-button class="btn-form" disabled>Reset Password</b-button>
              </div>
            </template>
          </b-col>
        </b-row>
        <!-- // ? Modal Reset Password -->
        <b-modal ref="bv-modal-reset-password" scrollable centered id="bv-modal-reset-password">
          <template v-slot:modal-title>Reset Password</template>
          <div class="d-block form-input-text text-center">
            <div v-if="selected_dosen.length > 0">
              <h5>Data Dosen yang di reset:</h5>
              <b-table :fields="fields_reset_dosen" :items="selected_dosen"></b-table>
            </div>
            <div v-if="selected_mahasiswa.length > 0">
              <h5>Data Mahasiswa yang di reset:</h5>
              <b-table :fields="fields_reset_mahasiswa" :items="selected_mahasiswa"></b-table>
            </div>
          </div>
          <template v-slot:modal-footer="{ cancel }">
            <div class="w-100">
              <p class="float-left" v-if="pesan">
                <b class="text-success" v-if="pesan.id == 1">Reset Berhasil</b>
              </p>
              <div class="float-right">
                <b-button class="btn-form" @click="cancel()">Batal</b-button>
                <b-button class="btn-form" :disabled="loading_reset" @click="resetPassword">
                  {{loading_reset?'':'Reset'}}
                  <ring-loader class="loading-page" color="white" :size="25" v-if="loading_reset" />
                </b-button>
              </div>
            </div>
          </template>
        </b-modal>
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
      filterMahasiswa: null,
      // * Pagination
      totalRows: 1,
      totalRowsMahasiswa: 1,
      currentPage: 1,
      currentPageMahasiswa: 1,
      perPage: 10,
      // * Sorting
      sortBy: "UserId",
      sortDesc: false,
      sortByMahasiswa: "UserId",
      sortDescMahasiswa: false,
      // * Table
      fields_reset_dosen: [
        { key: "UserId", label: "NIK" },
        { key: "Name", label: "Nama" }
      ],
      fields_reset_mahasiswa: [
        { key: "UserId", label: "NRP" },
        { key: "Name", label: "Nama" }
      ],
      fields_dosen: [
        { key: "selected", label: "", sortable: false },
        { key: "UserId", label: "NIK", sortable: true },
        { key: "Name", label: "Nama", sortable: true },
        { key: "reset", sortable: false }
      ],
      fields_mahasiswa: [
        { key: "selected", label: "", sortable: false },
        { key: "UserId", label: "NRP", sortable: true },
        { key: "Name", label: "Nama", sortable: true },
        { key: "reset", sortable: false }
      ],
      selectMode: "multi",
      selected: [],
      selected_dosen: [],
      selected_mahasiswa: [],
      kumpulan_dosen: [],
      kumpulan_mahasiswa: [],
      isBusyDosen: true,
      isBusyMahasiswa: true,
      pesan: null,
      loading_reset: false
    };
  },
  mounted() {
    this.getListDosen();
    this.getListMahasiswa();
  },
  methods: {
    getListDosen() {
      this.selected_dosen = [];
      this.isBusyDosen = true;
      Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered`)
        .then(response => {
          this.kumpulan_dosen = response.data;
          this.totalRows = response.data.length;
          this.isBusyDosen = false;
        })
        .catch(response => {
          console.log(response);
        });
    },
    getListMahasiswa() {
      this.selected_mahasiswa = [];
      this.isBusyMahasiswa = true;
      // Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered?input_id_roles=3`)
      //   .then(response => {
      if (this.$store.getters.listMahasiswa) {
        this.kumpulan_mahasiswa = this.$store.getters.listMahasiswa;
        this.totalRowsMahasiswa = this.kumpulan_mahasiswa.length;
        this.isBusyMahasiswa = false;
      } else {
        this.$store.dispatch("listMahasiswa").then(response => {
          this.kumpulan_mahasiswa = this.$store.getters.listMahasiswa;
          this.totalRowsMahasiswa = this.kumpulan_mahasiswa.length;
          this.isBusyMahasiswa = false;
        });
      }
      // })
      // .catch(response => {
      //   console.log(response);
      // });
    },
    resetPassword() {
      this.loading_reset = true;
      this.pesan = null;
      this.selected = this.selected_dosen.concat(this.selected_mahasiswa);
      for (let index = 0; index < this.selected.length; index++) {
        const element = this.selected[index];
        this.ubahPassword(element.UserId);
        if (index >= this.selected.length - 1) {
          this.loading_reset = false;
          this.$refs["bv-modal-reset-password"].hide();
          this.selected = [];
          this.selected_dosen = [];
          this.selected_mahasiswa = [];
          this.$refs.tableDosen.refresh();
          this.$refs.tableMahasiswa.refresh();
        }
      }
    },
    ubahPassword(UserId) {
      Axios.post(
        `${config.apiIbatsBiasaUrl}/ResetPass`,
        {
          input_user_id: UserId
        },
        {
          headers: {
            "Content-Type": "application/json"
          }
        }
      )
        .then(response => {
          this.pesan = response.data;
          console.log(this.pesan);
        })
        .catch(response => {
          console.log(response);
        });
    },
    onRowSelected(items) {
      this.selected_dosen = items;
    },
    selectAllRows() {
      this.$refs.tableDosen.selectAllRows();
    },
    clearSelected() {
      this.$refs.tableDosen.clearSelected();
    },
    onRowSelectedMahasiswa(items) {
      this.selected_mahasiswa = items;
    },
    selectAllRowsMahasiswa() {
      this.$refs.tableMahasiswa.selectAllRows();
    },
    clearSelectedMahasiswa() {
      this.$refs.tableMahasiswa.clearSelected();
    },
    // ? Filter
    onFiltered(filteredItems) {
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
    onFilteredMahasiswa(filteredItems) {
      this.currentPageMahasiswa = 1;
      this.totalRowsMahasiswa = filteredItems.length;
    }
  }
};
</script>
<style scoped>
.table-pengguna {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}
.permintaan-persetujuan {
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