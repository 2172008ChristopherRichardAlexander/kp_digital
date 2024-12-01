<template>
  <div id="app" class="container">
    <div class="form-container">
      <h2 class="title">Form Pendaftaran MBKM</h2>
      <form @submit.prevent="ajukanForm" class="form">
        <div class="form-group">
          <label for="namaInstansi">Nama Instansi</label>
          <input type="text" id="namaInstansi" v-model="form.namaInstansi" placeholder="Nama Instansi" class="form-control" />
        </div>
        <div class="form-group">
          <label for="deskripsi">Deskripsi</label>
          <textarea id="deskripsi" v-model="form.deskripsi" placeholder="Deskripsi Pekerjaan" class="form-control"></textarea>
        </div>
        <div class="form-group">
          <label for="jenisMbkm">Jenis MBKM</label>
          <select id="jenisMbkm" v-model="form.jenisMbkm" class="form-control">
            <option value="" disabled selected>Pilih Jenis MBKM</option>
            <option v-for="jenis in jenisMBKM" :key="jenis.id_jenis_mbkm" :value="jenis.id_jenis_mbkm">
              {{ jenis.nama_mbkm }}
            </option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajukan</button>
      </form>
    </div>
  </div>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
export default {
  data() {
    return {
      id_semester: null,
      form: {
        namaInstansi: "",
        deskripsi: "",
        jenisMbkm: "",
      },
      jenisMBKM: []
    };
  },
  methods: {
    async authUser() {
      return this.$store.getters.pengguna;
    },
    async ajukanForm() {
      const user = await this.authUser();
      if (this.form.namaInstansi && this.form.deskripsi && this.form.jenisMbkm) {
        this.form.idPengguna = user.Id;  // Misalkan id pengguna ada di user.id
        this.form.idSemester = this.id_semester;  // Pastikan semesterId sudah terisi
        Axios.post(`${config.apiMahasiswaUrl}/mbkm/pendaftaran`, this.form)
          .then((response) => {
            alert('Pendaftaran berhasil!');
          })
          .catch((error) => {
            console.error('Error during form submission:', error);
            alert('Terjadi kesalahan saat mengajukan pendaftaran.');
          });
      } else {
        alert('Harap lengkapi semua data!');
      }
    },
    getAllJenisMBKM() {
      Axios.get(`${config.apiMahasiswaUrl}/jenis-mbkm`)
        .then((res) => {
          if (res.data && Array.isArray(res.data.data)) {
            this.jenisMBKM = res.data.data.map((item) => ({
              id_jenis_mbkm: item.id_jenis_mbkm,
              nama_mbkm: item.nama_mbkm
            }));
          } else {
            console.error("Data jenis MBKM tidak valid", res.data);
          }
        })
        .catch((err) => {
          console.error("Terjadi kesalahan saat mengambil data jenis MBKM", err);
        });
    },
    async getSemesterId() {
      Axios.get(`${config.apiUrl}/semester/aktif`).then((res) => {
        this.id_semester = res.data.id_semester;
      });
    }
  },
  mounted() {
    this.getSemesterId();
    this.getAllJenisMBKM();
    this.authUser();
  }
};
</script>
<style scoped>

.container {
  margin: auto;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  height: 100vh;
  font-family: 'Arial', sans-serif;
}

.form-container {
  width: 100%;
  max-width: 600px;
  margin-top: 50px; 
  background-color: #f8f8f8;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.title {
  text-align: center;
  color: #333;
  margin-bottom: 20px;
  font-size: 24px;
}

.form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 20px;
}

label {
  font-weight: bold;
  margin-bottom: 8px;
  display: block;
  color: #555;
}


.form-control {
  width: 100%;
  padding: 12px 15px;
  font-size: 16px; 
  border: 1px solid #ddd;
  border-radius: 5px;
  transition: border-color 0.3s ease;
  box-sizing: border-box; 
}
select.form-control {
  height: auto; 
  appearance: none; 
  -webkit-appearance: none;
  -moz-appearance: none;
}


select.form-control::-ms-expand {
  display: none;
}
.form-control:focus {
  border-color: #4CAF50;
  outline: none;
}

textarea.form-control {
  height: 120px;
  resize: vertical;
}

select.form-control {
  padding: 12px;
}


button.btn-primary {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 12px 24px;
  font-size: 16px;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

button.btn-primary:hover {
  background-color: #45a049;
}

button:focus {
  outline: none;
}
</style>
