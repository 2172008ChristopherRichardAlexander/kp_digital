<template>
  <b-container>
    <div class="form-detail-jabatan">
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">List Jabatan Pengguna</h3>
        </div>
      </div>
      <JabatanPengguna :id_pengguna="id_pengguna" />
    </div>
  </b-container>
</template>
<script>
import JabatanPengguna from "../../../components/JabatanPengguna";
export default {
  components: {
    JabatanPengguna
  },
  data() {
    return {
      id_pengguna: this.$route.params.id
    };
  }
};
</script>
<style scoped>
.detail-jabatan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
/* // * CSS Table */
.table-jabatan {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}

.btn-select {
  margin: 10px 0px 10px 0px;
}
.btn-form {
  padding: 0.38vw 1.4vw;
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
.loading-text {
  color: rgb(32, 165, 6);
}
</style>