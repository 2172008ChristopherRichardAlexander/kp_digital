<template>
  <b-container>
    <div class="form-histori-bimbingan">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Histori Bimbingan</h3>
        </div>
      </div>
      <!-- Title Histori Bimbingan -->
      <div class="histori-bimbingan">
        <div class="row">
          <div class="col text-left">
            <h4 class="keterangan-website">Daftar Bimbingan</h4>
          </div>
          <div class="col text-right">
            <router-link
              :to="`/bimbingan/ajukan/${topik.slug_topik}`"
              class="btn btn-form"
            >Tambah Bimbingan</router-link>
          </div>
        </div>
        <div class="row">
          <div class="col text-left padding-margin">
            <!-- <h5 class="keterangan-website text-center">{{topik.judul_topik}}</h5> -->
            <h5 class="keterangan-website">Judul Topik : {{topik.judul_topik}}</h5>
          </div>
          <!-- // ? Filter -->
          <div class="col-3">
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
          </div>
          <!-- // ? ######################################################################## -->
        </div>
        <!-- // * Button Select All, Clear All -->
        <!-- Button Select All, Clear Selected-->
        <!-- <div class="btn-select">
          <template v-if="selected.length != bimbingan.length">
            <b-button size="sm" @click="selectAllRows" class="btn-form">Select all</b-button>
          </template>
          <template v-if="Array.isArray(selected) && selected.length">
            <b-button size="sm" @click="clearSelected" class="btn-form">Clear selected</b-button>
          </template>
        </div>-->
        <!-- Table Histori Bimbingan  -->
        <b-table
          sticky-header
          class="table-histori-bimbingan"
          ref="tabelHistory"
          selectable
          :select-mode="selectMode"
          selected-variant="active"
          hover
          striped
          :items="bimbingan"
          :fields="fields"
          @row-selected="onRowSelected"
          responsive="sm"
          :busy="isBusyHistory"
          show-empty
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          :filter="filter"
        >
          <template v-slot:table-busy>
            <div class="text-center my-2">
              <ring-loader class="loading-page" color="#20a506" :size="50" />
              <strong class="loading-text">Loading...</strong>
            </div>
          </template>
          <template v-slot:empty>
            <h5 class="text-center">Tidak ada data bimbingan</h5>
          </template>
          <!-- A custom formatted header cell for field 'name' -->
          <template v-slot:head(selected)>
            <div class="box-select">
              <!-- // TODO: Belum di uji coba -->
              <template v-if="selected.length < bimbingan.length && selected.length > 0">
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
        </b-table>
        <!-- Button Selected Konfirmasi -->
        <template class="btn-select">
          <div class="btn-select text-right">
            <!-- <b-button class="btn-form" @click="konfirmasiDosen">Konfirmasi Dosen</b-button> -->
            <b-button
              class="btn-form"
              id="show-btn"
              @click="$bvModal.show('bv-modal-konfirmasi-dosen')"
              :disabled="!status_konfirmasi"
            >Konfirmasi Dosen</b-button>
          </div>
        </template>
      </div>

      <b-modal ref="bv-modal-konfirmasi-dosen" id="bv-modal-konfirmasi-dosen" hide-footer>
        <template v-slot:modal-title>Konfirmasi Dosen</template>

        <b-form
          @submit.prevent="konfirmasiDosen"
          v-if="show"
          enctype="multipart/form-data"
          method="post"
        >
          <div class="d-block form-input-text text-center">
            <b-form-input
              class="form-input-text-item"
              id="input_user_password"
              v-model="input_user_password"
              type="password"
              required
              placeholder="Kode Pengguna"
              size="sm"
            ></b-form-input>
          </div>
          <div class="pesan-invalid" v-if="pesan">
            <small class="text-danger">{{pesan}}</small>
          </div>
          <div class="text-right">
            <b-button type="submit" class="btn-form" :disabled="!input_user_password">Konfirmasi</b-button>
          </div>
        </b-form>
      </b-modal>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
export default {
  data() {
    return {
      topik: {},
      id_pengguna: this.$store.getters.pengguna.UserId,
      fields: [
        { key: "selected", label: "", sortable: false },
        { key: "tanggal_bimbingan", label: "Tanggal", sortable: true },
        { key: "deskripsi_bimbingan", label: "Deskripsi", sortable: false },
        { key: "status_bimbingan", label: "Status", sortable: true }
      ],
      bimbingan: [],
      selectMode: "multi",
      selected: [],
      loading: false,
      input_user_password: null,
      show: true,
      pesan: null,
      isBusyHistory: true,
      // * Sorting
      sortBy: "tanggal_bimbingan",
      sortDesc: true,
      // * Filter
      filter: null,
      status_konfirmasi: false
    };
  },
  mounted() {
    this.getTopik();
  },
  methods: {
    onRowSelected(items) {
      this.selected = items;
    },
    selectAllRows() {
      this.$refs.tabelHistory.selectAllRows();
    },
    clearSelected() {
      this.$refs.tabelHistory.clearSelected();
    },
    getTopik() {
      Axios.get(`${config.apiMahasiswaUrl}/topik/${this.$route.params.id}`)
        .then(response => {
          this.topik = response.data.data;
          this.getBimbingan();
        })
        .catch(response => {
          console.log(response);
        });
    },
    getBimbingan() {
      this.isBusyHistory = true;
      Axios.post(
        `${config.apiMahasiswaUrl}/bimbingan-topik`,
        {
          id_topik: this.topik.id_topik
        },
        {
          headers: {
            "Content-Type": "application/json"
          }
        }
      )
        .then(response => {
          this.bimbingan = response.data.data;
          this.changeStatus();
        })
        .catch(response => {
          console.log(response);
        });
    },
    changeStatus() {
      this.bimbingan.forEach(element => {
        element.status_bimbingan = this.cekStatus(element.status_bimbingan);
      });
      this.isBusyHistory = false;
    },
    cekStatus(status) {
      if (status == 0) {
        return "ditolak";
      } else if (status == 1) {
        return "diterima";
      } else if (status == 2) {
        return "sedang diajukan";
      } else {
        return "";
      }
    },
    konfirmasiDosen() {
      this.loading = true;
      //api request
      // const formData = new FormData();
      // formData.append("input_user_id", this.topik.id_pembimbing);
      // formData.append("input_user_password", this.input_user_password);
      Axios.post(
        `${config.apiLoginUrl}`,
        // formData,
        {
          input_user_id: this.topik.id_pembimbing,
          // input_user_id: this.topik.id_pengaju,
          input_user_password: this.input_user_password
        },
        {
          headers: {
            "Content-Type": "application/json"
            // "Content-Type": "multipart/form-data"
          }
        }
      )
        .then(response => {
          if (response.data.Message == "Login Success") {
            this.konfirmasiBimbingan();
          } else {
            this.pesan = "Password Salah";
          }
        })
        .catch(response => {
          this.loading = false;
          console.log(response);
        });
    },
    konfirmasiBimbingan() {
      this.loading = true;
      this.selected.forEach(element => {
        Axios.put(
          `${config.apiMahasiswaUrl}/bimbingan`,
          {
            deskripsi_bimbingan: element.deskripsi_bimbingan,
            tanggal_bimbingan: element.tanggal_bimbingan,
            status_bimbingan: 1,
            id_topik: element.id_topik.id_topik,
            id_bimbingan: element.id_bimbingan,
            id_pembimbing_bimbingan: element.id_pembimbing_bimbingan
          },
          {
            headers: {
              "Content-Type": "application/json"
            }
          }
        )
          .then(response => {
            this.loading = false;
            this.$refs["bv-modal-konfirmasi-dosen"].hide();
          })
          .catch(response => {
            this.loading = false;
            console.log(response);
          });
      });
      this.getTopik();
      this.clearSelected();
    },
    periksaSelectedBimbingan() {
      var status_diterima = false;
      for (let index = 0; index < this.selected.length; index++) {
        const element = this.selected[index];
        if (
          element.status_bimbingan == "diterima" ||
          element.status_bimbingan == 1
        ) {
          status_diterima = true;
          break;
        }
      }
      if (!status_diterima && this.selected != 0) {
        this.status_konfirmasi = true;
      } else {
        this.status_konfirmasi = false;
      }
    }
  },
  watch: {
    selected() {
      this.periksaSelectedBimbingan();
    }
  },
  computed: {
    authUser() {
      return this.$root.auth;
    }
  }
};
</script>
<style scoped>
.keterangan-website {
  margin-left: 0px;
  padding-left: 0px;
}
.histori-bimbingan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 5px 30px 10px 20px;
  margin: 0px 10px 0px 0px;
  background-color: rgb(255, 255, 255);
}

.table-histori-bimbingan {
  /* border-top: 3px solid rgb(202, 206, 147); */
  /* padding: 5px 30px 10px 10px; */
  margin: 10px 0px 0px 0px;
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
  white-space: pre-wrap;
}
/* // * Css Form Input Text Item */
.form-input-text-item {
  margin: 0px 0px 10px 0px;
}
/* // * Css Button */
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
/* // * Pesan Invalid */
/* .pesan-invalid{
  padding: 0px;
  margin:0px;
} */

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