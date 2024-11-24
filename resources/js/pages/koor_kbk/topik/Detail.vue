<template>
  <b-container>
    <div class="form-detail-topik">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Detail Topik Kerja Praktek</h3>
          <h4 class="keterangan-website my-2" v-if="!loading_page">
            {{ topik.nama }} - {{ topik.id_pengaju }}
          </h4>
        </div>
      </div>
      <div class="detail-topik">
        <!-- // * Detail Topik -->
        <div v-if="loading_page" class="text-center my-2">
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
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">KBK</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{ topik.kbk.nama_kbk }}</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>

        <!-- // * Komentar -->
        <div class="form-komentar-topik">
          <div class="sub-bab-item">Komentar</div>
          <div class="text-center my-2" v-if="loading_komentar">
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
          <ul class="list-unstyled" v-else>
            <div
              class="my-3"
              v-for="komentar in kumpulan_komentar"
              :key="komentar.id_komentar"
            >
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
        </div>

        <!-- // * Pembimbing Penguji -->
        <div v-if="loading_dosen">
          <div class="text-center my-2">
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
        </div>
        <div class="form-pembimbing-penguji" v-else>
          <b-table-simple borderless responsive class="pembimbing-penguji">
            <b-tbody>
              <b-tr class="sub-bab">
                <b-td class="sub-bab-item">Pembimbing Utama</b-td>
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
        </div>
        <div class="catatan">
          <ul>
            <li>
              Penguji Satu dan Penguji Dua hanya akan menjadi default data pada
              saat Mahasiswa mengajukan Sidang.
            </li>
            <li>
              Jika ingin Update Penguji silahkan ke menu Pengaturan Penguji di
              menu Sidang.
            </li>
          </ul>
        </div>
        <div class="btn-select">
          <b-button class="btn-form" @click="terimaTopik">Terima</b-button>
          <b-button class="btn-form" @click="tolakTopik">Tolak</b-button>
          <b-button class="btn-form" @click="revisiTopik">Revisi</b-button>
          <b-button class="btn-form" v-b-modal.modal-migrasi-kbk
            >Migrasi KBK</b-button
          >
        </div>
      </div>
      <!-- // ? Modal Migrasi KBK -->
      <b-modal
        id="modal-migrasi-kbk"
        size="sm"
        centered
        title="Migrasi KBK"
        @ok="updateKBK"
      >
        <div>
          <div class="row form-pengajuan-item">
            <div class="col input-form-select-box">
              <b-form-select
                class="input-form-select-box-item"
                v-model.trim="$v.id_kbk.$model"
                size="sm"
              >
                <template v-slot:first>
                  <option :value="null" disabled>Pilih KBK</option>
                </template>
                <template v-if="kumpulan_kbk">
                  <option
                    :value="kbk.id_kbk"
                    v-for="kbk in kumpulan_kbk"
                    :key="kbk.id_kbk"
                  >
                    {{ kbk.nama_kbk }}
                  </option>
                </template>
              </b-form-select>
            </div>
            <div class="col-1 feedback-validasi">
              <span class="feedback-validasi-false" v-if="!$v.id_kbk.required"
                >&#10006;</span
              >
              <span class="feedback-validasi-true" v-else>&#10004;</span>
            </div>
          </div>
        </div>
        <template v-slot:modal-footer="{ cancel }">
          <!-- Emulate built in modal footer ok and cancel button actions -->
          <b-button size="sm" class="btn-form" @click="cancel">Batal</b-button>
          <!-- <b-button size="sm" class="btn-form" @click="ok()">Update</b-button> -->
          <b-button size="sm" class="btn-form" @click="updateKBK"
            >Migrasi</b-button
          >
        </template>
      </b-modal>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import Komentar from "../../../components/Komentar";
import { required, requiredIf } from "vuelidate/lib/validators";
export default {
  components: {
    Komentar,
  },
  data() {
    return {
      topik: {},
      kumpulan_komentar: {},
      text_komentar: "",
      fileUrl: "/public/pengajuan_topik/",
      id_pengguna: this.$store.getters.pengguna.UserId,
      id_pembimbing: null,
      id_pembimbing_lapangan: null,
      id_penguji_sidang: null,
      id_penguji_sidang_dua: null,
      check_pembimbing_lapangan: "false",
      check_penguji_sidang_dua: "false",
      kumpulan_dosen: {},
      loading: false,
      loading_page: true,
      loading_komentar: true,
      loading_dosen: true,
      id_kbk: null,
      kumpulan_kbk: [],
    };
  },
  validations: {
    id_kbk: {
      required,
    },
  },
  mounted() {
    this.getTopik();
  },
  methods: {
    getTopik() {
      Axios.get(`${config.apiKbkUrl}/topik/${this.$route.params.id}`)
        .then((response) => {
          this.topik = response.data.data;
          this.id_kbk = this.topik.kbk.id_kbk;
          this.changeData();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    changeData() {
      this.id_pembimbing = this.topik.id_pembimbing;
      if (this.topik.status_topik === 1) {
        this.check_pembimbing_lapangan = "false";
        this.id_pembimbing_lapangan = this.topik.id_pembimbing_lapangan;
      }
      this.loading_page = false;
      this.getKomentar();
    },
    getKomentar() {
      Axios.get(`${config.apiUrl}/komentar_topik/${this.topik.id_topik}`)
        .then((response) => {
          this.kumpulan_komentar = response.data.data;
          this.loading_komentar = false;
          this.getAllDosen();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getAllDosen() {
      Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered`)
        .then((response) => {
          this.kumpulan_dosen = response.data;
          this.loading_dosen = false;
          this.getKbk();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getKbk() {
      Axios.get(`${config.apiUrl}/kbk`)
        .then((response) => {
          this.kumpulan_kbk = response.data.data;
        })
        .catch((response) => {
          console.log(response);
        });
    },
    updateKBK() {
      Axios.put(
        `${config.apiKoorKpUrl}/topik-update-kbk`,
        {
          id_topik: this.topik.id_topik,
          id_kbk: this.id_kbk,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.$bvModal.hide("modal-migrasi-kbk");
          this.$router.push("/topik/kelayakan-topik");
        })
        .catch((response) => {
          console.log(response);
        });
    },
    checkForm() {
      if (this.check_pembimbing_lapangan === "true") {
        this.id_pembimbing_lapangan = null;
      }
      if (this.check_penguji_sidang_dua === "false") {
        this.id_penguji_sidang_dua = null;
      }
    },
    terimaTopik() {
      this.checkForm();
      this.loading = true;
      Axios.put(
        `${config.apiKbkUrl}/topik`,
        {
          id_topik: this.topik.id_topik,
          status_topik: 5,
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
          this.postKomentar();
        })
        .catch((response) => {
          console.log(response);
        });
      // Hide the modal manually
      this.$nextTick(() => {
        this.$refs.modal.hide();
      });
    },
    tolakTopik() {
      this.loading = true;
      Axios.put(
        `${config.apiKbkUrl}/topik`,
        {
          id_topik: this.topik.id_topik,
          status_topik: 4,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.postKomentar();
        })
        .catch((response) => {
          console.log(response);
        });
      // Hide the modal manually
      this.$nextTick(() => {
        this.$refs.modal.hide();
      });
    },
    revisiTopik() {
      this.loading = true;
      Axios.put(
        `${config.apiKbkUrl}/topik`,
        {
          id_topik: this.topik.id_topik,
          status_topik: 6,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.postKomentar();
        })
        .catch((response) => {
          console.log(response);
        });
      // Hide the modal manually
      this.$nextTick(() => {
        this.$refs.modal.hide();
      });
    },
    postKomentar() {
      if (this.text_komentar != "" && this.text_komentar.length !== 0) {
        const formData = new FormData();
        formData.append("isi_komentar", this.text_komentar);
        formData.append("id_topik", this.topik.id_topik);
        formData.append("id_pengguna", this.id_pengguna);
        Axios.post(`${config.apiUrl}/komentar`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
          .then((response) => {
            this.loading = false;
          })
          .catch((response) => {
            console.log(response);
          });
      }
      this.$router.push("/topik/kelayakan-topik");
    },
  },
};
</script>
<style scoped>
.detail-topik {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 5px 30px 10px 10px;
  margin: 0px 10px 0px 0px;
  background-color: rgb(255, 255, 255);
}
.keterangan-website {
  margin-left: 0px;
  padding-left: 0px;
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
/* // * Komentar */
.form-komentar-topik {
  padding-left: 10px;
  margin-bottom: 10px;
}
/* // * Button Select Terima atau Tolak atau Revisi*/
.btn-select {
  padding-left: 10px;
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

/* // ! CSS Validasi Form */
.feedback-validasi {
  padding-left: 0px;
  text-align: left;
  font-size: 20px;
}
.feedback-validasi-false {
  color: red;
}
.feedback-validasi-true {
  color: green;
}
.catatan {
  padding-left: 30px;
}
</style>