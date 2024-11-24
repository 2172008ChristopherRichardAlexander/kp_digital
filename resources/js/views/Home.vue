<template>
  <b-container>
    <div>
      <h1 class="text-center my-5">Welcome to Web KP Digital</h1>
      <div class="koordinator-kp" v-if="cekJabatan('Koordinator KP')">
        <b-row>
          <b-col>
            <div class="stepper-progres-kp">
              <h5 class="my-2 judul">Tahap Pengajuan Topik</h5>
              <div class="text-center my-2" v-if="loading_topik">
                <ring-loader class="loading-page" color="#20a506" :size="50" />
                <strong class="loading-text">Loading...</strong>
              </div>
              <div class="pengajuan-topik" v-else>
                <Stepper
                  v-for="step in steps_pengajuan_topik"
                  :key="step.id_step"
                  :detail="step"
                  :icon="step.jumlah_data"
                />
              </div>
              <h5 class="my-2 judul">Tahap Pengajuan Sidang</h5>
              <div class="text-center my-2" v-if="loading_sidang">
                <ring-loader class="loading-page" color="#20a506" :size="50" />
                <strong class="loading-text">Loading...</strong>
              </div>
              <div class="sidang" v-else>
                <Stepper
                  v-for="step in steps_pengajuan_sidang"
                  :key="step.id_step"
                  :detail="step"
                  :icon="step.jumlah_data"
                />
              </div>
            </div>
          </b-col>
          <b-col>
            <div class="jabatan-pengguna">
              <JabatanKBK />
            </div>
          </b-col>
        </b-row>
      </div>
    </div>
  </b-container>
</template>
<script>
import Stepper from "../components/Stepper";
import JabatanKBK from "../pages/koor_kp/kbk/Index";
import Axios from "axios";
import config from "../config";
export default {
  components: {
    Stepper,
    JabatanKBK,
  },
  data() {
    return {
      data_step_topik: null,
      data_step_sidang: null,
      id_batch_topik: null,
      id_batch_sidang: null,
      loading_topik: true,
      loading_sidang: true,
      steps_pengajuan_topik: [
        // {
        //   id_step: -1,
        //   judul: "Mengajukan Topik",
        //   isi: "",
        //   jumlah_data: 0
        // },
        {
          id_step: 3,
          judul: "Menunggu Persetujuan Dosen",
          isi: "",
          jumlah_data: 0,
        },
        {
          id_step: 2,
          judul: "Menunggu Persetujuan Koordinator KP",
          isi: "",
          jumlah_data: 0,
        },
        {
          id_step: 1,
          judul: "Menunggu Persetujuan Koordinator KBK",
          isi: "",
          jumlah_data: 0,
        },
      ],
      steps_pengajuan_bimbingan: [
        {
          id_step: 1,
          judul: "Mengajukan Bimbingan",
          isi: "",
          jumlah_data: 0,
        },
        {
          id_step: 2,
          judul: "Menunggu Persetujuan Dosen",
          isi: "",
          jumlah_data: 0,
        },
      ],

      steps_pengajuan_sidang: [
        // {
        //   id_step: -1,
        //   judul: "mengajukan sidang",
        //   isi: "",
        //   jumlah_data: 0
        // },
        {
          id_step: 2,
          judul: "menunggu persetujuan pembimbing",
          isi: "",
          jumlah_data: 0,
        },
        {
          id_step: 3,
          judul: "menunggu penjadwalan",
          isi: "",
          jumlah_data: 0,
        },
        {
          id_step: 4,
          judul: "sidang sudah di jadwalkan",
          isi: "",
          jumlah_data: 0,
        },
      ],
      steps_revisi: [
        {
          id_step: 3,
          judul: "melakukan revisi",
          isi: "",
          jumlah_data: 0,
        },
        {
          id_step: 2,
          judul: "mengajukan penyelesaian revisi",
          isi: "",
          jumlah_data: 0,
        },
        {
          id_step: 4,
          judul: "revisi diterima dosen pembimbing",
          isi: "",
          jumlah_data: 0,
        },
        {
          id_step: 5,
          judul: "revisi diterima dosen penguji",
          isi: "",
          jumlah_data: 0,
        },
      ],
    };
  },
  mounted() {
    this.getBatchTopik();
    this.getBatchSidang();
  },
  methods: {
    getBatchTopik() {
      this.loading_topik = true;
      if (this.$store.getters.batchTopik) {
        this.id_batch_topik = this.$store.getters.batchTopik.id_batch;
        this.getTahapanTopik();
      } else {
        this.$store.dispatch("batchTopik").then((response) => {
          this.id_batch_topik = this.$store.getters.batchTopik.id_batch;
          this.getTahapanTopik();
        });
      }
    },
    getBatchSidang() {
      this.loading_sidang = true;
      if (this.$store.getters.batchSidang) {
        this.id_batch_sidang = this.$store.getters.batchSidang.id_batch;
        this.getTahapanSidang();
      } else {
        this.$store.dispatch("batchSidang").then((response) => {
          this.id_batch_sidang = this.$store.getters.batchSidang.id_batch;
          this.getTahapanSidang();
        });
      }
    },
    getTahapanTopik() {
      this.loading_topik = true;
      const formData = new FormData();
      formData.append("id_batch", this.id_batch_topik);
      Axios.post(`${config.apiKoorKpUrl}/stepper/topik`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.data_step_topik = response.data;
          this.addTahapanTopik();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getTahapanSidang() {
      this.loading_sidang = true;

      const formData = new FormData();
      formData.append("id_batch", this.id_batch_sidang);
      Axios.post(`${config.apiKoorKpUrl}/stepper/sidang`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.data_step_sidang = response.data;
          this.addTahapanSidang();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    addTahapanTopik() {
      var index_data = -1;
      this.steps_pengajuan_topik.forEach((element, index) => {
        index_data = this.data_step_topik.findIndex(
          (step) => step.status_topik == element.id_step
        );
        if (index_data >= 0) {
          element.jumlah_data = this.data_step_topik[index_data].jumlah_data;
        }
        if (index == this.steps_pengajuan_topik.length - 1) {
          this.loading_topik = false;
        }
      });
    },
    addTahapanSidang() {
      var index_data = -1;
      this.steps_pengajuan_sidang.forEach((element, index) => {
        index_data = this.data_step_sidang.findIndex(
          (step) => step.status_sidang == element.id_step
        );
        if (index_data >= 0) {
          element.jumlah_data = this.data_step_sidang[index_data].jumlah_data;
        }
        if (index == this.steps_pengajuan_sidang.length - 1) {
          this.loading_sidang = false;
        }
      });
    },
    cekJabatan(jabatan) {
      return this.$store.getters.jabatan.includes(jabatan);
    },
  },
};
</script>
<style scoped>
.judul {
  font-weight: bold;
}
.koordinator-kp {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>