<template>
  <div id="app" class="container">
    <!-- Loading State -->
    <div v-if="submissionStatus === 'loading'" class="loading-container">
      <p>Loading...</p>
      <!-- Anda dapat mengganti ini dengan komponen spinner -->
    </div>

    <!-- Tampilkan Formulir Pendaftaran MBKM jika belum ada pendaftaran -->
    <div v-else-if="submissionStatus === 'notExists'" class="form-container">
      <h2 class="title">Form Pendaftaran MBKM</h2>
      <p>{{ statusMessage }}</p>
      <form @submit.prevent="ajukanForm" class="form">
        <div class="form-group">
          <label for="namaInstansi">Nama Instansi</label>
          <input type="text" id="namaInstansi" v-model="form.namaInstansi" placeholder="Nama Instansi"
            class="form-control" required />
        </div>
        <div class="form-group">
          <label for="deskripsi">Deskripsi</label>
          <textarea id="deskripsi" v-model="form.deskripsi" placeholder="Deskripsi Pekerjaan" class="form-control"
            required></textarea>
        </div>
        <div class="form-group">
          <label for="jenisMbkm">Jenis MBKM</label>
          <select id="jenisMbkm" v-model="form.jenisMbkm" class="form-control" required>
            <option value="" disabled>Pilih Jenis MBKM</option>
            <option v-for="jenis in jenisMBKM" :key="jenis.id_jenis_mbkm" :value="jenis.id_jenis_mbkm">
              {{ jenis.nama_mbkm }}
            </option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajukan</button>
      </form>
    </div>

    <!-- Tampilkan Detail Pendaftaran MBKM dalam Mode Readonly -->
    <div v-else-if="submissionStatus === 'exists'" class="form-container">
      <h2 class="title">Detail Pendaftaran MBKM</h2>
      <div class="form">
        <div class="form-group">
          <label for="namaInstansi">Nama Instansi</label>
          <input type="text" id="namaInstansi" :value="existingRegistration.namaInstansi" class="form-control"
            readonly />
        </div>
        <div class="form-group">
          <label for="deskripsi">Deskripsi</label>
          <textarea id="deskripsi" :value="existingRegistration.deskripsi" class="form-control" readonly></textarea>
        </div>
        <div class="form-group">
          <label for="jenisMbkm">Jenis MBKM</label>
          <input type="text" id="jenisMbkm" :value="existingRegistration.jenisMbkm" class="form-control" readonly />
        </div>
        <div class="form-group">
          <label for="status">Status</label>
          <input type="text" id="status" :value="statusMessage" class="form-control" readonly />
        </div>
        <button @click="reloadPage" class="btn btn-secondary">Refresh</button>
      </div>
    </div>
    <div v-else-if="submissionStatus === 'success'" class="form-container">
      <h2 class="title">Berhasil</h2>
      <p>{{ statusMessage }}</p>
      <button @click="reloadPage" class="btn btn-secondary">Refresh</button>
    </div>
    <!-- Tampilkan Pesan Error -->
    <div v-else class="form-container">
      <h2 class="title">Error</h2>
      <p>{{ statusMessage }}</p>
      <button @click="reloadPage" class="btn btn-secondary">Coba Lagi</button>
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
      jenisMBKM: [],
      isReadonly: false, // Flag untuk menentukan apakah formulir harus readonly
      submissionStatus: "loading", // 'loading', 'notExists', 'exists', 'success', 'error'
      existingRegistration: null,
      statusMessage: "",
    };
  },
  methods: {
    /**
     * Mengambil pengguna yang terautentikasi dari Vuex store.
     */
    async authUser() {
      console.log(this.$store.getters.pengguna.Id)
      return this.$store.getters.pengguna;
    },

    /**
     * Mengambil semua opsi Jenis MBKM.
     */
    async getAllJenisMBKM() {
      try {
        const res = await Axios.get(`${config.apiMahasiswaUrl}/jenis-mbkm`);
        if (res.data && Array.isArray(res.data.data)) {
          this.jenisMBKM = res.data.data.map((item) => ({
            id_jenis_mbkm: item.id_jenis_mbkm,
            nama_mbkm: item.nama_mbkm,
          }));
        } else {
          console.error("Data jenis MBKM tidak valid", res.data);
        }
      } catch (err) {
        console.error("Terjadi kesalahan saat mengambil data jenis MBKM", err);
      }
    },

    /**
     * Mengambil ID semester aktif.
     */
    async getSemesterId() {
      try {
        const res = await Axios.get(`${config.apiUrl}/semester/aktif`);
        this.id_semester = res.data.id_semester;
      } catch (error) {
        console.error("Error fetching semester ID:", error);
        this.submissionStatus = "error";
        this.statusMessage = "Terjadi kesalahan saat mengambil data semester.";
      }
    },

    /**
     * Memeriksa apakah pengguna memiliki pendaftaran MBKM yang sudah ada.
     */
    async checkExistingMBKM() {
      try {
        const user = await this.authUser();
        await this.getSemesterId();
        console.log(this.id_semester);
        // Pastikan properti user.id sesuai dengan yang dikembalikan oleh authUser()
        const response = await Axios.get(`${config.apiMahasiswaUrl}/mbkm-status/${user.Id}/${this.id_semester}`);

        if (response.data && response.data.data && response.data.data.id_mbkm) {
          this.existingRegistration = {
            namaInstansi: response.data.data.nama_instansi,
            deskripsi: response.data.data.deskripsi,
            jenisMbkm: response.data.data.jenis_mbkm.nama_mbkm,
            status: response.data.data.status,
          };
          this.submissionStatus = "exists";

          // Menetapkan pesan status berdasarkan kode status
          if (response.data.data.status === 0) {
            this.statusMessage = "Pendaftaran sedang menunggu konfirmasi dosen.";
          } else if (response.data.data.status === 1) {
            this.statusMessage = "Pendaftaran berhasil dilakukan.";
          }
        } else {
          // Jika respons tidak memiliki data pendaftaran
          this.submissionStatus = "notExists";
          this.statusMessage = "Anda belum pernah mendaftar MBKM.";
        }
      } catch (error) {
        if (error.response && error.response.status === 404) {
          // Tidak ada pendaftaran yang ditemukan
          this.submissionStatus = "notExists";
          this.statusMessage = "Anda belum pernah mendaftar MBKM.";
        } else {
          console.error("Terjadi kesalahan saat memeriksa data MBKM", error);
          this.submissionStatus = "error";
          this.statusMessage = "Terjadi kesalahan saat memeriksa data MBKM.";
        }
      }
    },

    /**
     * Mengajukan formulir pendaftaran MBKM.
     */
    async ajukanForm() {
      try {
        const user = await this.authUser();
        if (this.form.namaInstansi && this.form.deskripsi && this.form.jenisMbkm) {
          const payload = {
            nama_instansi: this.form.namaInstansi,
            deskripsi: this.form.deskripsi,
            jenis_mbkm_id: this.form.jenisMbkm,
            idPengguna: user.Id,
            id_semester: this.id_semester,
          };

          const response = await Axios.post(`${config.apiMahasiswaUrl}/mbkm/pendaftaran`, payload);
          console.log("Pendaftaran Response:", response.data);

          // Menetapkan status dan pesan berdasarkan status dari API
          if (response.data.status === "pending") {
            this.submissionStatus = "exists"; // Menandakan pendaftaran sedang diproses
            this.statusMessage = response.data.message || "Pendaftaran sedang menunggu konfirmasi.";
            // Simpan data pendaftaran untuk ditampilkan pada form read-only
            this.existingRegistration = {
              namaInstansi: this.form.namaInstansi,
              deskripsi: this.form.deskripsi,
              jenisMbkm: this.jenisMBKM.find(j => j.id_jenis_mbkm === this.form.jenisMbkm).nama_mbkm,
              status: 0, // Status pending
            };
            this.isReadonly = true;
          } else if (response.data === "success") {
            this.submissionStatus = "success"; // Pendaftaran berhasil
            this.statusMessage = response.data.message || "Pendaftaran berhasil dilakukan.";
            this.existingRegistration = {
              namaInstansi: this.form.namaInstansi,
              deskripsi: this.form.deskripsi,
              jenisMbkm: this.jenisMBKM.find(j => j.id_jenis_mbkm === this.form.jenisMbkm).nama_mbkm,
            };
            this.isReadonly = true;
          } else {
            // Tangani status lainnya yang tidak terduga
            this.submissionStatus = "error";
            this.statusMessage = response.data.message || "Pendaftaran gagal.";
          }
        } else {
          this.$bvToast.toast(
            "Harap lengkapi semua data!",
            {
              variant: "warning",
              solid: true,
              title: "Peringatan",
            }
          );
        }
      } catch (error) {
        if (error.response) {
          // Jika ada respons dari server
          console.error("Error Response:", error.response.data);
          if (error.response.status === 422) {
            // Misalnya status 422 menunjukkan kesalahan validasi
            this.submissionStatus = "error";
            this.statusMessage = error.response.data.message || "Validasi gagal.";
          } else {
            // Tangani kesalahan umum
            this.submissionStatus = "error";
            this.statusMessage = "Terjadi kesalahan pada server.";
          }
        } else {
          // Jika tidak ada respons dari server
          console.error("Error:", error);
          this.submissionStatus = "error";
          this.statusMessage = "Terjadi kesalahan saat mengajukan pendaftaran.";
        }
      }

    },


    /**
     * Reload data pendaftaran MBKM tanpa me-reload seluruh halaman.
     */
    async reloadPage() {
      this.submissionStatus = "loading";
      this.statusMessage = "";
      await this.checkExistingMBKM();
      window.location.reload()
    },

    /**
     * Navigasi kembali ke halaman beranda.
     */
  },
  mounted() {
    this.getSemesterId();
    this.getAllJenisMBKM();
    this.checkExistingMBKM(); // Memeriksa pendaftaran MBKM yang sudah ada
  },
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

button.btn-primary,
button.btn-secondary {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 12px 24px;
  font-size: 16px;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s ease;
  margin-top: 10px;
}

button.btn-secondary {
  background-color: #6c757d;
}

button.btn-primary:hover {
  background-color: #45a049;
}

button.btn-secondary:hover {
  background-color: #5a6268;
}

button:focus {
  outline: none;
}

.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
</style>
