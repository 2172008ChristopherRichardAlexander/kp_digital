<template>
  <div>
    <div class="detail-jabatan">
      <!-- // ? Tambah Jabatan -->
      <div v-if="options" class="mb-2">
        <b-form inline>
          <b-form-select v-model="id_jabatan" :options="options" size="sm">
            <!-- This slot appears above the options from 'options' prop -->
            <template v-slot:first>
              <option :value="null" disabled>
                -- Pilih Jabatan Pengguna --
              </option>
            </template>
          </b-form-select>
          <b-button
            class="btn-form"
            @click="insertJabatanPengguna"
            v-if="id_jabatan"
            >Tambah</b-button
          >
          <b-button class="btn-form" disabled v-else>Tambah</b-button>
        </b-form>
      </div>
      <div v-else>
        <div class="text-center my-2">
          <ring-loader class="loading-page" color="#20a506" :size="50" />
          <strong class="loading-text">Loading...</strong>
        </div>
      </div>
      <!-- // ? ####################################################################### -->
      <!-- // ? List Jabatan -->
      <b-table
        sticky-header
        class="table-jabatan"
        ref="tableJabatan"
        hover
        striped
        :items="jabatan_pengguna"
        :fields="fields_jabatan"
        responsive="sm"
        :busy="isBusyJabatan"
        show-empty
      >
        <template v-slot:table-busy>
          <div class="text-center my-2">
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
        </template>
        <template v-slot:empty>
          <h5 class="text-center">Tidak ada jabatan</h5>
        </template>
        <template v-slot:cell(hapus)="data">
          <b-button class="btn-form" @click="hapusJabatan(data.item)"
            >Hapus</b-button
          >
        </template>
      </b-table>
      <!-- // ? ####################################################################### -->
      <!-- // ? Modal Migrasi KBK -->
      <b-modal
        id="modal-hapus-jabatan"
        ref="modal-hapus-jabatan"
        size="sm"
        centered
        title="Hapus Jabatan Pengguna"
        @ok="deleteJabatanPengguna"
      >
        <div v-if="jabatan_hapus">
          Apakah anda yakin ingin menghapus jabatan, Sebagai:
          <p>{{ jabatan_hapus.jabatan.nama_jabatan }}</p>
        </div>
        <template v-slot:modal-footer="{ cancel }">
          <!-- Emulate built in modal footer ok and cancel button actions -->
          <b-button size="sm" class="btn-form" @click="cancel">Batal</b-button>
          <!-- <b-button size="sm" class="btn-form" @click="ok()">Update</b-button> -->
          <b-button size="sm" class="btn-form" @click="deleteJabatanPengguna">
            Hapus
          </b-button>
        </template>
      </b-modal>
      <!-- // ? ####################################################################### -->
    </div>
  </div>
</template>
<script>
import Axios from "axios";
import config from "../config";
export default {
  data() {
    return {
      id_jabatan: null,
      list_jabatan: [],
      options: null,
      jabatan_pengguna: [],
      isBusyJabatan: false,
      isBusyList: false,
      fields_jabatan: [
        { key: "jabatan.nama_jabatan", label: "Nama Jabatan" },
        "hapus",
      ],
      jabatan_hapus: null,
    };
  },
  mounted() {
    this.getJabatanPengguna();
    this.getListJabatan();
  },
  methods: {
    getJabatanPengguna() {
      this.isBusyJabatan = true;
      const formData = new FormData();
      formData.append("id_pengguna", this.id_pengguna);
      Axios.post(`${config.apiUrl}/jabatan-pengguna/list`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      }).then((response) => {
        this.jabatan_pengguna = response.data.data;
        this.isBusyJabatan = false;
      });
    },
    getListJabatan() {
      this.isBusyList = true;
      Axios.post(`${config.apiUrl}/jabatan/list`, { status_jabatan: 1 })
        .then((response) => {
          this.list_jabatan = response.data.data;
          this.makeOptions();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    makeOptions() {
      this.options = [];
      this.list_jabatan.forEach((element) => {
        this.options.push({
          value: element.id_jabatan,
          text: element.nama_jabatan,
        });
        if (this.list_jabatan.length == this.options.length) {
          this.isBusyList = false;
        }
      });
    },
    hapusJabatan(hapus_jabatan) {
      this.jabatan_hapus = hapus_jabatan;
      this.$refs["modal-hapus-jabatan"].show();
    },
    deleteJabatanPengguna() {
      const formData = new FormData();
      formData.append(
        "id_jabatan_pengguna",
        this.jabatan_hapus.id_jabatan_pengguna
      );
      Axios.post(`${config.apiUrl}/jabatan-pengguna/delete`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
        .then((response) => {
          this.$refs["modal-hapus-jabatan"].hide();
          this.getJabatanPengguna();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    insertJabatanPengguna() {
      const formData = new FormData();
      formData.append("id_pengguna", this.id_pengguna);
      formData.append("id_jabatan", this.id_jabatan);
      if (this.id_jabatan) {
        Axios.post(`${config.apiUrl}/jabatan-pengguna/insert`, formData, {
          headers: { "Content-Type": "multipart/form-data" },
        })
          .then((response) => {
            this.getJabatanPengguna();
            this.id_jabatan = null;
          })
          .catch((response) => {
            console.log(response);
          });
      }
    },
  },
  props: ["id_pengguna"],
};
</script>
<style scoped>
.detail-jabatan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
/* // * CSS Table */
.table-jabatan {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}

/* // * Css Button */
.btn-select {
  margin: 10px 0px 10px 0px;
}
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

.btn-form:hover {
  font-size: 15px;
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>