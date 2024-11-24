<template>
  <div class="form-detail-topik">
    <div v-if="loading_page">
      <ring-loader class="loading-page" color="#bada55" :loading="loading_page" :size="150" />
    </div>
    <div v-else>
      <div class="detail-topik">
        <!-- // * Detail Topik -->
        <b-table-simple borderless responsive>
          <b-thead>
            <b-th colspan="2">
              <h4 class="judul-topik text-center">{{topik.judul_topik}}</h4>
            </b-th>
          </b-thead>
          <b-tbody>
            <!-- // * Latar Belakang -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Latar Belakang</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{topik.latar_belakang}}</b-td>
            </b-tr>
            <!-- // * Rumusan Masalah -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Rumusan Masalah</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{topik.rumusan_masalah}}</b-td>
            </b-tr>
            <!-- // * Tujuan Pembahasan -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Tujuan Pembahasan</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{topik.tujuan_pembahasan}}</b-td>
            </b-tr>
            <!-- // * Ruang Lingkup -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Ruang Lingkup</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{topik.ruang_lingkup}}</b-td>
            </b-tr>
            <!-- // * Sumber Data -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Sumber Data</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{topik.sumber_data}}</b-td>
            </b-tr>
            <!-- // * Sistematika Penyajian -->
            <b-tr class="sub-bab">
              <b-td class="sub-bab-item">Sistematika Penyajian</b-td>
            </b-tr>
            <b-tr class="sub-bab-isi">
              <b-td class="sub-bab-isi-item">{{topik.sistematika_penyajian}}</b-td>
            </b-tr>
          </b-tbody>
        </b-table-simple>
      </div>
    </div>
  </div>
</template>
<script>
import Axios from "axios";
import config from "../config";
export default {
  data() {
    return {
      fileUrl: "/public/pengajuan_topik/",
      loading_page: true
    };
  },
  mounted() {
    this.getTopik();
  },
  methods: {
    getTopik() {
      if (!this.topik) {
        if (this.id_topik) {
          Axios.get(`${config.apiDosenUrl}/id-topik/${this.id_topik}`)
            .then(response => {
              this.topik = response.data.data;
              this.loading_page = false;
            })
            .catch(response => {
              console.log(response);
            });
        } else if (this.slug_topik) {
          Axios.get(`${config.apiDosenUrl}/topik/${this.slug_topik}`)
            .then(response => {
              this.topik = response.data.data;
              this.loading_page = false;
            })
            .catch(response => {
              console.log(response);
            });
        }
      } else this.loading_page = false;
    }
  },
  props: {
    slug_topik: {
      type: String,
      required: false,
      default: null
    },
    id_topik: {
      type: [String, Number],
      required: false,
      default: null
    },
    topik: {
      type: Object,
      required: false,
      default: null
    }
  }
};
</script>
<style scoped>
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
/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
</style>