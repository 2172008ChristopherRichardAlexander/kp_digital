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
      <div v-if="loading_page">
        <ring-loader
          class="loading-page"
          color="#bada55"
          :loading="loading_page"
          :size="150"
        />
      </div>
      <div v-else>
        <div class="detail-topik">
          <!-- // * Detail Topik -->
          <b-table-simple borderless responsive>
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
                <b-td class="sub-bab-isi-item">{{
                  topik.rumusan_masalah
                }}</b-td>
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
          <!-- // * Komentar -->
          <div class="form-komentar-topik">
            <div class="sub-bab-item">Komentar</div>
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
      kumpulan_komentar: {},
      text_komentar: "",
      fileUrl: "/public/pengajuan_topik/",
      id_pengguna: this.$store.getters.pengguna.UserId,
      loading: false,
      loading_page: true,
    };
  },
  mounted() {
    this.getTopik();
  },
  methods: {
    getTopik() {
      Axios.get(`${config.apiDosenUrl}/topik/${this.$route.params.id}`)
        .then((response) => {
          this.topik = response.data.data;
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
        })
        .catch((response) => {
          console.log(response);
        });
    },
    terimaTopik() {
      this.loading = true;
      var status = 2;
      if (this.topik.status_topik && this.topik.status_topik != 3) {
        status = this.topik.status_topik;
      }
      Axios.put(
        `${config.apiDosenUrl}/topik`,
        {
          id_topik: this.topik.id_topik,
          status_persetujuan_dosen: 1,
          status_topik: status,
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
    tolakTopik() {
      this.loading = true;
      Axios.put(
        `${config.apiDosenUrl}/topik`,
        {
          id_topik: this.topik.id_topik,
          status_persetujuan_dosen: 0,
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
        `${config.apiDosenUrl}/topik`,
        {
          id_topik: this.topik.id_topik,
          status_persetujuan_dosen: 3,
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
      this.$router.push("/topik/persetujuan");
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
/* // * Komentar */
.form-komentar-topik {
  padding-left: 15px;
  margin-bottom: 10px;
}
/* // * Button Select Terima atau Tolak */
.btn-select {
  padding-left: 15px;
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

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
</style>