<template>
    <b-container>
        <h3 class="my-4">Unggah Dokumen Laporan atau Logbook</h3>
        <div class="upload-dokumen-section">

            <!-- Formulir Unggah Dokumen -->
            <b-form @submit.prevent="handleUpload" enctype="multipart/form-data">
                <!-- Pilihan Jenis Dokumen -->
                <b-form-group label="Jenis Dokumen" label-for="jenisDokumen">
                    <b-form-select v-model="jenisDokumen" id="jenisDokumen" required>
                        <option disabled value="">Pilih Jenis Dokumen</option>
                        <option v-for="jenis in jenisDokumenList" :key="jenis.id_jenis_dokumen"
                            :value="jenis.id_jenis_dokumen">
                            {{ jenis.nama_dokumen }}
                        </option>
                    </b-form-select>
                </b-form-group>

                <!-- Pilihan File Dokumen -->
                <b-form-group label="Unggah Dokumen" label-for="dokumenFile">
                    <b-form-file id="dokumenFile" v-model="dokumenFile" :state="fileState" accept=".pdf, .xlsx"
                        required>
                        <template v-slot:hint>
                            Format file yang didukung: .docx, .pdf, .xlsx
                        </template>
                    </b-form-file>
                </b-form-group>

                <!-- Tombol Upload -->
                <b-button type="submit" variant="primary" :disabled="uploading">
                    <span v-if="uploading">Mengunggah...</span>
                    <span v-else>Unggah Dokumen</span>
                </b-button>
            </b-form>

            <!-- Feedback Status Upload -->
            <div v-if="uploadStatus" class="mt-3">
                <p v-if="uploadStatus.success" class="text-success">{{ uploadStatus.message }}</p>
                <p v-if="!uploadStatus.success" class="text-danger">{{ uploadStatus.message }}</p>
            </div>
        </div>
    </b-container>
</template>

<script>
import Axios from "axios";
import config from "../../../config"; // Pastikan path ke config API benar

export default {
    data() {
        return {
            // Data untuk memilih jenis dokumen (laporan/logbook)
            id_semester: null,
            jenisDokumen: '',
            dokumenFile: null,
            uploading: false,
            uploadStatus: null, // Status upload
            jenisDokumenList: [] // Daftar jenis dokumen dari API
        };
    },
    computed: {
        fileState() {
            return this.dokumenFile ? true : null;
        }
    },
    mounted() {
        // Mengambil daftar jenis dokumen dari API saat komponen dimuat
        this.fetchJenisDokumen();
        // Mengambil semester aktif
        this.getSemesterId();

    },
    methods: {
        async authUser() {

            // Fungsi untuk mengambil data pengguna dari Vuex Store atau metode autentikasi lainnya
            // Pastikan Anda telah mengatur Vuex Store dengan getter 'pengguna'
            return this.$store.getters.pengguna;
        },
        // Fungsi untuk mengambil daftar jenis dokumen
        fetchJenisDokumen() {
            Axios.get(`${config.apiUrl}/jenis-dokumen/list`)
                .then((response) => {
                    this.jenisDokumenList = response.data.data;
                })
                .catch((error) => {
                    console.error("Error fetching jenis dokumen:", error);
                    this.jenisDokumenList = [];
                });
        },

        // Fungsi untuk mengunggah dokumen
        async handleUpload() {
            if (!this.dokumenFile || !this.jenisDokumen) {
                this.uploadStatus = { success: false, message: "Pilih jenis dokumen dan file terlebih dahulu." };
                return;
            }
            this.uploading = true;
            const formData = new FormData();
            const user = await this.authUser();
            formData.append("idPengguna", user.Id);
            formData.append("kode_pengguna", user.UserId);
            formData.append("jenisDokumen", this.jenisDokumen);
            formData.append("dokumen", this.dokumenFile);
            formData.append("id_semester", this.id_semester);
            Axios.post(`${config.apiMahasiswaUrl}/dokumen/upload-dokumen`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            })
                .then(response => {
                    this.uploading = false;
                    this.uploadStatus = { success: true, message: "Dokumen berhasil diunggah." };
                    this.dokumenFile = null; // Reset file input setelah upload berhasil
                    this.jenisDokumen = ''; // Reset jenis dokumen setelah upload
                })
                .catch(error => {
                    this.uploading = false;
                    const message = error.response?.data?.message || "Gagal mengunggah dokumen. Coba lagi.";
                    this.uploadStatus = { success: false, message };
                    console.error(error);
                });
        },
        async getSemesterId() {
            Axios.get(`${config.apiUrl}/semester/aktif`).then((res) => {
                this.id_semester = res.data.id_semester;
            });
        }
    }
};
</script>

<style scoped>
/* Styling untuk halaman unggah dokumen laporan/logbook */
.upload-dokumen-section {
    padding: 30px;
    background-color: #f9f9f9;
}

.upload-dokumen-section h3 {
    font-size: 1.5em;
    color: #333;
}

.upload-dokumen-section .b-button {
    font-size: 16px;
}

.upload-dokumen-section .text-success {
    font-size: 1.1em;
}

.upload-dokumen-section .text-danger {
    font-size: 1.1em;
}

.upload-dokumen-section .mt-3 {
    margin-top: 20px;
}
</style>
