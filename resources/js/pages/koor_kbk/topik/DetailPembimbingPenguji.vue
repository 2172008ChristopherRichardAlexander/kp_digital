<template>
  <b-container>
    <div class="form-detail-topik">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Detail Topik Kerja Praktek</h3>
          <h4 class="keterangan-website my-2">
            {{ topik.nama }} - {{ topik.id_pengaju }}
          </h4>
        </div>
      </div>
      <div class="detail-topik">
        <!-- // * Pembimbing Penguji -->
        <div class="form-pembimbing-penguji">
          <div v-if="loading_dosen" class="text-center my-2">
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
          <b-table-simple
            borderless
            responsive
            class="pembimbing-penguji"
            v-else
          >
            <b-tbody>
              <b-tr class="sub-bab">
                <b-td class="sub-bab-item" style="width: 48%"
                  >Pembimbing Utama</b-td
                >
                <b-td class="sub-bab-item">
                  <b-form-checkbox
                    id="checkbox-pembimbing-lapangan"
                    v-model="check_pembimbing_lapangan"
                    name="checkbox-pembimbing-lapangan"
                    value="true"
                    unchecked-value="false"
                    inline
                    >Pembimbing Lapangan Instansi</b-form-checkbox
                  >
                </b-td>
              </b-tr>
              <b-tr class="sub-bab-isi">
                <!-- // ? Pembimbing Utama -->
                <b-td class="input-form-select-box">
                  <b-form-select
                    class="input-form-select-box-item"
                    v-model="id_pembimbing"
                    size="sm"
                  >
                    <template v-slot:first>
                      <option :value="null" disabled>
                        Pilih Pembimbing Utama
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
                <!-- // ? Pembimbing Lapangan -->
                <b-td class="input-form-select-box">
                  <b-form-select
                    class="input-form-select-box-item"
                    v-model="id_pembimbing_lapangan"
                    size="sm"
                    :disabled="check_pembimbing_lapangan == 'true'"
                  >
                    <template v-slot:first>
                      <option :value="null" disabled>
                        Pilih Pembimbing Lapangan
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
                  <b-form-select
                    class="input-form-select-box-item"
                    v-model="id_penguji_sidang"
                    size="sm"
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
                  <b-form-select
                    class="input-form-select-box-item"
                    v-model="id_penguji_sidang_dua"
                    size="sm"
                    :disabled="check_penguji_sidang_dua == 'false'"
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
          <div class="catatan">
            <ul>
              <li>
                Penguji Satu dan Penguji Dua hanya akan menjadi default data
                pada saat Mahasiswa mengajukan Sidang.
              </li>
              <li>
                Jika ingin Update Penguji silahkan ke menu Pengaturan Penguji di
                menu Sidang.
              </li>
            </ul>
          </div>
        </div>
        <div class="btn-select text-right">
          <b-button class="btn-form" @click="simpanPembimbingPenguji"
            >Simpan</b-button
          >
        </div>
        <!-- // * Detail Topik -->

        <div v-if="loading_topik" class="text-center my-2">
          <ring-loader class="loading-page" color="#20a506" :size="50" />
          <strong class="loading-text">Loading...</strong>
        </div>
        <b-table-simple borderless responsive v-else>
          <b-thead>
            <b-th colspan="2">
              <h4 class="judul-topik text-center">{{ topik.judul_topik }}</h4>
            </b-th>
          </b-thead>
          <b-tbody>
            <!-- // * Latar Belakang -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Latar Belakang</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{ topik.latar_belakang }}</b-td>
            </b-tr>
            <!-- // * Rumusan Masalah -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Rumusan Masalah</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{ topik.rumusan_masalah }}</b-td>
            </b-tr>
            <!-- // * Tujuan Pembahasan -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Tujuan Pembahasan</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{
                topik.tujuan_pembahasan
              }}</b-td>
            </b-tr>
            <!-- // * Ruang Lingkup -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Ruang Lingkup</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{ topik.ruang_lingkup }}</b-td>
            </b-tr>
            <!-- // * Sumber Data -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Sumber Data</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{ topik.sumber_data }}</b-td>
            </b-tr>
            <!-- // * Sistematika Penyajian -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Sistematika Penyajian</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{
                topik.sistematika_penyajian
              }}</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>

        <!-- // ! Komentar -->
        <!-- <div class="form-komentar-topik">
        <div class="sub-bab-item">Komentar</div>
        <ul class="list-unstyled">
          <div class="my-3" v-for="komentar in kumpulan_komentar" :key="komentar.id_komentar">
            <Komentar :komentar="komentar" />
          </div>
        </ul>
        <div class="sub-bab-isi-item">
          <b-form-textarea
            id="textarea-no-resize"
            placeholder="Komentar (Opsional)"
            rows="3"
            no-resize
            v-model="text_komentar"
          ></b-form-textarea>
        </div>
        </div>-->
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
      topik: {},
      //   kumpulan_komentar: {},
      //   text_komentar: "",
      fileUrl: "/public/pengajuan_topik/",
      id_pengguna: this.$store.getters.pengguna.UserId,
      id_pembimbing: null,
      id_pembimbing_lapangan: null,
      id_penguji_sidang: null,
      id_penguji_sidang_dua: null,
      check_pembimbing_lapangan: "false",
      check_penguji_sidang_dua: "false",
      kumpulan_dosen: {},
      loading_topik: true,
      loading_dosen: true,
    };
  },
  mounted() {
    this.getTopik();
  },
  methods: {
    cekJabatan(jabatan) {
      return this.$store.getters.jabatan.includes(jabatan);
    },
    getTopik() {
      Axios.get(`${config.apiKbkUrl}/topik/${this.$route.params.id}`)
        .then((response) => {
          this.topik = response.data.data;
          this.changeData();
          // this.getKomentar();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    changeData() {
      this.id_pembimbing = this.topik.id_pembimbing;
      if (this.topik.status_topik === 5) {
        this.check_pembimbing_lapangan = "false";
        this.id_pembimbing_lapangan = this.topik.id_pembimbing_lapangan;
      }
      this.id_penguji_sidang = this.topik.id_penguji_sidang;
      if (this.topik.id_penguji_sidang_dua !== null) {
        this.check_penguji_sidang_dua = "true";
        this.id_penguji_sidang_dua = this.topik.id_penguji_sidang_dua;
      }
      this.loading_topik = false;
      this.getAllDosen();
    },
    // getKomentar() {
    //   Axios.get(`${config.apiUrl}/komentar_topik/${this.topik.id_topik}`)
    //     .then(response => {
    //       this.kumpulan_komentar = response.data.data;
    //     })
    //     .catch(response => {
    //       console.log(response);
    //     });
    // },
    getAllDosen() {
      Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered`)
        .then((response) => {
          this.kumpulan_dosen = response.data;
          this.loading_dosen = false;
        })
        .catch((response) => {
          console.log(response);
        });
    },
    checkForm() {
      if (this.check_pembimbing_lapangan === "true") {
        this.id_pembimbing_lapangan = "";
      }
      if (this.check_penguji_sidang_dua === "false") {
        this.id_penguji_sidang_dua = "";
      }
    },
    simpanPembimbingPenguji() {
      this.checkForm();
      this.loading = true;
      Axios.put(
        `${config.apiKbkUrl}/topik-pembimbing-penguji`,
        {
          id_topik: this.topik.id_topik,
          id_pembimbing: this.id_pembimbing,
          id_pembimbing_lapangan: this.id_pembimbing_lapangan,
          id_penguji_sidang: this.id_penguji_sidang,
          id_penguji_sidang_dua: this.id_penguji_sidang_dua,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          if (this.cekJabatan("Koordinator KP")) {
            this.$router.push("/topik/pembimbing");
          } else if (this.cekJabatan("Koordinator KBK")) {
            this.$router.push("/topik/pengaturan-pembimbing");
          }
        })
        .catch((response) => {
          console.log(response);
        });
      // // Hide the modal manually
      // this.$nextTick(() => {
      //   this.$refs.modal.hide();
      // });
    },
  },
};
</script>
<style scoped>
.detail-topik {
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
/* // * Pembimbing Penguji */
.form-pembimbing-penguji {
  padding-left: 0px;
}
/* // * CSS Input Select Box Item */
/* .input-form-select-box-item {
} */

/* // * Button Select Simpan Pembimbing Penguji*/
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

.catatan {
  padding-left: 30px;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>