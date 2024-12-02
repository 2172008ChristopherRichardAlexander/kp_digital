<template>
    <b-container>
        <div class="form-mbkm">
            <!-- Title Keterangan Halaman -->
            <div class="row">
                <div class="col">
                    <h3 class="keterangan-website my-2">Jenis MBKM</h3>
                </div>
                <div class="col text-right">
                    <b-button variant="primary" size="sm" @click="addMbkm">
                        Tambah MBKM
                    </b-button>
                </div>
                <!-- // ? Pilih Semester -->
            </div>
            <div class="list-mbkm">
                <div class="list-mbkm-item">
                    <b-table sticky-header class="tabel-list-mbkm" ref="tablePenjadwalanSidang" no-border-collapse
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
                                Edit
                            </b-button>
                            <b-button variant="danger" size="sm" @click="deleteMbkm(data.item)">
                                Delete
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
    </b-container>
</template>

<script>
import Axios from "axios";
import config from "../../../config";

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
        };
    },
    methods: {
        addMbkm() {
            this.$router.push({ name: 'TambahMBKM' });  // Pastikan sudah ada rute 'TambahMBKM' di router
        },
        editMbkm(item) {
            // Implementasikan logika edit
            this.$router.push({ name: 'EditMBKM', params: { id: item.id_jenis_mbkm } });
        },
        // Metode untuk menghapus MBKM
        deleteMbkm(item) {
            if (confirm("Apakah Anda yakin ingin menghapus MBKM ini?")) {
                Axios.delete(`${config.apiMahasiswaUrl}/jenis-mbkm/${item.id_jenis_mbkm}`)
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
            Axios.get(`${config.apiMahasiswaUrl}/jenis-mbkm`).then((response) => {
                if (response.data.data.length === 0) {
                    this.kumpulan_mbkm = [];
                } else {
                    this.kumpulan_mbkm = response.data.data;
                }
            })
                .catch((error) => {
                    console.error(error);
                    this.kumpulan_mbkm = [];
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