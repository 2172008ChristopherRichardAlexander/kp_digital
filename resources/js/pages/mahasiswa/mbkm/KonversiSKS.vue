<template>
  <div id="konversi-sks" class="container">
    <h2 class="title">Form Konversi SKS</h2>

    <!-- Bagian Form Semester -->
    <div v-for="(semester, index) in semesters" :key="index" class="semester-group">
      <div class="semester-header" @click="toggleSemester(index)">
        SEMESTER {{ index + 1 }}
      </div>
      <div v-if="semester.open" class="semester-content">
        <!-- Menampilkan kursus yang dimiliki setiap semester -->
        <div v-for="(course, idx) in semester.courses" :key="idx" class="form-check">
          <input type="checkbox" :id="'course-' + index + '-' + idx" v-model="course.selected"
            class="form-check-input" />
          <label :for="'course-' + index + '-' + idx" class="form-check-label">
            {{ course.name }} ({{ course.code }}) {{ course.sks }} SKS
          </label>
        </div>
      </div>
    </div>

    <!-- Bagian Tabel -->
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
</template>

<script>
import Axios from "axios";
import config from "../../../config";
export default {
  data() {
    return {
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
      });
    },
    fetchCourses() {
      Axios.get(`${config.apiMahasiswaUrl}/matakuliah`).then((res) => {
        const courses = res.data.data; // Ambil data kursus dari API
        // Memasukkan kursus ke semester yang sesuai berdasarkan id_jenis_semester
        courses.forEach(course => {
          const semesterIndex = course.id_jenis_semester.id_jenis_semester - 1; // Menyesuaikan dengan indeks array (id_jenis_semester 1 -> semester 0)
          // Pastikan semesterIndex valid dan array courses ada
          if (this.semesters[semesterIndex]) {
            // Menambahkan kursus ke semester yang sesuai
            this.semesters[semesterIndex].courses.push({
              code: course.id_matakuliah,      // ID Mata Kuliah
              name: course.nama_matakul,       // Nama Mata Kuliah
              sks: course.jumlah_sks,         // Jumlah SKS
              selected: false,                // Status default untuk checkbox (belum dipilih)
            });
          } else {
            console.error(`Semester with id_jenis_semester: ${course.id_jenis_semester} not found.`);
          }
        });
      }).catch((error) => {
        console.error('Error fetching courses:', error);
      });
    },
    async submitCourses() {
      const selectedCoursesData = this.selectedCourses.map(course => {
        return {
          id_matakuliah: course.code,         // ID Mata Kuliah
          id_pengguna: this.authUser.Id,      // ID Pengguna dari store (pastikan ini tersedia)
          id_semester: this.id_semester, // ID Semester yang sesuai
        };
      });
      // Kirim data terpilih ke API menggunakan Axios
      Axios.post(`${config.apiMahasiswaUrl}/konversi-sks`, selectedCoursesData, {
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then(response => {
          alert("Data berhasil diajukan!");
          console.log(response.data);  // Lihat respon dari server
        })
        .catch(error => {
          console.error('Error submitting courses:', error);
        });
    },

  },
  mounted() {
    this.getSemesterId();
    this.fetchCourses();
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