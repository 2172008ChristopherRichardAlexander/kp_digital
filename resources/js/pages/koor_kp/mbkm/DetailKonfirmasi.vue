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
            <div class="action-buttons">
                <button class="btn btn-secondary" @click="goBack">Kembali</button>
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
        fetchKonversiSKS(id_pengguna) {
            Axios.get(`${config.apiUrl}/konversi-sks/${id_pengguna}`)
                .then((res) => {
                    const konversiData = res.data.data;
                    this.markSelectedCourses(konversiData);
                })
                .catch((error) => {
                    console.error('Error fetching konversi SKS:', error);
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
        fetchCourses() {
            Axios.get(`${config.apiMahasiswaUrl}/matakuliah`).then((res) => {
                const courses = res.data.data;
                courses.forEach(course => {
                    const semesterIndex = course.id_jenis_semester.id_jenis_semester - 1;
                    if (this.semesters[semesterIndex]) {
                        this.semesters[semesterIndex].courses.push({
                            code: course.id_matakuliah,
                            name: course.nama_matakul,
                            sks: course.jumlah_sks,
                            selected: false,
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
            const selectedCoursesData = this.selectedCourses.map((course) => ({
                id_matakuliah: course.code,
                id_pengguna: this.id_pengguna,
                id_semester: this.id_semester,
            }));

            try {
                // Hapus data konversi yang tidak dicentang
                const allCoursesData = await Axios.get(`${config.apiUrl}/konversi-sks/${this.id_pengguna}`);
                const existingCourses = allCoursesData.data.data;

                // Cari kursus yang tidak dipilih
                const deselectedCourses = existingCourses.filter(course =>
                    !selectedCoursesData.some(selected => selected.id_matakuliah === course.id_matakuliah)
                );

                // Hapus kursus yang tidak dipilih
                for (let course of deselectedCourses) {
                    await Axios.delete(`${config.apiUrl}/konversi-sks/${this.id_pengguna}/${course.id_matakuliah}`);
                }

                // Sekarang simpan data yang dipilih
                const response = await Axios.put(
                    `${config.apiUrl}/konversi-sks/${this.id_pengguna}`,
                    { selectedCourses: selectedCoursesData }
                );
                console.log(response);

                // Setelah konversi SKS berhasil, update status MBKM menjadi 1
                await Axios.put(`${config.apiUrl}/mbkm/${this.id_pengguna}`, {
                    status: 1,
                });

                alert("Data konversi SKS berhasil diperbarui dan status MBKM telah diperbarui!");
                this.$router.push(`/mbkm/konfirmasi`);
            } catch (error) {
                console.error("Error submitting courses:", error);
            }
        },
        goBack() {
            this.$router.go(-1); // Kembali ke halaman sebelumnya
        },
    },
    mounted() {
        this.id_mbkm = this.$route.params.id;
        this.getSemesterId();
        this.fetchCourses();
        this.fetchMBKM();
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

.btn {
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    width: 15%; /* Membuat tombol memiliki lebar yang sama */
}

.btn-primary {
    background-color: #4CAF50;
    color: white;
}

.btn-action {
    background-color: #6c757d;
    color: white;
}

.btn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.action-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}
</style>