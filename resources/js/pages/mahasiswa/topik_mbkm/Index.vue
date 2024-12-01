<template>
  <b-container>
    <div>
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Histori Topik Kerja Praktek</h3>
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
      <!-- Form Pengajuan Topik -->
      <div class="history-topik">
        <b-table
          sticky-header
          class="table-history"
          ref="tableHistory"
          hover
          striped
          :items="kumpulan_topik"
          :fields="fields"
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
            <h5 class="text-center">Tidak ada data</h5>
          </template>
          <template v-slot:cell(detail)="data">
            <router-link
              :to="`/topik/histori/${data.item.slug_topik}`"
              class="btn-form"
              >Lihat</router-link
            >
          </template>
        </b-table>
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
      fields: [
        { key: "judul_topik", sortable: true },
        { key: "batch.nama_batch", label: "Batch", sortable: false },
        { key: "pembimbing", label: "Pembimbing", sortable: true },
        { key: "status_topik", sortable: true },
        { key: "detail", sortable: false },
      ],
      kumpulan_topik: [],
      isBusyHistory: true,
      sortBy: "batch.id_batch",
      sortDesc: true,
      // * Filter
      filter: null,
    };
  },
  mounted() {
    this.getHistoriTopik();
  },
  methods: {
    getHistoriTopik() {
      const formData = new FormData();
      formData.append("id_pengaju", this.$store.getters.pengguna.UserId);
      Axios.post(`${config.apiMahasiswaUrl}/histori-topik`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.kumpulan_topik = response.data.data;
          this.changeStatus();
        })
        .catch((response) => {
          console.log(response);
        });
      // FIXME : Belum sesuai Mahasiswa
      // Axios.get(`${config.apiMahasiswaUrl}/topik`)
      //   .then(response => {
      //     this.kumpulan_topik = response.data.data;
      //     this.changeStatus();
      //   })
      //   .catch(response => {
      //     console.log(response);
      //   });
    },
    changeStatus() {
      this.kumpulan_topik.forEach((element) => {
        element.status_topik = this.cekStatus(element.status_topik);
        element.status_persetujuan_dosen = this.cekStatusDosen(
          element.status_persetujuan_dosen
        );
        // Axios.get(
        //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //     element.id_pembimbing
        // ).then(response => {
        //   element.id_pembimbing = response.data[0].Name;
        // });
      });
      this.isBusyHistory = false;
    },
    cekStatus(status) {
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
  },
};
</script>

<style scoped>
.history-topik {
  border-top: 3px solid rgb(202, 206, 147);
  background-color: rgb(255, 255, 255);
}

.table-history {
  background-color: rgb(255, 255, 255);
  max-height: 70vh;
}

.component-filter {
  margin: auto;
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