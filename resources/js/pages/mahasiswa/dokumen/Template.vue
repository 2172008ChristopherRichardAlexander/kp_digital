<template>
    <b-container>
        <h3 class="my-4">Unduh Template Dokumen</h3>
        <div class="download-dokumen-section">
            <!-- Daftar Template Dokumen -->
            <b-table :items="dokumenTemplates" :fields="fields" responsive>
                <template v-slot:cell(download)="data">
                    <b-button variant="primary" @click="downloadDokumen(data.item)" :disabled="downloading">
                        <span v-if="downloading">Mengunduh...</span>
                        <span v-else>Unduh</span>
                    </b-button>
                </template>
            </b-table>

            <!-- Feedback Status Download -->
            <div v-if="downloadStatus" class="mt-3">
                <p v-if="downloadStatus.success" class="text-success">{{ downloadStatus.message }}</p>
                <p v-if="!downloadStatus.success" class="text-danger">{{ downloadStatus.message }}</p>
            </div>
        </div>
    </b-container>
</template>

<script>
import Axios from "axios";
import config from "../../../config"; // Pastikan config API URL

export default {
    data() {
        return {
            dokumenTemplates: [], // Daftar template dokumen yang bisa diunduh
            downloading: false,
            downloadStatus: null, // Status download
            fields: [
                { key: "jenis_dokumen.nama_dokumen", label: "Nama Dokumen" },
                { key: "download", label: "Action", sortable: false }
            ]
        };
    },
    mounted() {
        // Mengambil daftar template dokumen dari API saat komponen dimuat
        this.fetchDokumenTemplates();
    },
    methods: {
        // Fungsi untuk mengambil daftar template dokumen
        fetchDokumenTemplates() {
            Axios.get(`${config.apiMahasiswaUrl}/dokumen/templates`)
                .then((response) => {
                    this.dokumenTemplates = response.data.data;
                })
                .catch((error) => {
                    console.error("Error fetching dokumen templates:", error);
                    this.dokumenTemplates = [];
                });
        },

        // Fungsi untuk mengunduh template dokumen
        downloadDokumen(dokumen) {
            if (dokumen.file_dokumen) {
                const url = `${config.dokumenUrl}storage/${dokumen.file_dokumen}`;
                const link = document.createElement('a');
                link.href = url;
                link.download = dokumen.file_dokumen;  // Nama file yang akan didownload
                link.click();
            } else {
                console.error("Dokumen tidak tersedia untuk diunduh.");
            }
        }
    }
};
</script>

<style scoped>
/* Styling untuk halaman unduh template dokumen */
.download-dokumen-section {
    padding: 30px;
    background-color: #f9f9f9;
}

.download-dokumen-section h3 {
    font-size: 1.5em;
    color: #333;
}

.download-dokumen-section .b-button {
    font-size: 16px;
}

.download-dokumen-section .text-success {
    font-size: 1.1em;
}

.download-dokumen-section .text-danger {
    font-size: 1.1em;
}

.download-dokumen-section .mt-3 {
    margin-top: 20px;
}
</style>
