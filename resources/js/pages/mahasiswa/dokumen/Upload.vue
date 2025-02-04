<template>
    <b-container>
        <h3 class="my-4">Unggah Dokumen</h3>
        <div class="upload-dokumen-section">
            <!-- Tombol Upload Dokumen Baru -->
            <b-button variant="primary" class="mb-3" @click="showUploadModal = true">
                Unggah Dokumen Baru
            </b-button>

            <!-- Tabel Daftar Dokumen -->
            <b-table :items="dokumenList" :fields="fields" responsive="sm" bordered striped>
                <template #cell(actions)="row">
                    <b-button size="sm" variant="success" @click="previewDokumen(row.item)">
                        Lihat Dokumen
                    </b-button>
                </template>
            </b-table>

            <!-- Modal Unggah Dokumen Baru -->
            <b-modal v-model="showUploadModal" title="Unggah Dokumen Baru" @hide="clearUploadModal">
                <b-form @submit.prevent="handleUpload">
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
                        <b-form-file id="dokumenFile" v-model="dokumenFile" :state="fileState"
                            accept=".docx, .pdf, .xlsx" required>
                            <template v-slot:hint>Format file yang didukung: .docx, .pdf, .xlsx</template>
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
            </b-modal>
        </div>
        <b-modal id="preview-modal" title="Preview Dokumen" v-model="showPreview" size="lg">
            <template v-slot:default>
                <iframe :src="previewUrl" width="100%" height="1200px"></iframe>
            </template>
        </b-modal>
    </b-container>
</template>

<script>
import Axios from "axios";
import config from "../../../config";

export default {
    data() {
        return {
            id_semester: null,
            jenisDokumen: '',
            showPreview: false,
            dokumenFile: null,
            uploading: false,
            uploadStatus: null,
            jenisDokumenList: [],
            dokumenList: [],
            fields: [
                { key: 'jenis_dokumen.nama_dokumen', label: 'Nama Dokumen' },
                { key: 'actions', label: 'Aksi', class: 'text-center' }
            ],
            showUploadModal: false
        };
    },
    computed: {
        fileState() {
            return this.dokumenFile ? true : null;
        }
    },
    mounted() {
        this.fetchJenisDokumen();
        this.fetchDokumenList();
        this.getSemesterId();
    },
    methods: {
        async authUser() {
            return this.$store.getters.pengguna;
        },
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
        async fetchDokumenList() {
            try {
                const user = await this.authUser();
                const semester = await this.getSemesterId();
                const response = await Axios.get(`${config.apiMahasiswaUrl}/dokumen/${user.Id}/${semester}`);
                this.dokumenList = response.data.data;

            } catch (error) {
                console.error("Error fetching dokumen list:", error);
            }
        },
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
                    this.fetchDokumenList(); // Refresh daftar dokumen setelah upload
                })
                .catch(error => {
                    this.uploading = false;
                    const message = error.response?.data?.message || "Gagal mengunggah dokumen. Coba lagi.";
                    this.uploadStatus = { success: false, message };
                    console.error(error);
                });
        },
        async getSemesterId() {
            try {
                const response = await Axios.get(`${config.apiUrl}/semester/aktif`);
                this.id_semester = response.data.id_semester;
                return this.id_semester; // Mengembalikan id_semester setelah didapatkan
            } catch (error) {
                console.error("Error fetching semester aktif:", error);
                this.id_semester = null;
                return null; // Kembalikan null jika terjadi kesalahan
            }
        },
        showPreviewModal(url) {
            this.previewUrl = url;
            this.showPreview = true;
        },
        previewDokumen(dokumen) {
            if (dokumen.file_dokumen) {
                const previewUrl = `${config.dokumenUrl}storage/${dokumen.file_dokumen}`;
                this.showPreviewModal(previewUrl);
            } else {
                console.error("Dokumen tidak tersedia untuk dipreview.");
            }
        },
        clearUploadModal() {
            this.jenisDokumen = '';
            this.dokumenFile = null;
            this.uploadStatus = null;
        }
    }
};
</script>

<style scoped>
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
