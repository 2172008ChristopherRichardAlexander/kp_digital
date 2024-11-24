<template>
  <b-media class="komentar" tag="li">
    <template v-slot:aside>
      <b-img blank blank-color="#abc" width="75" height="90" alt="placeholder"></b-img>
    </template>
    <div class="tanggal-komentar">{{moment(komentar.tanggal_komentar)}}</div>
    <div class="nama-pengguna">{{getNamaPengguna(komentar.id_pengguna)}}{{nama_pengguna}}</div>
    <hr />
    <div class="isi-komentar">{{komentar.isi_komentar}}</div>
  </b-media>
</template>


<script>
import moment from "moment";
import Axios from "axios";
import config from "../config";
export default {
  data() {
    return {
      nama_pengguna: "",
    };
  },
  methods: {
    moment: function(date) {
      return moment(date).format("DD-MM-YYYY");
    },
    getNamaPengguna(id_pengguna) {
      Axios.get(
        `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` + id_pengguna
      )
        .then(response => {
          this.nama_pengguna = response.data[0].Name;
        })
        .catch(response => {
          console.log(response);
        });
    }
  },
  props: ["komentar"]
};
</script>

<style scoped>
.tanggal-komentar {
  font-size: 13px;
  color: rgb(146, 165, 143);
}
hr {
  margin: 0px;
  padding: 0px;
  border: 1px solid rgb(202, 206, 147);
}
.nama-pengguna {
  font-size: 14px;
  font-weight: bold;
  color: rgb(19, 21, 0);
}
.isi-komentar{
  font-size: 16px;
  color: rgb(28, 31, 1);
}
.komentar{
  padding: 10px;
  border: 1px solid rgb(202, 206, 147);
  margin-bottom: 0px;
}
</style>