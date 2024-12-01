<template>
    <div id="konversi-sks" class="container">
        <h2 class="title">Form Konversi SKS</h2>
        <div v-if="deskripsi" class="deskripsi-section">
            <h4>Deskripsi Pekerjaan</h4>
            <p>{{ deskripsi }}</p>
        </div>
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
            <h3>{{ this.nama_mahasiswa }}</h3>
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
            id_pengguna: null,
            id_mbkm: null,
            nama_mahasiswa: null,
            id_semester: null,
            deskripsi: null,
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
        getSemesterId() {
            Axios.get(`${config.apiUrl}/semester/aktif`).then((res) => {
                this.id_semester = res.data.id_semester;
            });
        },
        // Fetch data MBKM dan data konversi SKS berdasarkan pengguna
        fetchMBKM() {
            Axios.get(`${config.apiUrl}/mbkm/list/konversi/${this.id_mbkm}`)
                .then((res) => {
                    this.nama_mahasiswa = res.data.data.mahasiswa.nama_pengguna;
                    this.deskripsi = res.data.data.deskripsi;
                    this.id_pengguna = res.data.data.mahasiswa.id_pengguna;
                    this.fetchKonversiSKS(this.id_pengguna);
                })
                .catch((error) => {
                    console.error('Error fetching MBKM:', error);
                });
        },

        // Ambil data konversi SKS berdasarkan id_pengguna
        fetchKonversiSKS(id_pengguna) {
            Axios.get(`${config.apiUrl}/konversi-sks/${id_pengguna}`)
                .then((res) => {

                    // Respon berisi data konversi SKS untuk pengguna tertentu
                    const konversiData = res.data.data;
                    this.markSelectedCourses(konversiData);
                })
                .catch((error) => {
                    console.error('Error fetching konversi SKS:', error);
                });
        },

        // Tandai kursus yang sudah terkonversi
        markSelectedCourses(konversiData) {
            konversiData.forEach(konversi => {
                // Cari mata kuliah di semester yang sesuai
                this.semesters.forEach(semester => {
                    semester.courses.forEach(course => {
                        // Tandai jika mata kuliah sudah terkonversi
                        if (course.code === konversi.id_matakuliah) {
                            course.selected = true; // Tandai sebagai terpilih
                        }
                    });
                });
            });
        },

        // Fetch data kursus
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

        // Method lainnya tetap sama
        async submitCourses() {
            const selectedCoursesData = this.selectedCourses.map((course) => ({
                id_matakuliah: course.code,
                id_pengguna: this.id_pengguna,
                id_semester: course.id_semester,
            }));

            // Kirim data ke API untuk memperbarui konversi SKS
            console.log(selectedCoursesData)
            try {
                const response = await Axios.put(
                    `${config.apiUrl}/konversi-sks/${this.id_pengguna}`,
                    { selectedCourses: selectedCoursesData }
                );
                alert("Data konversi SKS berhasil diperbarui!");
                console.log(response);
            } catch (error) {
                console.error("Error submitting courses:", error);
            }
        },
    },
    mounted() {
        this.id_mbkm = this.$route.params.id;
        this.getSemesterId();
        this.fetchCourses();
        this.fetchMBKM();;
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