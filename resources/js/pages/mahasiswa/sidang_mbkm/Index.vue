<template>
  <b-container>
    <div class="form-histori-sidang">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Histori Sidang</h3>
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
      <div class="histori-sidang">
        <!-- Table Histori Sidang  -->
        <b-table
          sticky-header
          class="table-histori-sidang"
          :items="kumpulan_sidang"
          :fields="fields"
          hover
          striped
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
          <template v-slot:cell(jenis_sidang)="data">{{
            data.item.jenis_sidang.nama_jenis_sidang
          }}</template>
          <template v-slot:cell(judul_topik)="data">{{
            data.item.topik.judul_topik
          }}</template>
          <template v-slot:cell(pembimbing)="data">
            <div class="row">{{ data.item.topik.pembimbing }}</div>
            <div
              class="row"
              v-if="
                data.item.topik.id_pembimbing !=
                data.item.topik.id_pembimbing_lapangan
              "
            >
              {{ data.item.topik.pembimbing_lapangan }}
            </div>
          </template>
          <template v-slot:cell(penguji)="data">
            <div class="row">{{ data.item.penguji_sidang }}</div>
            <div class="row">{{ data.item.penguji_sidang_dua }}</div>
          </template>
          <template v-slot:cell(status_sidang)="data">
            <div>{{ data.item.status_sidang }}</div>
          </template>
          <template v-slot:cell(revisi)="data">
            <router-link
              :to="`/sidang/revisi/${acakAcak(data.item.id_sidang, 0)}`"
              class="btn btn-form"
              v-if="
                data.item.status_sidang == 1 ||
                data.item.status_sidang == 0 ||
                data.item.status_sidang == 'lulus' ||
                data.item.status_sidang == 'tidak lulus'
              "
              >Revisi</router-link
            >
            <b-button class="btn-form" v-else disabled>Revisi</b-button>
          </template>
          <template v-slot:cell(detail)="data">
            <router-link
              :to="`/sidang/detail/${acakAcak(data.item.id_sidang, 0)}`"
              class="btn btn-form"
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
      kumpulan_topik: {},
      kumpulan_sidang: [],
      fields: [
        { key: "jenis_sidang", sortable: true },
        { key: "judul_topik", sortable: true },
        { key: "pembimbing", sortable: true },
        { key: "penguji", sortable: true },
        { key: "status_sidang", sortable: true },
        { key: "revisi", sortable: false },
        { key: "detail", sortable: false },
      ],
      id_pengaju: this.$store.getters.pengguna.UserId,
      isBusyHistory: true,
      sortBy: "id_sidang",
      sortDesc: true,
      // * Filter
      filter: null,
    };
  },
  mounted() {
    this.getHistoriSidang();
  },
  methods: {
    getHistoriSidang() {
      Axios.post(
        `${config.apiMahasiswaUrl}/list-sidang`,
        {
          id_pengaju: this.id_pengaju,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.kumpulan_sidang = response.data.data;

          this.changeDataSidang();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    changeDataSidang() {
      this.kumpulan_sidang.forEach((element) => {
        // if (!!element.topik.id_pembimbing) {
        //   // element.topik.id_pembimbing = this.cekNama(element.topik.id_pembimbing);
        //   Axios.get(
        //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //       element.topik.id_pembimbing
        //   ).then(response => {
        //     element.topik.id_pembimbing = response.data[0].Name;
        //   });
        // }
        // if (!!element.topik.id_pembimbing_lapangan) {
        //   Axios.get(
        //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //       element.topik.id_pembimbing_lapangan
        //   ).then(response => {
        //     element.topik.id_pembimbing_lapangan = response.data[0].Name;
        //   });
        // }
        // if (!!element.id_penguji_sidang) {
        //   Axios.get(
        //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //       element.id_penguji_sidang
        //   ).then(response => {
        //     element.id_penguji_sidang = response.data[0].Name;
        //   });
        // }
        // if (!!element.id_penguji_sidang_dua) {
        //   Axios.get(
        //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        //       element.id_penguji_sidang_dua
        //   ).then(response => {
        //     element.id_penguji_sidang_dua = response.data[0].Name;
        //   });
        // }
        element.status_sidang = this.cekStatus(element.status_sidang);
      });
      this.isBusyHistory = false;
    },
    // cekNama(id_pengguna) {
    //   Axios.get(
    //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` + id_pengguna
    //   )
    //     .then(response => {
    //       return response.data[0].Name;
    //     })
    //     .catch(response => {
    //       return "";
    //     });
    // },
    cekStatus(status) {
      if (status == 0) {
        return "tidak lulus";
      } else if (status == 1) {
        return "lulus";
      } else if (status == 2) {
        return "menunggu persetujuan";
      } else if (status == 3) {
        return "sidang sedang diajukan penjadwalan";
      } else if (status == 4) {
        return "sidang sudah di jadwalankan";
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
  },
};
</script>
<style scoped>
.histori-sidang {
  border-top: 3px solid rgb(202, 206, 147);
  background-color: rgb(255, 255, 255);
}

.component-filter {
  margin: auto;
}

/* // * CSS Tabel Histori Sidang */
.table-histori-sidang {
  background-color: rgb(255, 255, 255);
  max-height: 70vh;
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