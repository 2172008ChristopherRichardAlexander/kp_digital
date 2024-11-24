<template>
  <div id="app" class="container">
    <h2 class="title">Form Pendaftaran MBKM</h2>
    <div class="container">

      <form @submit.prevent="ajukanForm">
        <div class="form-group">
          <label for="namaInstansi">Nama Instansi</label>
          <input 
            type="text" 
            id="namaInstansi" 
            v-model="form.namaInstansi" 
            placeholder="Nama Instansi" 
            class="form-control"
          />
        </div>
        <div class="form-group">
          <label for="deskripsi">Deskripsi</label>
          <textarea 
            id="deskripsi" 
            v-model="form.deskripsi" 
            placeholder="Deskripsi Pekerjaan" 
            class="form-control">
          </textarea>
        </div>
        <div class="form-group">
          <label for="jenisMbkm">Jenis MBKM</label>
          <select 
            id="jenisMbkm" 
            v-model="form.jenisMbkm" 
            class="form-control">
            <option value="" disabled selected>Pilih Jenis MBKM</option>
            <option value="magang">Magang</option>
            <option value="proyek">Proyek</option>
            <option value="penelitian">Penelitian</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajukan</button>
      </form>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      form: {
        namaInstansi: "",
        deskripsi: "",
        jenisMbkm: ""
      }
    };
  },
  methods: {
    ajukanForm() {
      if (this.form.namaInstansi && this.form.deskripsi && this.form.jenisMbkm) {
        alert("Pendaftaran berhasil diajukan!");
        // Proses data form, misalnya kirim ke backend API
        console.log(this.form);
      } else {
        alert("Harap lengkapi semua data!");
      }
    }
  }
};
</script>
<style scoped>
.container {
  margin: auto;
  font-family: Arial, sans-serif;
}
.title {
  color: #4CAF50;
}
.form-group {
  margin-bottom: 15px;
}
.form-control {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
}
.btn-primary {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
}
.btn-primary:hover {
  background-color: #45a049;
}
</style>
