<template>
    <b-container>
        <div class="form-dokumen">
            <!-- Title Keterangan Halaman -->
            <div class="row">
                <div class="col">
                    <h3 class="keterangan-website my-2">Berkas Mahasiswa</h3>
                </div>
                <div class="col-3 pilihan-semester">
                    <b-form-select v-model="id_semester" size="sm" @change="getListDokumen">
                        <template v-slot:first>
                            <option :value="null" disabled>-- Semester --</option>
                            <option value="all">-- All Semester --</option> <!-- All option -->
                        </template>
                        <option v-for="pilihan in pilihan_semester" :key="pilihan.id_semester"
                            :value="pilihan.id_semester">
                            {{ pilihan.nama_semester }}
                        </option>
                    </b-form-select>
                </div>
                <div class="col-3" style="margin: auto">
                    <b-form-group label-size="sm" label-for="filterInput" class="mb-0">
                        <b-input-group size="sm">
                            <b-form-input v-model="filter" type="search" id="filterInput"
                                placeholder="Ketik untuk mencari"></b-form-input>
                            <b-input-group-append>
                                <b-button class="btn-form" :disabled="!filter" @click="filter = ''">Hapus</b-button>
                            </b-input-group-append>
                        </b-input-group>
                    </b-form-group>
                </div>
                <div class="col-3 pilihan-jenis-dokumen">
                    <b-button variant="primary" @click="downloadAllDokumen" class="my-1">
                        Download Semua
                    </b-button>
                </div>
            </div>
            <div class="dokumen-table">
                <b-table sticky-header class="tabel-dokumen" ref="tableDokumen" no-border-collapse
                    :items="kumpulan_dokumen" :fields="fields" hover striped responsive="sm" :current-page="currentPage"
                    :per-page="perPage" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :filter="filter"
                    @filtered="onFiltered">
                    <template v-slot:table-busy>
                        <div class="text-center my-2">
                            <ring-loader class="loading-page" color="#20a506" :size="50" />
                            <strong class="loading-text">Loading...</strong>
                        </div>
                    </template>
                    <template v-slot:empty>
                        <div class="text-center">
                            <h5 class="text-muted">Tidak ada data dokumen MBKM yang tersedia.</h5>
                            <p class="text-muted">Silakan pilih filter yang berbeda atau periksa kembali data yang
                                dimasukkan.</p>
                        </div>
                    </template>
                    <template v-slot:cell(no)="data">
                        {{ (currentPage - 1) * perPage + data.index + 1 }}
                    </template>
                    <template v-slot:cell(is_mbkm)="data">
                        {{ data.item.is_mbkm === 1 ? 'MBKM' : 'Reguler' }}
                    </template>
                    <template v-slot:cell(action)="data">
                        <b-button size="sm" variant="success" @click="downloadDokumen(data.item)">
                            <i class="material-icons">download</i>
                        </b-button>
                        <b-button size="sm" variant="primary" @click="previewDokumen(data.item)">
                            <i class="material-icons">preview</i>
                        </b-button>
                    </template>
                </b-table>

                <b-row>
                    <!-- Pagination -->
                    <b-col>
                        <b-row>
                            <b-col>
                                <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage"
                                    align="fill" size="sm" class="mb-0"></b-pagination>
                            </b-col>
                            <b-col lg="2">
                                <b-form-select v-model="perPage" id="perPageSelect" size="sm"
                                    :options="pageOptions"></b-form-select>
                            </b-col>
                        </b-row>
                    </b-col>
                    <b-col class="text-right">&nbsp;</b-col>
                </b-row>
            </div>
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
            // Default filter values set to 'all' (showing all documents by default)
            id_semester: 'all',
            id_jenis_dokumen: 'all',
            filter: null,
            // Pagination
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
            // Sorting
            sortBy: "nama",
            sortDesc: false,
            // Table
            kumpulan_dokumen: [],
            fields: [
                { key: "no", label: "No", sortable: true },
                { key: "mahasiswa.nama_pengguna", label: "Nama", sortable: true },
                { key: "jenis_dokumen.nama_dokumen", label: "Jenis Dokumen", sortable: true },
                { key: "action", label: "Action" }
            ],
            // Filter Options
            pilihan_semester: [],
            pilihan_jenis_dokumen: [],
            showPreview: false,
            previewUrl: "",
        };
    },

    mounted() {
        this.getListSemester();
        this.getListJenisDokumen();
        this.getListDokumen();
        this.authUser();
    },
    methods: {
        async authUser() {
            return this.$store.getters.pengguna;
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
        // Mendapatkan data dokumen berdasarkan filter
        async getListDokumen() {
            const user = await this.authUser();
            let params = {};
            if (this.id_semester && this.id_semester !== 'all') params.id_semester = this.id_semester;

            Axios.get(`${config.apiDosenUrl}/topik/dokumen-mahasiswa/${user.UserId}`, { params })
                .then((response) => {
                    if (response.data.length === 0) {
                        this.kumpulan_dokumen = [];
                    } else {
                        this.kumpulan_dokumen = response.data.data;
                    }
                })
                .catch((error) => {
                    console.error(error);
                    this.kumpulan_dokumen = [];
                });
        },
        // Mendapatkan daftar semester
        getListSemester() {
            Axios.get(`${config.apiUrl}/semester/list`)
                .then((response) => {
                    this.pilihan_semester = response.data.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        // Mendapatkan daftar jenis dokumen
        getListJenisDokumen() {
            Axios.get(`${config.apiUrl}/jenis-dokumen/list`)
                .then((response) => {
                    this.pilihan_jenis_dokumen = response.data.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        // Fungsi untuk mengunduh dokumen
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
        },
        downloadAllDokumen() {
            // Menyaring dokumen yang sesuai dengan filter
            const dokumenToDownload = this.kumpulan_dokumen.filter(dokumen => {
                const filterSemesterMatch = this.id_semester === 'all' || dokumen.id_semester === this.id_semester;
                const filterJenisDokumenMatch = this.id_jenis_dokumen === 'all' || dokumen.id_jenis_dokumen === this.id_jenis_dokumen;
                return filterSemesterMatch && filterJenisDokumenMatch;
            });

            // Mengunduh dokumen yang telah disaring
            if (dokumenToDownload.length > 0) {
                dokumenToDownload.forEach(dokumen => {
                    const url = `${config.dokumenUrl}storage/${dokumen.file_dokumen}`;
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = dokumen.file_dokumen;  // Nama file yang akan didownload
                    link.click();
                });
                console.log(`${dokumenToDownload.length} dokumen berhasil diunduh.`);
            } else {
                console.error("Tidak ada dokumen yang sesuai dengan filter.");
            }
        },
        // Filter callback
        onFiltered(filteredItems) {
            this.currentPage = 1;
            this.totalRows = filteredItems.length;
        },
    },
    watch: {
        // Memanggil getListDokumen setiap kali filter berubah
        id_semester(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.getListDokumen();
            }
        },
        id_jenis_dokumen(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.getListDokumen();
            }
        },
    }
};
</script>
<style scoped>
/* Styling untuk tabel dokumen */
.dokumen-table {
    border-top: 3px solid rgb(202, 206, 147);
    padding: 10px;
    background-color: rgb(255, 255, 255);
}

/* Styling untuk filter */
.pilihan-semester,
.pilihan-jenis-dokumen,
.pilihan-status {
    margin: auto;
}

/* Styling untuk tabel */
.tabel-dokumen {
    background-color: rgb(255, 255, 255);
    max-height: 60vh;
}

/* Styling untuk tombol */
.btn-form {
    padding: 4px 20px;
    font-size: 15px;
    border-radius: 5px;
    color: rgb(255, 255, 255);
    background-color: rgb(17, 122, 139);
    border: none;
    text-align: center;
}

.btn-form:disabled,
.btn-form:disabled:hover {
    color: rgb(255, 255, 255);
    background-color: rgb(17, 122, 139);
    cursor: default;
}

.btn-form:hover,
.btn-modal:hover {
    font-size: 15px;
    color: rgb(255, 255, 255);
    background-color: rgb(20, 145, 163);
    cursor: pointer;
}

.btn-modal {
    font-size: 15px;
    border-radius: 5px;
    color: rgb(255, 255, 255);
    background-color: rgb(17, 122, 139);
    border: none;
    text-align: center;
    width: 80px;
}

/* Loading style */
.loading-page {
    margin: auto;
}

.loading-text {
    color: rgb(32, 165, 6);
}

.modal-dialog {
    max-width: 90% !important;
    /* Mengatur lebar modal menjadi 90% dari lebar layar */
}

.modal-content {
    height: 80vh;
    /* Mengatur tinggi modal menjadi 80% dari tinggi layar */
}
</style>