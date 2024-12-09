<template>
    <b-container>
        <div class="form-mata-kuliah">
            <!-- Title Keterangan Halaman -->
            <div class="row">
                <div class="col">
                    <h3 class="keterangan-website my-2">Mata Kuliah</h3>
                </div>
                <div class="col text-right">
                    <b-button variant="primary" size="sm" @click="addMataKuliah">
                        Tambah Mata Kuliah
                    </b-button>
                </div>
                <!-- // ? Pilih Semester -->
            </div>
            <div class="list-mata-kuliah">
                <div class="list-mata-kuliah-item">
                    <b-table sticky-header class="tabel-list-mata-kuliah" ref="tableListMataKuliah" no-border-collapse
                        :items="kumpulan_matakuliah" :fields="fields" hover striped responsive="sm"
                        :current-page="currentPage" :per-page="perPage" :sort-by.sync="sortBy"
                        :sort-desc.sync="sortDesc">
                        <template v-slot:table-busy>
                            <div class="text-center my-2">
                                <ring-loader class="loading-page" color="#20a506" :size="50" />
                                <strong class="loading-text">Loading...</strong>
                            </div>
                        </template>
                        <template v-slot:empty>
                            <div class="text-center">
                                <h5 class="text-muted">Tidak ada Mata Kuliah.</h5>
                            </div>
                        </template>
                        <template v-slot:cell(no)="data">
                            {{ (currentPage - 1) * perPage + data.index + 1 }}
                        </template>
                        <template v-slot:cell(action)="data">
                            <b-button variant="warning" size="sm" @click="editMataKuliah(data.item)">
                                <i class="material-icons">edit</i>
                            </b-button>
                            <b-button variant="danger" size="sm" @click="deleteMataKuliah(data.item)">
                                <i class="material-icons">delete</i>
                            </b-button>
                        </template>
                    </b-table>
                    <b-row>
                        <!-- // ? Pagination -->
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
        </div>

        <!-- Modal Edit Mata Kuliah -->
        <b-modal id="edit-mata-kuliah-modal" v-model="showEditModal" title="Edit Mata Kuliah"
            @hide="resetSelectedMataKuliah" ok-title="Simpan" cancel-title="Batal" @ok="updateMataKuliah">
            <b-form @submit.stop.prevent="updateMataKuliah">
                <b-form-group label="ID Mata Kuliah" label-for="idMatakuliah">
                    <b-form-input id="idMatakuliah" v-model="selectedMataKuliah.id_matakuliah" :disabled="true"
                        type="text"></b-form-input>
                </b-form-group>

                <b-form-group label="Nama Mata Kuliah" label-for="namaMatakuliah">
                    <b-form-input id="namaMatakuliah" v-model="selectedMataKuliah.nama_matakul" type="text" required
                        placeholder="Masukkan Nama Mata Kuliah"></b-form-input>
                </b-form-group>

                <b-form-group label="Jumlah SKS" label-for="jumlahSks">
                    <b-form-input id="jumlahSks" v-model="selectedMataKuliah.jumlah_sks" type="number" min="1" required
                        placeholder="Masukkan Jumlah SKS"></b-form-input>
                </b-form-group>

                <b-form-group label="Jenis Semester" label-for="jenisSemester">
                    <b-form-select id="jenisSemester" v-model="selectedMataKuliah.id_jenis_semester"
                        :options="jenisSemesterOptions" required placeholder="Pilih Jenis Semester"></b-form-select>
                </b-form-group>

                <b-form-group label="Status" label-for="status">
                    <b-form-checkbox id="status" v-model="selectedMataKuliah.status" :true-value="1" :false-value="0">
                        Aktif
                    </b-form-checkbox>
                </b-form-group>

                <!-- Tambahkan field lain sesuai kebutuhan -->
            </b-form>
        </b-modal>

        <!-- Modal Tambah Mata Kuliah -->
        <b-modal id="add-mata-kuliah-modal" v-model="showAddModal" title="Tambah Mata Kuliah" @hide="resetNewMataKuliah"
            ok-title="Tambah" cancel-title="Batal" @ok="createMataKuliah">
            <b-form @submit.stop.prevent="createMataKuliah">
                <b-form-group label="ID Mata Kuliah" label-for="newIdNamaMatakuliah">
                    <b-form-input id="newIdNamaMatakuliah" v-model="newMataKuliah.id_matakuliah" type="number" required
                        placeholder="Masukkan ID Mata Kuliah"></b-form-input>
                </b-form-group>
                <b-form-group label="Nama Mata Kuliah" label-for="newNamaMatakuliah">
                    <b-form-input id="newNamaMatakuliah" v-model="newMataKuliah.nama_matakul" type="text" required
                        placeholder="Masukkan Nama Mata Kuliah"></b-form-input>
                </b-form-group>

                <b-form-group label="Jumlah SKS" label-for="newJumlahSks">
                    <b-form-input id="newJumlahSks" v-model="newMataKuliah.jumlah_sks" type="number" min="1" required
                        placeholder="Masukkan Jumlah SKS"></b-form-input>
                </b-form-group>

                <b-form-group label="Jenis Semester" label-for="newJenisSemester">
                    <b-form-select id="newJenisSemester" v-model="newMataKuliah.id_jenis_semester"
                        :options="jenisSemesterOptions" required placeholder="Pilih Jenis Semester"></b-form-select>
                </b-form-group>

                <b-form-group label="Status" label-for="newStatus">
                    <b-form-checkbox id="newStatus" v-model="newMataKuliah.status" :true-value="1" :false-value="0">
                        Aktif
                    </b-form-checkbox>
                </b-form-group>

                <!-- Tambahkan field lain sesuai kebutuhan -->
            </b-form>
        </b-modal>
    </b-container>
</template>

<script>
import Axios from "axios";
import config from "../../../config";
import '@mdi/font/css/materialdesignicons.css';

export default {
    data() {
        return {
            // * Filter
            filter: null,
            // * Pagination
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15, 20, 25, 30, 35, 40, 45, 50],
            // * Sorting
            sortBy: "nama_matakul",
            sortDesc: false,
            // * Table
            kumpulan_matakuliah: [],
            fields: [
                { key: "no", label: "No", sortable: true },
                { key: "id_matakuliah", label: "ID", sortable: true },
                { key: "nama_matakul", label: "Nama Mata Kuliah", sortable: true },
                { key: "jumlah_sks", label: "SKS", sortable: true },
                { key: "id_jenis_semester.nama_semester", label: "Semester", sortable: true },
                { key: "action", label: "Action" },
            ],
            isBusyMataKuliah: true,
            loading: false,
            id_semester: null,
            pilihan_semester: [],
            semester: null,
            isBusySemester: true,
            infoModal: {
                id: "info-modal",
                title: "",
                content: "",
            },
            // * Modal Edit
            showEditModal: false,
            selectedMataKuliah: {
                id_matakuliah: '',
                nama_matakul: '',
                jumlah_sks: '',
                id_jenis_semester: '',
                status: 1, // default aktif
            },
            // * Modal Add
            showAddModal: false,
            newMataKuliah: {
                id_matakuliah: '',
                nama_matakul: '',
                jumlah_sks: '',
                id_jenis_semester: '',
                status: 1, // default aktif
            },
            // * Jenis Semester
            jenisSemesterList: [],
        };
    },
    computed: {
        jenisSemesterOptions() {
            if (Array.isArray(this.jenisSemesterList)) {
                return this.jenisSemesterList.map(item => ({
                    value: item.id_jenis_semester,
                    text: item.nama_semester,
                }));
            }
            return [];
        }
    },

    methods: {
        addMataKuliah() {
            // Buka modal Tambah Mata Kuliah
            this.showAddModal = true;
        },
        editMataKuliah(item) {
            // Clone item untuk menghindari perubahan langsung pada kumpulan_matakuliah sebelum disimpan
            this.selectedMataKuliah = { ...item };
            this.showEditModal = true;
        },
        // Metode untuk menghapus Mata Kuliah
        deleteMataKuliah(item) {
            if (confirm("Apakah Anda yakin ingin menghapus Mata Kuliah ini?")) {
                Axios.delete(`${config.apiUrl}/matakuliah/${item.id_matakuliah}`)
                    .then(() => {
                        this.$bvToast.toast('Mata Kuliah berhasil dihapus!', {
                            variant: 'success',
                            solid: true,
                        });
                        this.getListMataKuliah();  // Refresh data
                    })
                    .catch((error) => {
                        console.error(error);
                        this.$bvToast.toast('Terjadi kesalahan saat menghapus Mata Kuliah.', {
                            variant: 'danger',
                            solid: true,
                        });
                    });
            }
        },
        getListMataKuliah() {
            this.isBusyMataKuliah = true;
            Axios.get(`${config.apiMahasiswaUrl}/matakuliah`)
                .then((response) => {
                    if (response.data.data.length === 0) {
                        this.kumpulan_matakuliah = [];
                        this.totalRows = 0;
                    } else {
                        this.kumpulan_matakuliah = response.data.data;
                        this.totalRows = response.data.data.length;
                    }
                    this.isBusyMataKuliah = false;
                })
                .catch((error) => {
                    console.error(error);
                    this.kumpulan_matakuliah = [];
                    this.totalRows = 0;
                    this.isBusyMataKuliah = false;
                });
        },
        getJenisSemester() {
            Axios.get(`${config.apiUrl}/jenis-semester`)
                .then((response) => {
                    // Sesuaikan berdasarkan struktur respon yang sebenarnya
                    this.jenisSemesterList = response.data.data || response.data;
                })
                .catch((error) => {
                    console.error("Error fetching jenis_semester:", error);
                    this.jenisSemesterList = [];
                });
        },

        resetSelectedMataKuliah() {
            this.selectedMataKuliah = {
                id_matakuliah: '',
                nama_matakul: '',
                jumlah_sks: '',
                id_jenis_semester: '',
                status: 1,
            };
        },
        updateMataKuliah() {
            // Validasi sederhana
            if (
                !this.selectedMataKuliah.nama_matakul ||
                !this.selectedMataKuliah.jumlah_sks ||
                !this.selectedMataKuliah.id_jenis_semester
            ) {
                this.$bvToast.toast('Nama, Jumlah SKS, dan Jenis Semester tidak boleh kosong.', {
                    variant: 'warning',
                    solid: true,
                });
                return;
            }

            Axios.put(`${config.apiUrl}/matakuliah`, this.selectedMataKuliah)
                .then((res) => {
                    this.$bvToast.toast('Mata Kuliah berhasil diperbarui!', {
                        variant: 'success',
                        solid: true,
                    });
                    this.showEditModal = false;
                    this.getListMataKuliah();  // Refresh data
                })
                .catch((error) => {
                    console.error(error);
                    this.$bvToast.toast('Terjadi kesalahan saat memperbarui Mata Kuliah.', {
                        variant: 'danger',
                        solid: true,
                    });
                });
        },
        resetNewMataKuliah() {
            this.newMataKuliah = {
                nama_matakul: '',
                jumlah_sks: '',
                id_jenis_semester: '',
                status: 1,
            };
        },
        createMataKuliah() {
            // Validasi sederhana
            if (
                !this.newMataKuliah.nama_matakul ||
                !this.newMataKuliah.jumlah_sks ||
                !this.newMataKuliah.id_jenis_semester
            ) {
                this.$bvToast.toast('Nama, Jumlah SKS, dan Jenis Semester tidak boleh kosong.', {
                    variant: 'warning',
                    solid: true,
                });
                return;
            }

            Axios.post(`${config.apiUrl}/matakuliah`, this.newMataKuliah)
                .then(() => {
                    this.$bvToast.toast('Mata Kuliah berhasil ditambahkan!', {
                        variant: 'success',
                        solid: true,
                    });
                    this.showAddModal = false;
                    this.getListMataKuliah();  // Refresh data
                })
                .catch((error) => {
                    console.error(error);
                    this.$bvToast.toast('Terjadi kesalahan saat menambahkan Mata Kuliah.', {
                        variant: 'danger',
                        solid: true,
                    });
                });
        },
    },
    watch: {
        id_semester(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.getListMataKuliah();
            }
        }
    },
    mounted() {
        this.getListMataKuliah();
        this.getJenisSemester();
    }
};
</script>

<style scoped>
/* // * Penjadwalan Sidang */
.list-mata-kuliah {
    border-top: 3px solid rgb(202, 206, 147);
    padding: 10px;
    background-color: rgb(255, 255, 255);
}

.pilihan-semester,
.component-filter {
    margin: auto;
}

/* // * Css Table */
.tabel-list-mata-kuliah {
    background-color: rgb(255, 255, 255);
    max-height: 60vh;
}

/* // * Css Button */
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

/* // ! CSS Icon */
.i-icon {
    font-size: 15px;
}

.icon-remove {
    color: red;
    font-size: 21px;
}

.icon-update {
    color: white;
}

/* // ! CSS Loading Page */
.loading-page {
    margin: auto;
}

.loading-text {
    color: rgb(32, 165, 6);
}
</style>
