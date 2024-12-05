<template>
    <b-container>
        <div class="form-mbkm">
            <!-- Title Keterangan Halaman -->
            <div class="row">
                <div class="col">
                    <h3 class="keterangan-website my-2">Jenis MBKM</h3>
                </div>
                <div class="col text-right">
                    <b-button variant="primary" size="sm" @click="showAddModal = true">
                        Tambah MBKM
                    </b-button>
                </div>
                <!-- // ? Pilih Semester -->
            </div>
            <div class="list-mbkm">
                <div class="list-mbkm-item">
                    <b-table sticky-header class="tabel-list-mbkm" ref="tableListMBKM" no-border-collapse
                        :items="kumpulan_mbkm" :fields="fields" hover striped responsive="sm"
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
                                <h5 class="text-muted">Tidak ada MBKM.</h5>
                            </div>
                        </template>
                        <template v-slot:cell(no)="data">
                            {{ (currentPage - 1) * perPage + data.index + 1 }}
                        </template>
                        <template v-slot:cell(action)="data">
                            <b-button variant="warning" size="sm" @click="editMbkm(data.item)">
                                <i class="material-icons">edit</i>
                            </b-button>
                            <b-button variant="danger" size="sm" @click="deleteMbkm(data.item)">
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

        <!-- Modal Edit MBKM -->
        <b-modal id="edit-mbkm-modal" v-model="showEditModal" title="Edit MBKM" @hide="resetSelectedMbkm"
            ok-title="Simpan" cancel-title="Batal" @ok="updateMbkm">
            <b-form @submit.stop.prevent="updateMbkm">
                <b-form-group label="ID MBKM" label-for="idMbkm">
                    <b-form-input id="idMbkm" v-model="selectedMbkm.id_jenis_mbkm" :disabled="true"
                        type="text"></b-form-input>
                </b-form-group>

                <b-form-group label="Nama MBKM" label-for="namaMbkm">
                    <b-form-input id="namaMbkm" v-model="selectedMbkm.nama_mbkm" type="text" required></b-form-input>
                </b-form-group>

                <!-- Tambahkan field lain sesuai kebutuhan -->

            </b-form>
        </b-modal>

        <!-- Modal Tambah MBKM -->
        <b-modal id="add-mbkm-modal" v-model="showAddModal" title="Tambah MBKM" @hide="resetNewMbkm" ok-title="Tambah"
            cancel-title="Batal" @ok="createMbkm">
            <b-form @submit.stop.prevent="createMbkm">
                <b-form-group label="Nama MBKM" label-for="newNamaMbkm">
                    <b-form-input id="newNamaMbkm" v-model="newMbkm.nama_mbkm" type="text" required
                        placeholder="Masukkan Nama MBKM"></b-form-input>
                </b-form-group>
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
            sortBy: "nama",
            sortDesc: false,
            // * Table
            kumpulan_mbkm: [],
            fields: [
                { key: "no", label: "No", sortable: true },
                { key: "id_jenis_mbkm", label: "ID", sortable: true },
                { key: "nama_mbkm", label: "Nama MBKM", sortable: true },
                { key: "action", label: "Action" },
            ],
            isBusyPenjadwalan: true,
            tataUsaha: true,
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
            selectedMbkm: {
                id_jenis_mbkm: '',
                nama_mbkm: '',
            },
            // * Modal Add
            showAddModal: false,
            newMbkm: {
                id_jenis_mbkm: '',
                nama_mbkm: '',
            },
        };
    },
    methods: {
        addMbkm() {
            // Buka modal Tambah MBKM
            this.showAddModal = true;
        },
        editMbkm(item) {
            // Clone item untuk menghindari perubahan langsung pada kumpulan_mbkm sebelum disimpan
            this.selectedMbkm = { ...item };
            this.showEditModal = true;
        },
        // Metode untuk menghapus MBKM
        deleteMbkm(item) {
            if (confirm("Apakah Anda yakin ingin menghapus MBKM ini?")) {
                Axios.delete(`${config.apiUrl}/jenis-mbkm/${item.id_jenis_mbkm}`)
                    .then(() => {
                        this.$bvToast.toast('MBKM berhasil dihapus!', {
                            variant: 'success',
                            solid: true,
                        });
                        this.getListMbkm();  // Refresh data
                    })
                    .catch((error) => {
                        console.error(error);
                        this.$bvToast.toast('Terjadi kesalahan saat menghapus MBKM.', {
                            variant: 'danger',
                            solid: true,
                        });
                    });
            }
        },
        getListMbkm() {
            this.isBusyPenjadwalan = true;
            Axios.get(`${config.apiMahasiswaUrl}/jenis-mbkm`).then((response) => {
                if (response.data.data.length === 0) {
                    this.kumpulan_mbkm = [];
                    this.totalRows = 0;
                } else {
                    this.kumpulan_mbkm = response.data.data;
                    this.totalRows = response.data.data.length;
                }
                this.isBusyPenjadwalan = false;
            })
                .catch((error) => {
                    console.error(error);
                    this.kumpulan_mbkm = [];
                    this.totalRows = 0;
                    this.isBusyPenjadwalan = false;
                });
        },
        resetSelectedMbkm() {
            this.selectedMbkm = {
                nama_mbkm: '',
                // Reset field lain jika ada
            };
        },
        updateMbkm() {
            // Validasi sederhana
            if (!this.selectedMbkm.nama_mbkm) {
                this.$bvToast.toast('Nama MBKM tidak boleh kosong.', {
                    variant: 'warning',
                    solid: true,
                });
                return;
            }

            Axios.put(`${config.apiUrl}/jenis-mbkm`, this.selectedMbkm)
                .then(() => {
                    this.$bvToast.toast('MBKM berhasil diperbarui!', {
                        variant: 'success',
                        solid: true,
                    });
                    this.showEditModal = false;
                    this.getListMbkm();  // Refresh data
                })
                .catch((error) => {
                    console.error(error);
                    this.$bvToast.toast('Terjadi kesalahan saat memperbarui MBKM.', {
                        variant: 'danger',
                        solid: true,
                    });
                });
        },
        resetNewMbkm() {
            this.newMbkm = {
                id_jenis_mbkm: '',
                nama_mbkm: '',
                // Reset field lain jika ada
            };
        },
        createMbkm() {
            // Validasi sederhana
            if (!this.newMbkm.nama_mbkm) {
                this.$bvToast.toast('Nama MBKM tidak boleh kosong.', {
                    variant: 'warning',
                    solid: true,
                });
                return;
            }
            Axios.post(`${config.apiUrl}/jenis-mbkm`, this.newMbkm)
                .then(() => {
                    this.$bvToast.toast('MBKM berhasil ditambahkan!', {
                        variant: 'success',
                        solid: true,
                    });
                    this.showAddModal = false;
                    this.getListMbkm();  // Refresh data
                })
                .catch((error) => {
                    console.error(error);
                    this.$bvToast.toast('Terjadi kesalahan saat menambahkan MBKM.', {
                        variant: 'danger',
                        solid: true,
                    });
                });
        },
    },
    watch: {
        id_semester(newVal, oldVal) {
            if (newVal !== oldVal) {
                this.getListMbkm();
            }
        }
    },
    mounted() {
        this.getListMbkm();
    }
};
</script>

<style scoped>
/* // * Penjadwalan Sidang */
.list-mbkm {
    border-top: 3px solid rgb(202, 206, 147);
    padding: 10px;
    background-color: rgb(255, 255, 255);
}

.pilihan-semester,
.component-filter {
    margin: auto;
}

/* // * Css Table */
.tabel-list-mbkm {
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
