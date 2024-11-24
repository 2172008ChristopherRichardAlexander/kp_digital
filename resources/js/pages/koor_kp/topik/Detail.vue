<template>
  <b-container>
    <div class="form-detail-topik">
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Detail Topik Kerja Praktek</h3>
          <h4 class="keterangan-website my-2" v-if="!loading_page">
            {{ topik.nama }} - {{ topik.id_pengaju }}
          </h4>
        </div>
      </div>
      <div v-if="loading_page">
        <ring-loader
          class="loading-page"
          color="#bada55"
          :loading="loading_page"
          :size="150"
        />
      </div>
      <div v-else class="detail-topik">
        <div class="row">
          <div class="col">
            <div>
              <h4 class="judul-topik text-center">{{ topik.judul_topik }}</h4>
            </div>
          </div>
        </div>
        <div>
          <div class="row">
            <div class="col label-form">Latar Belakang</div>
          </div>
          <div class="row">
            <div class="col isi-form">{{ topik.latar_belakang }}</div>
          </div>
        </div>

        <div>
          <div class="row">
            <div class="col label-form">Rumusan Masalah</div>
          </div>
          <div class="row">
            <div class="col isi-form">{{ topik.rumusan_masalah }}</div>
          </div>
        </div>

        <div>
          <div class="row">
            <div class="col label-form">Tujuan Pembahasan</div>
          </div>
          <div class="row">
            <div class="col isi-form">{{ topik.tujuan_pembahasan }}</div>
          </div>
        </div>

        <div>
          <div class="row">
            <div class="col label-form">Ruang Lingkup</div>
          </div>
          <div class="row">
            <div class="col isi-form">{{ topik.ruang_lingkup }}</div>
          </div>
        </div>

        <div>
          <div class="row">
            <div class="col label-form">Sumber Data</div>
          </div>
          <div class="row">
            <div class="col isi-form">{{ topik.sumber_data }}</div>
          </div>
        </div>

        <div>
          <div class="row">
            <div class="col label-form">Sistematika Penyajian</div>
          </div>
          <div class="row">
            <div class="col isi-form">{{ topik.sistematika_penyajian }}</div>
          </div>
        </div>

        <div>
          <div class="row">
            <div class="col label-form">KBK</div>
          </div>
          <div class="row">
            <div class="col isi-form">{{ topik.kbk.nama_kbk }}</div>
          </div>
        </div>

        <div>
          <div class="row">
            <div class="col label-form">Kelengkapan Topik</div>
          </div>
          <div class="row">
            <div class="col isi-form" v-viewer>
              <b-row>
                <b-col>
                  <b-img
                    :src="fileUrl + topik.id_pengaju + '/dkbs/' + topik.dkbs"
                    alt="DKBS"
                    class="gambar"
                  />
                  <div class="gambar-caption">DKBS</div>
                </b-col>
                <b-col>
                  <b-img
                    :src="
                      fileUrl +
                      topik.id_pengaju +
                      '/transkrip_nilai/' +
                      topik.transkrip_nilai
                    "
                    alt="Transkrip Nilai"
                    class="gambar"
                  />
                  <div class="gambar-caption">Transkrip Nilai</div>
                </b-col>
                <b-col v-if="topik.form_persetujuan_instansi">
                  <div>
                    <b-img
                      :src="
                        fileUrl +
                        topik.id_pengaju +
                        '/form_persetujuan_instansi/' +
                        topik.form_persetujuan_instansi
                      "
                      alt="Persetujuan Instansi"
                      class="gambar"
                    />
                    <div class="gambar-caption">Persetujuan Instansi</div>
                  </div>
                </b-col>
                <b-col v-if="topik.lampiran_gambar">
                  <div>
                    <b-img
                      :src="
                        fileUrl +
                        topik.id_pengaju +
                        '/lampiran_gambar/' +
                        topik.lampiran_gambar
                      "
                      alt="Lampiran Gambar"
                      class="gambar"
                    />
                    <div class="gambar-caption">Lampiran Gambar</div>
                  </div>
                </b-col>
              </b-row>
            </div>
          </div>
        </div>
        <div class="form-komentar-topik">
          <div class="label-form">Komentar</div>
          <ul class="list-unstyled">
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
      loading: false,
      loading_page: true,
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
      Axios.get(`${config.apiKoorKpUrl}/topik/${this.$route.params.id}`)
        .then((response) => {
          this.topik = response.data.data;
          this.id_kbk = this.topik.kbk.id_kbk;
          this.getKomentar();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getKomentar() {
      Axios.get(`${config.apiUrl}/komentar_topik/${this.topik.id_topik}`)
        .then((response) => {
          this.kumpulan_komentar = response.data.data;
          this.loading_page = false;
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
          this.getTopik();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    terimaTopik() {
      this.loading = true;
      Axios.put(
        `${config.apiKoorKpUrl}/topik`,
        {
          id_topik: this.topik.id_topik,
          status_topik: 1,
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
      // location.reload();
    },
    tolakTopik() {
      this.loading = true;
      Axios.put(
        `${config.apiKoorKpUrl}/topik`,
        {
          id_topik: this.topik.id_topik,
          status_topik: 0,
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
    },
    revisiTopik() {
      this.loading = true;
      Axios.put(
        `${config.apiKoorKpUrl}/topik`,
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
      this.$router.push("/topik/kelengkapan-dokumen");
    },
  },
  computed: {
    authUser() {
      return this.$root.auth;
    },
  },
};
</script>

<style scoped>
.detail-topik {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 5px 30px 10px 20px;
  margin: 0px 10px 0px 0px;
  background-color: rgb(255, 255, 255);
}
.keterangan-website {
  margin-left: 0px;
  padding-left: 0px;
}
.judul-topik {
  font-weight: bold;
  font-size: 23px;
}
.label-form {
  font-weight: bold;
  font-size: 18px;
}
.isi-form {
  text-align: justify;
  font-size: 15px;
  padding-top: 0px;
  white-space: pre-wrap;
}

.gambar {
  /* width: 100%;
  height: 100%; */
  display: block;
  max-width: 250px;
  max-height: 250px;
  margin: auto;
  height: auto;
}
.gambar:hover {
  cursor: pointer;
}
.gambar-caption {
  position: absolute;
  left: 10;
  bottom: 0;
  width: 100%;
  background: rgba(65, 72, 2, 0.5);
  color: rgb(255, 255, 255);
  text-align: center;
  font-size: 15px;
  padding: 5px;
}
/* .btn-select {
  margin: 10px 0px 10px 20px;
} */
.btn-form {
  padding: 0.38vw 1.4vw;
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
/* // * Komentar */
.form-komentar-topik {
  margin-bottom: 10px;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
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
</style>