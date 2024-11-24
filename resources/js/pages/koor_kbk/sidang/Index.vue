<template>
  <b-container>
    <div>
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Pengaturan Penguji</h3>
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
      <div class="form-sidang">
        <b-table
          sticky-header
          class="table-sidang"
          ref="tabelSidang"
          :items="sidang"
          :fields="fields"
          hover
          striped
          responsive="sm"
          :busy="isBusySidang"
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
            <h5 class="text-center">Tidak ada data</h5>
          </template>
          <template v-slot:cell(pengaturan)="data">
            <div v-if="cekJabatan('Koordinator KP')">
              <router-link
                :to="`/sidang/penguji/${acakAcak(data.item.id_sidang, 0)}`"
                class="btn btn-form"
                >Lihat</router-link
              >
            </div>
            <div v-else-if="cekJabatan('Koordinator KBK')">
              <router-link
                :to="`/sidang/pengaturan-penguji/${acakAcak(
                  data.item.id_sidang,
                  0
                )}`"
                class="btn btn-form"
                >Lihat</router-link
              >
            </div>
          </template>
        </b-table>
        <div class="row">
          <!-- // ? Pagination -->
          <div class="col">
            <b-pagination
              v-model="currentPage"
              :total-rows="totalRows"
              :per-page="perPage"
              align="fill"
              size="sm"
              class="mb-0"
            ></b-pagination>
          </div>
          <div class="col"></div>
          <!-- // ? ######################################################################## -->
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
      // * Sorting
      sortBy: "topik.id_pengaju",
      sortDesc: false,
      // * Table
      fields: [
        { key: "topik.id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "topik.judul_topik", label: "Judul Topik", sortable: true },
        {
          key: "id_penguji_sidang",
          label: "Penguji",
          formatter: (value, key, item) => {
            return value ? value : "-";
          },
          sortable: true,
        },
        {
          key: "id_penguji_sidang_dua",
          label: "Penguji Dua",
          formatter: (value, key, item) => {
            return value ? value : "-";
          },
          sortable: true,
        },
        {
          key: "status_pembimbing_utama",
          label: "Status Pembimbing",
          sortable: true,
        },
        {
          key: "status_pembimbing_lapangan",
          label: "Status Pembimbing Lapangan",
          sortable: true,
        },
        { key: "pengaturan", sortable: false },
      ],
      id_batch: null,
      sidang: [],
      isBusySidang: true,
      id_kbk: null,
      pilihan_batch: [],
      id_semester: null,
      semester: null,
      isBusySemester: true,
      kumpulan_dosen: [],
      kumpulan_mahasiswa: [],
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      await this.getListDosen();
      // await this.getListMahasiswa();
      await this.getListSemester();
    },
    async getListDosen() {
      await Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered`)
        .then((response) => {
          this.kumpulan_dosen = response.data;
        })
        .catch((response) => {
          console.log(response);
        });
      return;
    },
    // async getListMahasiswa() {
    //   if (this.$store.getters.listMahasiswa) {
    //     this.kumpulan_mahasiswa = this.$store.getters.listMahasiswa;
    //   } else {
    //     await this.$store.dispatch("listMahasiswa").then((response) => {
    //       this.kumpulan_mahasiswa = this.$store.getters.listMahasiswa;
    //     });
    //   }
    //   return;
    // },
    cekJabatan(jabatan) {
      return this.$store.getters.jabatan.includes(jabatan);
    },
    async getListSemester() {
      this.isBusySemester = true;
      await Axios.get(`${config.apiUrl}/semester/aktif`)
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
      this.isBusySidang = true;
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
      // if (!!this.$store.getters.batchSidang) {
      //   this.id_batch = this.$store.getters.batchSidang.id_batch;
      //   // this.getListSidang();
      // } else {
      //   this.$store.dispatch("batchSidang").then(response => {
      //     this.id_batch = this.$store.getters.batchSidang.id_batch;
      //     // this.getListSidang();
      //   });
      // }
    },
    getListSidang() {
      if (this.cekJabatan("Koordinator KP")) {
        this.getSidangKp();
      } else if (this.cekJabatan("Koordinator KBK")) {
        this.getIdKbkJabatan();
      }
    },
    getIdKbkJabatan() {
      this.$store.getters.jabatan.forEach((element) => {
        if (element.includes("Ketua KBK")) {
          this.getIdKbk(element.replace("Ketua KBK ", ""));
        } else if (element.includes("Sekretaris KBK")) {
          this.getIdKbk(element.replace("Sekretaris KBK ", ""));
        }
      });
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
          this.getSidangKbk();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getSidangKbk() {
      this.isBusySidang = true;
      Axios.post(`${config.apiKbkUrl}/sidang/batch-kbk`, {
        id_batch: this.id_batch,
        id_kbk: this.id_kbk,
      })
        .then((response) => {
          this.sidang = response.data.data;
          this.totalRows = response.data.data.length;
          this.changeDataSidang();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getSidangKp() {
      this.isBusySidang = true;
      const formData = new FormData();
      formData.append("id_batch", this.id_batch);
      formData.append("status_sidang", [3, 4]);
      Axios.post(`${config.apiUrl}/sidang/list-sidang`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.sidang = response.data.data;
            this.totalRows = response.data.data.length;
            this.changeDataSidang();
          } else {
            this.isBusySidang = false;
            this.totalRows = 0;
          }
        })
        .catch((response) => {
          console.log(response);
        });
    },
    changeDataSidang() {
      for (let index = 0; index < this.sidang.length; index++) {
        const element = this.sidang[index];
        // const tempMahasiswa = this.kumpulan_mahasiswa.find(
        //   (dataMahasiswa) => dataMahasiswa.UserId == element.topik.id_pengaju
        // );
        // // if (tempMahasiswa) {
        // //   element.nama = tempMahasiswa.Name;
        // // }
        // console.log(element.nama);
        // // Axios.get(
        // //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        // //     element.topik.id_pengaju
        // // ).then((response) => {
        // //   element.nama = response.data[0].Name;
        // // });
        if (element.id_penguji_sidang) {
          const tempPenguji = this.kumpulan_dosen.find(
            (da) => da.UserId == element.id_penguji_sidang
          );
          console.log(tempPenguji);
          if (tempPenguji) {
            element.id_penguji_sidang = tempPenguji.Name;
          }
          console.log(element.id_penguji_sidang);
          //   Axios.get(
          //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
          //       element.id_penguji_sidang
          //   ).then((response) => {
          //     element.id_penguji_sidang = response.data[0].Name;
          //   });
        }
        // if (element.id_penguji_sidang_dua) {
        //   Axios.get(
        //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //       element.id_penguji_sidang_dua
        //   ).then((response) => {
        //     element.id_penguji_sidang_dua = response.data[0].Name;
        //   });
        // }
        element.status_pembimbing_utama = this.cekStatusPembimbing(
          element.status_pembimbing_utama
        );
        element.status_pembimbing_lapangan = this.cekStatusPembimbingLapangan(
          element.status_pembimbing_lapangan
        );
        if (index == this.sidang.length - 1) {
          this.$refs.tabelSidang.refresh();
        }
      }
      this.isBusySidang = false;
    },
    cekStatusPembimbing(index) {
      var status = ["ditolak", "diterima", "diajukan"];
      if (index >= 0 && index <= status.length) {
        return status[index];
      } else {
        return "";
      }
    },
    cekStatusPembimbingLapangan(index) {
      var status = [
        "ditolak",
        "diterima",
        "diajukan",
        "tidak membutuhkan persetujuan",
      ];
      if (index >= 0 && index <= status.length) {
        return status[index];
      } else {
        return "";
      }
    },
    acakAcak(isi, status) {
      isi = Array.from(String(isi));
      var code = "";
      if (status == 0) {
        isi.forEach((element) => {
          if (element == "0") {
            code = code + "c";
          } else if (element == "1") {
            code = code + "h";
          } else if (element == "2") {
            code = code + "r";
          } else if (element == "3") {
            code = code + "i";
          } else if (element == "4") {
            code = code + "s";
          } else if (element == "5") {
            code = code + "t";
          } else if (element == "6") {
            code = code + "a";
          } else if (element == "7") {
            code = code + "n";
          } else if (element == "8") {
            code = code + "o";
          } else if (element == "9") {
            code = code + "7";
          }
        });
      } else if (status == 1) {
        isi.forEach((element) => {
          if (element == "c") {
            code = code + "0";
          } else if (element == "h") {
            code = code + "1";
          } else if (element == "r") {
            code = code + "2";
          } else if (element == "i") {
            code = code + "3";
          } else if (element == "s") {
            code = code + "4";
          } else if (element == "t") {
            code = code + "5";
          } else if (element == "a") {
            code = code + "6";
          } else if (element == "n") {
            code = code + "7";
          } else if (element == "o") {
            code = code + "8";
          } else if (element == "7") {
            code = code + "9";
          }
        });
      }
      return code;
    },
    // ? Filter
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
  },
  watch: {
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getListSidang();
      }
    },
  },
};
</script>
<style scoped>
.form-sidang {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.pilihan-batch,
.component-filter {
  margin: auto;
}

/* // * Css Table */
.table-sidang {
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