<template>
  <div class="detail-bimbingan">
    <b-table
      sticky-header
      class="table-detail-bimbingan"
      ref="tableDetailBimbingan"
      hover
      striped
      :items="kumpulan_bimbingan"
      :fields="fields_table_detail_bimbingan"
      responsive="sm"
      :busy="isBusyTabelDetailBimbingan"
      show-empty
    >
      <template v-slot:table-colgroup="scope">
        <col
          v-for="field in scope.fields"
          :key="field.key"
          :style="{ width: field.key === 'index' ? '45px' : field.key ==='tanggal_bimbingan' ? '150px' : ''}"
        />
      </template>
      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <ring-loader class="loading-page" color="#20a506" :size="50" />
          <strong class="loading-text">Loading...</strong>
        </div>
      </template>
      <template v-slot:empty>
        <h5 class="text-center">Tidak ada data Bimbingan</h5>
      </template>

      <template v-slot:cell(index)="data">{{ data.index + 1 }}</template>
    </b-table>
  </div>
</template>
<script>
import Axios from "axios";
import config from "../config";
export default {
  data() {
    return {
      fields_table_detail_bimbingan: [
        { key: "index", label: "#" },
        { key: "tanggal_bimbingan", label: "Tanggal" },
        { key: "deskripsi_bimbingan", label: "Deskripsi" }
      ],
      kumpulan_bimbingan: [],
      isBusyTabelDetailBimbingan: false,
      id_pengguna: this.$store.getters.pengguna.UserId
    };
  },
  mounted() {
    this.getBimbingan();
  },
  methods: {
    getBimbingan() {
      this.isBusyTabelDetailBimbingan = true;
      Axios.post(`${config.apiDosenUrl}/bimbingan/topik-status`, {
        id_topik: this.id_topik,
        status_bimbingan: 1
      })
        .then(response => {
          this.kumpulan_bimbingan = response.data.data;
          this.isBusyTabelDetailBimbingan = false;
        })
        .catch(response => {
          console.log(response);
        });
    }
  },
  acakAcak(isi, status) {
    isi = Array.from(String(isi));
    var code = "";
    if (status == 0) {
      isi.forEach(element => {
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
      isi.forEach(element => {
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
  props: ["id_topik"]
};
</script>
<style scoped>
.table-detail-bimbingan {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
  white-space: pre-wrap;
}
.form-bimbingan {
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