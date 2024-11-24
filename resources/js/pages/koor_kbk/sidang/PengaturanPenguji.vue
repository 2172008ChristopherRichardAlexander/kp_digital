<template>
  <b-container>
    <div>
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Menentukan Penguji Sidang</h3>
        </div>
      </div>
      <!-- // ? Pengaturan Penguji -->
      <div class="form-penguji">
        <b-table-simple borderless responsive class="pengaturan-penguji">
          <b-tbody>
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Penguji Satu</b-td>
              <b-td class="sub-bab-item">
                <b-form-checkbox
                  id="checkbox-penguji-sidang-dua"
                  v-model="check_penguji_sidang_dua"
                  name="checkbox-penguji-sidang-dua"
                  value="true"
                  unchecked-value="false"
                  inline
                  >Penguji Dua</b-form-checkbox
                >
              </b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <!-- // ? Penguji Sidang Satu -->
              <b-td class="input-form-select-box">
                <div class="text-center my-2" v-if="loading_dosen">
                  <ring-loader
                    class="loading-page"
                    color="#20a506"
                    :size="50"
                  />
                  <strong class="loading-text">Loading...</strong>
                </div>
                <b-form-select
                  class="input-form-select-box-item"
                  v-model="id_penguji_sidang"
                  size="sm"
                  v-else
                >
                  <template v-slot:first>
                    <option :value="null" disabled>
                      Pilih Penguji Sidang Satu
                    </option>
                  </template>
                  <template v-if="kumpulan_dosen">
                    <option
                      :value="dosen.UserId"
                      v-for="dosen in kumpulan_dosen"
                      :key="dosen.UserId"
                    >
                      {{ dosen.Name }}
                    </option>
                  </template>
                </b-form-select>
              </b-td>
              <!-- // ? Penguji Sidang Dua -->
              <b-td class="input-form-select-box">
                <div class="text-center my-2" v-if="loading_dosen">
                  <ring-loader
                    class="loading-page"
                    color="#20a506"
                    :size="50"
                  />
                  <strong class="loading-text">Loading...</strong>
                </div>
                <b-form-select
                  class="input-form-select-box-item"
                  v-model="id_penguji_sidang_dua"
                  size="sm"
                  :disabled="check_penguji_sidang_dua == 'false'"
                  v-else
                >
                  <template v-slot:first>
                    <option :value="null" disabled>
                      Pilih Penguji Sidang Dua
                    </option>
                  </template>
                  <template v-if="kumpulan_dosen">
                    <option
                      :value="dosen.UserId"
                      v-for="dosen in kumpulan_dosen"
                      :key="dosen.UserId"
                    >
                      {{ dosen.Name }}
                    </option>
                  </template>
                </b-form-select>
              </b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
        <!-- // ? Tombol Simpan -->
        <div class="btn-select text-right">
          <b-button class="btn-form" @click="simpanPengaturanPenguji"
            >Simpan</b-button
          >
        </div>
        <!-- // ? Detail Topik -->

        <div v-if="loading_sidang" class="text-center my-2">
          <ring-loader class="loading-page" color="#20a506" :size="50" />
          <strong class="loading-text">Loading...</strong>
        </div>
        <b-table-simple borderless responsive v-else>
          <b-thead>
            <b-th colspan="2">
              <h4 class="judul-topik text-center">
                {{ sidang.topik.judul_topik }}
              </h4>
            </b-th>
          </b-thead>
          <b-tbody>
            <!-- // * Latar Belakang -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Latar Belakang</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{
                sidang.topik.latar_belakang
              }}</b-td>
            </b-tr>
            <!-- // * Rumusan Masalah -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Rumusan Masalah</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{
                sidang.topik.rumusan_masalah
              }}</b-td>
            </b-tr>
            <!-- // * Tujuan Pembahasan -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Tujuan Pembahasan</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{
                sidang.topik.tujuan_pembahasan
              }}</b-td>
            </b-tr>
            <!-- // * Ruang Lingkup -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Ruang Lingkup</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{
                sidang.topik.ruang_lingkup
              }}</b-td>
            </b-tr>
            <!-- // * Sumber Data -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Sumber Data</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{
                sidang.topik.sumber_data
              }}</b-td>
            </b-tr>
            <!-- // * Sistematika Penyajian -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Sistematika Penyajian</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{
                sidang.topik.sistematika_penyajian
              }}</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
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
      sidang: null,
      id_penguji_sidang: null,
      id_penguji_sidang_dua: null,
      loading_sidang: true,
      kumpulan_dosen: [],
      loading_dosen: true,
      check_penguji_sidang_dua: "false",
    };
  },
  mounted() {
    this.getSidang();
    this.getAllDosen();
  },
  methods: {
    cekJabatan(jabatan) {
      return this.$store.getters.jabatan.includes(jabatan);
    },
    getSidang() {
      this.loading_sidang = true;
      Axios.get(
        `${config.apiUrl}/sidang/${this.acakAcak(this.$route.params.id, 1)}`
      )
        .then((response) => {
          if (response.data.data) {
            this.sidang = response.data.data;
            this.id_penguji_sidang = this.sidang.id_penguji_sidang;
            if (this.sidang.id_penguji_sidang_dua) {
              this.id_penguji_sidang_dua = this.sidang.id_penguji_sidang_dua;
              this.check_penguji_sidang_dua = true;
            }

            this.changeDataSidang();
          } else {
            this.sidang = null;
          }
        })
        .catch((response) => {
          console.log(response);
        });
    },
    changeDataSidang() {
      Axios.get(
        `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
          this.sidang.topik.id_pengaju
      ).then((response) => {
        this.sidang.nama = response.data[0].Name;
        this.loading_sidang = false;
      });
    },
    getAllDosen() {
      this.loading_dosen = true;
      Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered`)
        .then((response) => {
          this.kumpulan_dosen = response.data;
          this.loading_dosen = false;
        })
        .catch((response) => {
          console.log(response);
        });
    },
    simpanPengaturanPenguji() {
      this.checkForm();
      Axios.put(`${config.apiKbkUrl}/sidang/penguji`, {
        id_sidang: this.sidang.id_sidang,
        id_penguji_sidang: this.id_penguji_sidang,
        id_penguji_sidang_dua: this.id_penguji_sidang_dua,
      })
        .then((response) => {
          if (this.cekJabatan("Koordinator KP")) {
            this.$router.push("/sidang/penguji");
          } else if (this.cekJabatan("Koordinator KBK")) {
            this.$router.push("/sidang/pengaturan-penguji");
          }
        })
        .catch((response) => {
          console.log(response);
        });
    },
    checkForm() {
      if (this.check_penguji_sidang_dua === "false") {
        this.id_penguji_sidang_dua = "";
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
.form-penguji {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

/* // * Detail Topik */
.judul-topik {
  font-weight: bold;
  font-size: 23px;
}
.sub-bab-item {
  font-weight: bold;
  font-size: 18px;
}
.sub-bab-isi-item {
  text-align: justify;
  font-size: 15px;
  padding-top: 0px;
  white-space: pre-wrap;
}

/* // * Css Button */
.btn-select {
  padding-right: 10px;
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

.btn-form:hover {
  font-size: 15px;
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}

.btn-form:disabled,
.btn-form:disabled:hover {
  background-color: rgb(198, 205, 180);
  color: rgb(28, 31, 1);
  cursor: default;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>