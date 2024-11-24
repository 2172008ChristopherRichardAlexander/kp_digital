<template>
  <b-container>
    <!-- // ? Permintaan -->
    <div class="bimbingan">
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website mt-2">Detail Bimbingan</h3>
        </div>
      </div>
      <div class="form-bimbingan">
        <div v-if="loading_bimbingan">
          <ring-loader
            class="loading-page"
            color="#bada55"
            :loading="loading_bimbingan"
            :size="150"
          />
        </div>
        <TableDetailBimbingan v-else :id_topik="sidang.topik.id_topik" />
        <template class="btn-select">
          <div class="btn-select text-right">
            <b-button class="btn-form" @click="terimaPengajuanSidang">Terima Sidang</b-button>
            <b-button class="btn-form" @click="tolakPengajuanSidang">Tolak Sidang</b-button>
          </div>
        </template>
      </div>
      <!-- // ? Topik -->
      <div class="topik">
        <div class="row">
          <div class="col">
            <h3 class="keterangan-website mt-2">Detail Topik</h3>
          </div>
        </div>
        <div class="form-topik">
          <div v-if="loading_topik">
            <ring-loader class="loading-page" color="#bada55" :loading="loading_topik" :size="150" />
          </div>
          <DetailTopik v-else :topik="sidang.topik" />
        </div>
      </div>
      <!-- // ? ###################################################################### -->
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import TableDetailBimbingan from "../../../components/TableDetailBimbingan";
import DetailTopik from "../../../components/DetailTopik";
export default {
  components: {
    TableDetailBimbingan,
    DetailTopik
  },
  data() {
    return {
      sidang: {},
      loading_bimbingan: true,
      loading_topik: true,
      id_pengguna: this.$store.getters.pengguna.UserId
    };
  },
  mounted() {
    this.getSidang();
  },
  methods: {
    getSidang() {
      this.loading_bimbingan = true;
      this.loading_topik = true;
      Axios.post(`${config.apiDosenUrl}/sidang/detail`, {
        id_sidang: this.acakAcak(this.$route.params.id, 1)
      })
        .then(response => {
          this.sidang = response.data.data;
          this.loading_bimbingan = false;
          this.loading_topik = false;
        })
        .catch(response => {
          console.log(response);
        });
    },
    terimaPengajuanSidang() {
      this.loading = true;
      this.statusPembimbingUtama(this.sidang.id_sidang, 1);
      if (this.sidang.topik.id_pembimbing_lapangan) {
        this.statusPembimbingLapangan(this.sidang.id_sidang, 1);
      } else {
        this.statusPembimbingLapangan(this.sidang.id_sidang, 3);
      }
      this.$router.push("/sidang/persetujuan");
    },
    tolakPengajuanSidang() {
      this.loading = true;
      this.statusPembimbingUtama(this.sidang.id_sidang, 0);
      if (this.sidang.topik.id_pembimbing_lapangan) {
        this.statusPembimbingLapangan(this.sidang.id_sidang, 0);
      } else {
        this.statusPembimbingLapangan(this.sidang.id_sidang, 3);
      }
      this.$router.push("/sidang/persetujuan");
    },
    statusPembimbingUtama(id, status) {
      Axios.put(
        `${config.apiDosenUrl}/sidang/update-persetujuan-pembimbing-utama`,
        {
          id_sidang: id,
          id_pembimbing: this.id_pengguna,
          status_pembimbing_utama: status
        },
        {
          headers: {
            "Content-Type": "application/json"
          }
        }
      )
        .then(response => {
          // this.postKomentar(element.id_topik);
        })
        .catch(response => {
          console.log(response);
        });
    },
    statusPembimbingLapangan(id, status) {
      Axios.put(
        `${config.apiDosenUrl}/sidang/update-persetujuan-pembimbing-lapangan`,
        {
          id_sidang: id,
          id_pembimbing_lapangan: this.id_pengguna,
          status_pembimbing_lapangan: status
        },
        {
          headers: {
            "Content-Type": "application/json"
          }
        }
      )
        .then(response => {
          // this.postKomentar(element.id_topik);
        })
        .catch(response => {
          console.log(response);
        });
    },
    acakAcak(isi, status) {
      isi = Array.from(String(isi));
      var code = "";
      if (status == 0) {
        isi.forEach(element => {
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
        isi.forEach(element => {
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
    }
  }
};
</script>
<style scoped>
.table-bimbingan {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}
.form-bimbingan, .form-topik {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  margin: 0px 10px 0px 0px;
  background-color: rgb(255, 255, 255);
}
.btn-select {
  margin: 10px 0px 10px 0px;
}

.btn-form {
  padding: 0.38vw 1.4vw;
  font-size: 15px;
  border-radius: 5px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
}

.btn-form:disabled,
.btn-form:disabled:hover {
  background-color: rgb(198, 205, 180);
  color: rgb(28, 31, 1);
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