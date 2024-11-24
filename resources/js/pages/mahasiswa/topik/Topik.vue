<template>
  <b-container>
    <div class="form-detail-topik">
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Detail Topik Kerja Praktek</h3>
          <h4 class="keterangan-website my-2">
            {{ topik.nama }} - {{ topik.id_pengaju }}
          </h4>
        </div>
      </div>
      <div class="detail-topik">
        <div class="form-komentar-topik">
          <h4 class="my-1 text-left judul">Komentar</h4>
          <div v-if="loading_komentar" class="text-center">
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
          <div v-else>
            <div v-if="kumpulan_komentar">
              <div v-if="kumpulan_komentar.length == 0">Tidak Ada Komentar</div>
              <div v-else>
                <ul class="list-unstyled">
                  <div
                    class="my-3"
                    v-for="komentar in kumpulan_komentar"
                    :key="komentar.id_komentar"
                  >
                    <Komentar :komentar="komentar" />
                  </div>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="row revisi-topik">
          <div class="col text-left">
            <h4 class="my-1 text-left judul">Detail Topik</h4>
          </div>
          <div class="col text-right">
            <router-link
              :to="`/topik/revisi/${topik.slug_topik}`"
              class="btn btn-form"
              v-if="
                topik.status_topik === 'topik dapat di Revisi' ||
                topik.status_topik == 6
              "
              >Revisi Topik</router-link
            >
            <div class="btn btn-form-disabled disabled" v-else>
              Revisi Topik
            </div>
          </div>
        </div>
        <div v-if="loading_topik" class="text-center">
          <ring-loader class="loading-page" color="#20a506" :size="50" />
          <strong class="loading-text">Loading...</strong>
        </div>
        <div v-else>
          <div class="row">
            <div class="col">
              <div>
                <h5 class="my-2 text-center" style="font-weight: bold">
                  {{ topik.judul_topik }}
                </h5>
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
              <div class="col label-form">Kelengkapan Topik</div>
            </div>
            <div class="row">
              <div class="col isi-form" v-viewer>
                <b-row>
                  <b-col>
                    <b-img
                      :src="fileUrl + authUser.UserId + '/dkbs/' + topik.dkbs"
                      alt="DKBS"
                      class="gambar"
                    />
                    <div class="gambar-caption">DKBS</div>
                  </b-col>
                  <b-col>
                    <b-img
                      :src="
                        fileUrl +
                        authUser.UserId +
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
                          authUser.UserId +
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
                          authUser.UserId +
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
        </div>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import Komentar from "../../../components/Komentar";
export default {
  components: {
    Komentar,
  },
  data() {
    return {
      topik: {},
      kumpulan_komentar: null,
      fileUrl: "/public/pengajuan_topik/",
      loading_topik: true,
      loading_komentar: true,
    };
  },
  mounted() {
    this.getTopik();
  },
  methods: {
    getTopik() {
      Axios.get(`${config.apiMahasiswaUrl}/topik/${this.$route.params.id}`)
        .then((response) => {
          this.topik = response.data.data;
          this.loading_topik = false;
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
          this.loading_komentar = false;
        })
        .catch((response) => {
          console.log(response);
        });
    },
  },
  computed: {
    authUser() {
      // return this.$root.auth;
      return this.$store.getters.pengguna;
    },
  },
};
</script>

<style scoped>
/* // * Judul Topik */
.judul {
  font-weight: bold;
}
/* .keterangan-website {
  margin-left: 0px;
  padding-left: 0px;
} */
.detail-topik {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 5px 20px 10px 20px;
  margin: 0px 10px 0px 0px;
  background-color: rgb(255, 255, 255);
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
  left: 20;
  bottom: 0;
  width: 100%;
  background: rgba(65, 72, 2, 0.5);
  color: rgb(255, 255, 255);
  text-align: center;
  font-size: 15px;
  padding: 5px;
}

/* // * Css Button */

.btn-form {
  padding: 8px 20px;
  font-size: 15px;
  border-radius: 5px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
  width: 195px;
}

.btn-form:disabled,
.btn-form:disabled:hover {
  background-color: rgb(198, 205, 180);
  color: rgb(28, 31, 1);
  cursor: default;
}
.btn-form-disabled,
.btn-form-disabled:hover {
  padding: 8px 20px;
  background-color: rgb(17, 122, 139);
  color: rgb(255, 255, 255);
  cursor: default;
  width: 195px;
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