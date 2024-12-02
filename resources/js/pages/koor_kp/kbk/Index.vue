<template>
  <div>
    <div v-if="loading_page">
      <ring-loader class="loading-page" color="#bada55" :loading="loading_page" :size="150" />
    </div>
    <div class="jabatan-pengguna" v-else>
      <!-- // ? Button Ubah KBK -->
      <b-row>
        <b-col class="btn-select">
          <b-button-group size="sm">
            <div :key="kbk.id_kbk" v-for="kbk in list_kbk">
              <b-button class="btn-radio" v-if="kbk.id_kbk == id_kbk">{{kbk.nama_kbk}}</b-button>
              <b-button
                class="btn-radio-disable"
                @click="ubahKbk(kbk.id_kbk)"
                v-else
              >{{kbk.nama_pendek}}</b-button>
            </div>
          </b-button-group>
        </b-col>
      </b-row>
      <!-- // ? ############################################################################################### -->
      <!-- // ? Keanggotaan -->
      <div class="text-center my-2" v-if="loading_anggota_kbk">
        <ring-loader class="loading-page" color="#20a506" :size="50" />
        <strong class="loading-text">Loading...</strong>
      </div>
      <div class="daftar-keanggotaan" v-else>
        <template v-if="id_kbk == 5">
          <!-- // * Other -->
          <div v-for="pengguna in other" :key="pengguna.id_jabatan_pengguna">
            <div class="title-keanggotaan">{{pengguna.jabatan.nama_jabatan}}</div>
            <div class="list-keanggotaan">
              <div class="row card-jabatan">
                <div class="col nama-pengguna">{{pengguna.nama_pengguna}}</div>
                <div class="col-1 text-right">
                  <span
                    class="cross-mark"
                    @click="konfirmasiHapusJabatan(pengguna.id_jabatan_pengguna,pengguna.nama_pengguna,pengguna.jabatan.nama_jabatan)"
                  >&#10060;</span>
                </div>
              </div>
            </div>
          </div>
          <!-- // * ################################################ -->
        </template>
        <template v-else>
          <!-- // * Ketua -->
          <div class="title-keanggotaan">Ketua RG</div>
          <div class="list-keanggotaan">
            <div
              class="row card-jabatan"
              v-for="pengguna in ketua_kbk"
              :key="pengguna.id_jabatan_pengguna"
            >
              <div class="col nama-pengguna">{{pengguna.nama_pengguna}}</div>
              <div class="col-1 text-right">
                <span
                  class="cross-mark"
                  @click="konfirmasiHapusJabatan(pengguna.id_jabatan_pengguna,pengguna.nama_pengguna,pengguna.jabatan.nama_jabatan)"
                >&#10060;</span>
              </div>
            </div>
          </div>
          <!-- // * ################################################ -->
          <!-- // * Sekretaris -->
          <div class="title-keanggotaan">Sekretaris RG</div>
          <div class="list-keanggotaan">
            <div
              class="row card-jabatan"
              v-for="pengguna in sekretaris_kbk"
              :key="pengguna.id_jabatan_pengguna"
            >
              <div class="col nama-pengguna">{{pengguna.nama_pengguna}}</div>
              <div class="col-1 text-right">
                <span
                  class="cross-mark"
                  @click="konfirmasiHapusJabatan(pengguna.id_jabatan_pengguna,pengguna.nama_pengguna,pengguna.jabatan.nama_jabatan)"
                >&#10060;</span>
              </div>
            </div>
          </div>
          <!-- // * ################################################ -->
          <!-- // * Anggota -->
          <div class="title-keanggotaan">Anggota RG</div>
          <div class="list-keanggotaan">
            <div
              class="row card-jabatan"
              v-for="pengguna in anggota_kbk"
              :key="pengguna.id_jabatan_pengguna"
            >
              <div class="col nama-pengguna">{{pengguna.nama_pengguna}}</div>
              <div class="col-1 text-right">
                <span
                  class="cross-mark"
                  @click="konfirmasiHapusJabatan(pengguna.id_jabatan_pengguna,pengguna.nama_pengguna,pengguna.jabatan.nama_jabatan)"
                >&#10060;</span>
              </div>
            </div>
          </div>
          <!-- // * ################################################ -->
        </template>
      </div>
      <!-- // ? ############################################################################################### -->
    </div>
  </div>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
export default {
  data() {
    return {
      list_kbk: null,
      id_kbk: null,
      data_anggota_kbk: null,
      ketua_kbk: [],
      sekretaris_kbk: [],
      anggota_kbk: [],
      other: [],
      kumpulan_dosen: null,
      loading_page: true,
      loading_anggota_kbk: true,
      id_hapus_jabatan: null
    };
  },
  mounted() {
    this.getListKbk();
    this.getAllDosen();
  },
  methods: {
    getListKbk() {
      this.loading_page = true;
      Axios.get(`${config.apiUrl}/kbk-aktif`)
        .then(response => {
          if (response.data.data.length > 0) {
            this.list_kbk = response.data.data;
            this.id_kbk = this.list_kbk[0].id_kbk;
            this.ubahNamaKbk();
          } else {
            this.loading_page = false;
          }
        })
        .catch(response => {
          console.log(response);
        });
    },
    ubahNamaKbk() {
      this.list_kbk.forEach(element => {
        Object.assign(element, {
          nama_pendek: element.nama_kbk.replace(/[a-z]|\s+/g, "")
        });
      });
      this.loading_page = false;
      this.getAnggotaKbk();
    },
    getAllDosen() {
      Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered`)
        .then(response => {
          this.kumpulan_dosen = response.data;
        })
        .catch(response => {
          console.log(response);
        });
    },
    getAnggotaKbk() {
      this.loading_anggota_kbk = true;
      this.data_anggota_kbk = null;
      this.ketua_kbk = [];
      this.sekretaris_kbk = [];
      this.anggota_kbk = [];
      this.other = [];
      Axios.get(`${config.apiUrl}/jabatan-pengguna/kbk/${this.id_kbk}`).then(
        response => {
          if (response.data.data.length > 0) {
            this.data_anggota_kbk = response.data.data;
            this.ubahNamaAnggota();
          } else {
            this.loading_anggota_kbk = false;
          }
        }
      );
    },
    ubahNamaAnggota() {
      var nama_jabatan = "";
      this.data_anggota_kbk.forEach((element, index) => {
        // ! Nama Pengguna
        Axios.get(
          `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
            element.id_pengguna
        )
          .then(response => {
            if (response.data.length > 0) {
              Object.assign(element, { nama_pengguna: response.data[0].Name });
            }
            // ! Filter Aggota
            nama_jabatan = element.jabatan.nama_jabatan.toLowerCase();
            if (nama_jabatan.includes("ketua kbk")) {
              this.ketua_kbk.push(element);
            } else if (nama_jabatan.includes("sekretaris kbk")) {
              this.sekretaris_kbk.push(element);
            } else if (nama_jabatan.includes("kbk")) {
              this.anggota_kbk.push(element);
            } else {
              if (nama_jabatan.includes("koordinator kp")) {
                this.other.push(element);
              } else if (nama_jabatan.includes("tata usaha")) {
                this.other.push(element);
              }
            }
            if (index == this.data_anggota_kbk.length - 1) {
              this.loading_anggota_kbk = false;
            }
            // ! #######################################################################
          })
          .catch(response => {
            console.log(response);
          });

        // ! #######################################################################
      });
    },
    konfirmasiHapusJabatan(id_jabatan_pengguna, nama_pengguna, nama_jabatan) {
      this.$bvModal
        .msgBoxConfirm(
          `Hapus Jabatan ${nama_pengguna} Sebagai ${nama_jabatan}`,
          {
            title: "Hapus Jabatan",
            size: "sm",
            buttonSize: "sm",
            okVariant: "info",
            okTitle: "Ya",
            cancelTitle: "Tidak",
            cancelVarian: "info",
            footerClass: "p-1",
            hideHeaderClose: false,
            centered: true
          }
        )
        .then(value => {
          if (value) {
            this.hapusJabatan(id_jabatan_pengguna);
          }
        })
        .catch(err => {
          // An error occurred
        });
    },
    hapusJabatan(id_jabatan_pengguna) {
      // TODO : Belum di Uji Coba
      const formData = new FormData();
      formData.append("id_jabatan_pengguna", id_jabatan_pengguna);
      Axios.post(`${config.apiUrl}/jabatan-pengguna/delete`, formData, {
        headers: { "Content-Type": "multipart/form-data" }
      })
        .then(response => {
          this.getAnggotaKbk();
        })
        .catch(response => {
          console.log(response);
        });
    },
    ubahKbk(kbk) {
      this.id_kbk = kbk;
      this.getAnggotaKbk();
    }
  }
};
</script>
<style scoped>
.title-keanggotaan {
  font-size: 15px;
  font-weight: bold;
  margin: 10px 0px 5px 0px;
}
/* // * CSS Jabatan Pengguna */
.card-jabatan {
  font-size: 15px;
  color: rgb(19, 21, 0);
  width: 100%;
  background-color: rgb(243, 249, 215);
}
.card-jabatan:hover {
  background-color: rgb(228, 243, 188);
}
.cross-mark {
  cursor: pointer;
}

/* // * CSS Button Radio */
.btn-select {
  padding: 0px 0px 10px 0px;
}
.btn-radio {
  font-size: 15px;
  border-radius: 0px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
}

.btn-radio:focus {
  box-shadow: none;
}

.btn-radio:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}

.btn-radio-disable {
  font-size: 15px;
  border-radius: 0px;
  color: rgb(0, 0, 0);
  background-color: rgb(233, 236, 239);
  border: none;
  text-align: center;
}

.btn-radio-disable:hover {
  color: rgb(0, 0, 0);
  background-color: rgb(212, 216, 219);
  cursor: pointer;
}

/* // * CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>