<template>
  <b-container>
    <div class="form-penjadwalan-sidang">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Penjadwalan Sidang</h3>
        </div>
        <!-- // ? Pilih Batch -->
        <div class="col-3 pilihan-batch">
          <b-form-select v-model="id_batch" size="sm">
            <template v-slot:first>
              <option :value="null" disabled>-- Pilih Batch --</option>
            </template>
            <option
              v-for="pilihan in pilihan_batch"
              :key="pilihan.id_batch + 'a'"
              :value="pilihan.id_batch"
            >
              {{ pilihan.nama_batch }}
            </option>
          </b-form-select>
        </div>
        <!-- // ? Filter -->
        <div class="col-3" style="margin: auto">
          <b-form-group label-size="sm" label-for="filterInput" class="mb-0">
            <b-input-group size="sm">
              <b-form-input
                v-model="filter"
                type="search"
                id="filterInput"
                placeholder="Ketik untuk mencari"
              ></b-form-input>
              <b-input-group-append>
                <b-button
                  class="btn-form"
                  :disabled="!filter"
                  @click="filter = ''"
                  >Hapus</b-button
                >
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </div>
        <!-- // ? ######################################################################## -->
      </div>
      <div class="penjadwalan-sidang">
        <div class="penjadwalan-sidang-item">
          <b-table
            sticky-header
            class="tabel-penjadwalan-sidang"
            ref="tablePenjadwalanSidang"
            no-border-collapse
            :items="kumpulan_sidang"
            :fields="fields"
            hover
            striped
            responsive="sm"
            :busy="isBusyPenjadwalan"
            show-empty
            :current-page="currentPage"
            :per-page="perPage"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :filter="filter"
            @filtered="onFiltered"
          >
            <template v-slot:table-busy>
              <div class="text-center my-2">
                <ring-loader class="loading-page" color="#20a506" :size="50" />
                <strong class="loading-text">Loading...</strong>
              </div>
            </template>
            <template v-slot:empty>
              <h5 class="text-center">Tidak ada data untuk penjadwalan</h5>
            </template>
            <template v-slot:cell(nama)="data">{{ data.item.nama }}</template>
            <template v-slot:cell(tanggal_sidang)="data">{{
              data.item.tanggal_sidang
            }}</template>
            <template v-slot:cell(waktu_sidang)="data">
              <template
                v-if="
                  data.item.waktu_mulai_sidang && data.item.waktu_akhir_sidang
                "
                >{{ data.item.waktu_mulai_sidang.substr(0, 5) }} -
                {{ data.item.waktu_akhir_sidang.substr(0, 5) }}</template
              >
              <template v-else>-</template>
            </template>
            <template v-slot:cell(penjadwalan)="data">
              <router-link
                :to="`/penjadwalan/sidang/${acakAcak(data.item.id_sidang, 0)}`"
                class="btn btn-form"
                v-if="tataUsaha"
                >Jadwalkan</router-link
              >
              <router-link
                :to="`/sidang/penjadwalan/${acakAcak(data.item.id_sidang, 0)}`"
                class="btn btn-form"
                v-else
                >Jadwalkan</router-link
              >
            </template>
            <template v-slot:cell(aksi)="row" v-if="!tataUsaha">
              <button
                @click="info(row.item, row.index, $event.target)"
                class="btn-form"
              >
                <i class="fa fa-remove i-icon icon-remove"></i>
              </button>
            </template>
          </b-table>
          <b-row>
            <!-- // ? Pagination -->
            <b-col>
              <b-row>
                <b-col>
                  <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="mb-0"
                  ></b-pagination>
                </b-col>
                <b-col lg="2">
                  <b-form-select
                    v-model="perPage"
                    id="perPageSelect"
                    size="sm"
                    :options="pageOptions"
                  ></b-form-select>
                </b-col>
              </b-row>
            </b-col>
            <!-- // ? ######################################################################## -->
            <b-col class="text-right">&nbsp;</b-col>
          </b-row>
        </div>
        <b-modal
          :id="infoModal.id"
          centered
          scrollable
          @ok="hapusSidangMahasiswa(infoModal.content.id_sidang)"
          @cancel="resetInfoModal"
          title="Hapus Sidang Mahasiswa"
          v-if="!tataUsaha"
        >
          <b-table-simple borderless v-if="infoModal.content">
            <b-tbody>
              <b-tr>
                <b-td class="label-form">NRP</b-td>
                <b-td class="text-form">{{
                  infoModal.content.topik.id_pengaju
                }}</b-td>
              </b-tr>
              <b-tr>
                <b-td class="label-form">Nama</b-td>
                <b-td class="text-form">{{ infoModal.content.nama }}</b-td>
              </b-tr>
              <b-tr>
                <b-td class="label-form">Judul Topik</b-td>
                <b-td class="text-form">{{
                  infoModal.content.topik.judul_topik
                }}</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <!-- // * Footer -->
          <template v-slot:modal-footer="{ ok, cancel }">
            <!-- Emulate built in modal footer ok and cancel button actions -->
            <b-button size="sm" class="btn-modal" @click="cancel()"
              >Batal</b-button
            >
            <b-button size="sm" class="btn-modal" @click="ok()">Hapus</b-button>
          </template>
        </b-modal>
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
      kumpulan_sidang: [],
      fields: [
        { key: "topik.id_pengaju", label: "NRP", sortable: true },
        { key: "nama", sortable: true },
        { key: "tanggal_sidang", sortable: true },
        { key: "waktu_sidang", sortable: true },
        { key: "ruangan_sidang", sortable: true },
        { key: "penjadwalan", sortable: false },
      ],
      isBusyPenjadwalan: true,
      tataUsaha: true,
      loading: false,
      id_batch: null,
      pilihan_batch: [],
      id_semester: null,
      semester: null,
      isBusySemester: true,
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
    };
  },
  mounted() {
    this.tataUsaha = this.cekJabatan("Tata Usaha");
    this.getListSemester();
  },
  methods: {
    cekJabatan(jabatan) {
      return this.$store.getters.jabatan.includes(jabatan);
    },
    getListSemester() {
      this.isBusySemester = true;
      Axios.get(`${config.apiUrl}/semester/aktif`)
        .then((response) => {
          this.semester = response.data;
          this.id_semester = this.semester.id_semester;
          this.isBusySemester = false;
          this.getListBatch();
        })
        .catch((response) => {
          this.isBusySemester = false;
        });
    },
    getListBatch() {
      this.loading = true;
      const formData = new FormData();
      if (this.$store.getters.listBatchSidang) {
        this.pilihan_batch = this.$store.getters.listBatchSidang;
        this.getNewestBatch();
      } else {
        const jenis_batch = 2;
        formData.append("jenis_batch", jenis_batch);
        formData.append("status_batch", [0, 1]);
        this.$store
          .dispatch("listBatch", {
            data_batch: formData,
            jenis_batch: jenis_batch,
          })
          .then((response) => {
            this.pilihan_batch = this.$store.getters.listBatchSidang;
            this.getNewestBatch();
          });
      }
    },
    getNewestBatch() {
      this.loading = true;
      for (let index = 0; index < this.pilihan_batch.length; index++) {
        const element = this.pilihan_batch[index];
        if (element.semester.id_semester == this.id_semester) {
          this.id_batch = element.id_batch;
          break;
        }
        if (index == this.pilihan_batch.length - 1) {
          this.id_batch = this.pilihan_batch[0].id_batch;
        }
      }
    },
    getKumpulanSidang() {
      this.isBusyPenjadwalan = true;
      this.loading = true;
      Axios.post(
        `${config.apiKoorKpUrl}/list-pengajuan-sidang`,
        {
          id_batch: this.id_batch,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.kumpulan_sidang = response.data.data;
          this.totalRows = response.data.data.length;
          this.getListMahasiswa();
          this.loading = false;
        })
        .catch((response) => {
          this.loading = false;
        });
    },
    getListMahasiswa() {
      if (this.$store.getters.listMahasiswa) {
        this.changeDataTopik();
      } else {
        this.$store.dispatch("listMahasiswa").then((response) => {
          this.changeDataTopik();
        });
      }
    },
    cekNamaMahasiswa(nrp) {
      const listMahasiswa = this.$store.getters.listMahasiswa;
      for (let index = 0; index < listMahasiswa.length; index++) {
        const element = listMahasiswa[index];
        if (element.UserId == nrp) {
          return element.Name;
        }
      }
    },
    changeDataTopik() {
      for (let index = 0; index < this.kumpulan_sidang.length; index++) {
        const element = this.kumpulan_sidang[index];
        // Axios.get(
        //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
        // element.topik.id_pengaju
        // ).then(response => {
        // element.nama = response.data[0].Name;
        element.nama = this.cekNamaMahasiswa(element.topik.id_pengaju);
        this.$refs.tablePenjadwalanSidang.refresh();
        // });
        if (index == this.kumpulan_sidang.length - 1)
          this.$refs.tablePenjadwalanSidang.refresh();
      }
      if (!this.tataUsaha) this.fields.push({ key: "aksi", sortable: false });
      this.isBusyPenjadwalan = false;
    },
    info(item, index, button) {
      this.infoModal.title = `Row index: ${index}`;
      this.infoModal.content = item;
      this.$root.$emit("bv::show::modal", this.infoModal.id, button);
    },
    resetInfoModal() {
      this.infoModal.title = "";
      this.infoModal.content = "";
    },
    hapusSidangMahasiswa(id_sidang) {
      const formData = new FormData();
      formData.append("id_sidang", id_sidang);
      Axios.post(`${config.apiKoorKpUrl}/sidang/delete`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }).then((response) => {
        this.getKumpulanSidang();
      });
    },
    acakAcak(isi, status) {
      isi = Array.from(String(isi));
      var code = "";
      if (status == 0) {
        isi.forEach((element) => {
          if (element == "0") {
            code = code + "c";
          } else if (element == "1") {
            code = code + "h";
          } else if (element == "2") {
            code = code + "r";
          } else if (element == "3") {
            code = code + "i";
          } else if (element == "4") {
            code = code + "s";
          } else if (element == "5") {
            code = code + "t";
          } else if (element == "6") {
            code = code + "a";
          } else if (element == "7") {
            code = code + "n";
          } else if (element == "8") {
            code = code + "o";
          } else if (element == "9") {
            code = code + "7";
          }
        });
      } else if (status == 1) {
        isi.forEach((element) => {
          if (element == "c") {
            code = code + "0";
          } else if (element == "h") {
            code = code + "1";
          } else if (element == "r") {
            code = code + "2";
          } else if (element == "i") {
            code = code + "3";
          } else if (element == "s") {
            code = code + "4";
          } else if (element == "t") {
            code = code + "5";
          } else if (element == "a") {
            code = code + "6";
          } else if (element == "n") {
            code = code + "7";
          } else if (element == "o") {
            code = code + "8";
          } else if (element == "7") {
            code = code + "9";
          }
        });
      }
      return code;
    },
    // ? Filter
    onFiltered(filteredItems) {
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
  },
  watch: {
    id_batch: function (newValue, oldValue) {
      if (newValue != oldValue) {
        this.getKumpulanSidang();
      }
    },
  },
};
</script>
<style scoped>
/* // * Penjadwalan Sidang */
.penjadwalan-sidang {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.pilihan-batch,
.component-filter {
  margin: auto;
}

/* // * Css Table */
.tabel-penjadwalan-sidang {
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