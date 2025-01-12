<template>
  <b-container>
    <!-- // ? Bimbingan -->
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
        <TableDetailBimbingan v-else :id_topik="topik.id_topik" />
      </div>
    </div>
    <!-- // ? ###################################################################### -->
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
        <DetailTopik v-else :topik="topik" />
      </div>
    </div>
    <!-- // ? ###################################################################### -->
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
      fields: [
        { key: "deskripsi_bimbingan", label: "Deskripsi" },
        { key: "tanggal_bimbingan", label: "Tanggal" }
      ],
      isBusyBimbingan: false,
      id_pengguna: this.$store.getters.pengguna.UserId,
      topik: {},
      loading_bimbingan: true,
      loading_topik: true
    };
  },
  mounted() {
    this.getTopik();
  },
  methods: {
    getTopik() {
      this.loading_bimbingan = true;
      this.loading_topik = true;
      Axios.get(`${config.apiDosenUrl}/topik/${this.$route.params.id}`)
        .then(response => {
          this.topik = response.data.data;
          this.loading_bimbingan = false;
          this.loading_topik = false;
        })
        .catch(response => {
          console.log(response);
        });
    }
  }
};
</script>
<style scoped>
.form-bimbingan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
.form-topik {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
.btn-select {
  margin: 10px 0px 10px 0px;
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