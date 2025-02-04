<template>
  <div id="konversi-sks" class="container">
    <h2 class="title">Form Konversi SKS</h2>

    <!-- Jika data belum diambil, tampilkan form semester -->
    <div v-if="!dataFetched">
      <div v-for="(semester, index) in semesters" :key="index" class="semester-group">
        <div class="semester-header" @click="toggleSemester(index)">
          SEMESTER {{ index + 1 }}
        </div>
        <div v-if="semester.open" class="semester-content">
          <div v-for="(course, idx) in semester.courses" :key="idx" class="form-check">
            <input type="checkbox" :id="'course-' + index + '-' + idx" v-model="course.selected"
              class="form-check-input" />
            <label :for="'course-' + index + '-' + idx" class="form-check-label">
              {{ course.name }} ({{ course.code }}) {{ course.sks }} SKS
            </label>
          </div>
        </div>
      </div>
      <div class="table-section">
        <h3>{{ authUser.Name }}</h3>
        <table class="table">
          <thead>
            <tr>
              <th>No.</th>
              <th>ID Mata Kuliah</th>
              <th>Nama Mata Kuliah</th>
              <th>Jumlah SKS</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in selectedCourses" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ item.code }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.sks }}</td>
            </tr>
          </tbody>
        </table>
        <div class="total-section">
          Total SKS = {{ totalSKS }} / 20
          <button class="btn btn-primary" :disabled="totalSKS !== 20" @click="submitCourses">Ajukan</button>
        </div>
      </div>
    </div>

    <!-- Jika data sudah diambil, tampilkan hanya tabel konversi SKS -->
    <div v-else>
      <div class="table-section">
        <h3>{{ authUser.Name }}</h3>
        <table class="table">
          <thead>
            <tr>
              <th>No.</th>
              <th>ID Mata Kuliah</th>
              <th>Nama Mata Kuliah</th>
              <th>Jumlah SKS</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in selectedCourses" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ item.code }}</td>
              <td>{{ item.name }}</td>
              <td>{{ item.sks }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script>
import Axios from "axios";
import config from "../../../config";

export default {
  data() {
    return {
      batch: {},
      id_batch: null,
      id_semester: null,
      semesters: [
        { open: false, courses: [] },
        { open: false, courses: [] },
        { open: false, courses: [] },
        { open: false, courses: [] },
        { open: false, courses: [] },
        { open: false, courses: [] },
        { open: false, courses: [] },
        { open: false, courses: [] },
      ],
      dataFetched: false,
      konversiSKS: [],
    };
  },
  computed: {
    authUser() {
      return this.$store.getters.pengguna;
    },
    selectedCourses() {
      return this.semesters
        .flatMap((semester) => semester.courses)
        .filter((course) => course.selected);
    },
    totalSKS() {
      return this.selectedCourses.reduce((total, course) => total + course.sks, 0);
    },
  },
  methods: {
    toggleSemester(index) {
      this.semesters[index].open = !this.semesters[index].open;
    },
    async getSemesterId() {
      Axios.get(`${config.apiUrl}/semester/aktif`).then((res) => {
        this.id_semester = res.data.id_semester;
        this.fetchKonversiSKS(this.authUser.Id, this.id_semester);  // Ambil data konversi SKS setelah semester didapat
      });
    },
    async fetchCourses() {
      Axios.get(`${config.apiMahasiswaUrl}/matakuliah`).then((res) => {
        const courses = res.data.data; // Ambil data kursus dari API
        courses.forEach(course => {
          const semesterIndex = course.id_jenis_semester.id_jenis_semester - 1;
          if (this.semesters[semesterIndex]) {
            this.semesters[semesterIndex].courses.push({
              code: course.id_matakuliah,
              name: course.nama_matakul,
              sks: course.jumlah_sks,
              selected: false,
            });
          }
        });
      }).catch((error) => {
        console.error('Error fetching courses:', error);
      });
    },
    fetchKonversiSKS(id_pengguna, id_semester) {
      Axios.get(`${config.apiUrl}/konversi-sks/${id_pengguna}/${id_semester}`)
        .then((res) => {
          const konversiData = res.data.data;

          // Cek apakah konversiData benar-benar ada dan tidak kosong
          if (Array.isArray(konversiData) && konversiData.length > 0) {
            this.markSelectedCourses(konversiData);
            this.dataFetched = true;
          } else {
            // Jika konversiData kosong atau null
            this.dataFetched = false;
            console.log('Tidak ada data konversi SKS yang ditemukan.');
          }
        })
        .catch((error) => {
          console.error('Error fetching konversi SKS:', error);
          this.dataFetched = false; // Jika terjadi error, pastikan dataFetched false
        });
    },
    getBatchTopik() {
      this.loading_page = true;
      const formData = new FormData();
      formData.append("jenis_batch", 1);
      Axios.post(`${config.apiUrl}/batch/pengajuan`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.batch = response.data;
          if (!this.batch) {
            if (this.$store.getters.batchTopik) {
              this.id_batch = this.$store.getters.batchTopik.id_batch;
              this.batch = this.$store.getters.batchTopik;
              this.getTopik();
            } else {
              this.$store.dispatch("batchTopik").then((response) => {
                this.id_batch = this.$store.getters.batchTopik.id_batch;
                this.batch = this.$store.getters.batchTopik;
                this.getTopik();
              });
            }
          } else {
            this.id_batch = this.batch.id_batch;
            this.getTopik();
          }
        })
        .catch((response) => {
          console.log(response);
        });
    },
    markSelectedCourses(konversiData) {
      konversiData.forEach(konversi => {
        this.semesters.forEach(semester => {
          semester.courses.forEach(course => {
            if (course.code === konversi.id_matakuliah) {
              course.selected = true;
            }
          });
        });
      });
    },
    reloadPage() {
      // Memuat ulang halaman
      window.location.reload();
    },
    async submitCourses() {
      const selectedCoursesData = this.selectedCourses.map(course => {
        return {
          id_matakuliah: course.code,
          id_pengguna: this.authUser.Id,
          id_semester: this.id_semester,
        };
      });

      Axios.post(`${config.apiMahasiswaUrl}/konversi-sks`, selectedCoursesData, {
        headers: {
          "Content-Type": "application/json",
        },
      })
        .catch(error => {
          console.error('Error submitting courses:', error);
        });
      const detail = new FormData();
      detail.append("id_batch", this.id_batch);
      detail.append("id_pengaju", this.authUser.UserId);
      console.log(detail);
      Axios.post(`${config.apiMahasiswaUrl}/update-status-topik`, detail,{
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then(response => {
          console.log(response);
          this.reloadPage();
          this.$bvToast.toast(
            "Konversi SKS berhasil diajukan",
            {
              variant: "success",
              solid: true,
            }
          );
        })
        .catch(error => {
          console.error('Error submitting courses:', error);
        });
    },
  },
  mounted() {
    this.getSemesterId();
    this.fetchCourses();
    this.getBatchTopik();
  },
};
</script>
<style scoped>
.container {
  margin: auto;
  font-family: Arial, sans-serif;
}

.title {
  color: #000000;
  margin-bottom: 20px;
}

.semester-group {
  margin-bottom: 10px;
}

.semester-header {
  background-color: #CACE93;
  padding: 10px;
  cursor: pointer;
  text-align: center;
  font-weight: bold;
}

.semester-header:hover {
  background-color: #c0ca33;
}

.semester-content {
  background-color: #f9fbe7;
  padding: 10px;
}

.form-check {
  margin-bottom: 5px;
}

.table-section {
  margin-top: 30px;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.table th {
  background-color: #f1f8e9;
}

.total-section {
  margin-top: 10px;
  font-weight: bold;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.btn-primary {
  background-color: #4CAF50;
  color: white;
  padding: 5px 15px;
  border: none;
  cursor: pointer;
}

.btn-primary:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
</style>