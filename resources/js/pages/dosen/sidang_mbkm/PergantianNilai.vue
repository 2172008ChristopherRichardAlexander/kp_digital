<template>
  <b-container>
    <div class="form-pergantian">
      <PergantianNilaiAkhirSidang jenis_role="dosen" />
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import PergantianNilaiAkhirSidang from "../../../components/PergantianNilaiAkhirSidang";
export default {
  components: {
    PergantianNilaiAkhirSidang
  }
};
</script>
<style scoped>
.form-pergantian {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
</style>