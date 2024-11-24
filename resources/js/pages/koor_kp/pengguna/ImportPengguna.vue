<template>
  <b-container>
    <div class="judul-website">
      <h3 class="keterangan-website mt-2">Import Data Pengguna</h3>
    </div>
    <div class="isi-website">
      <section>
        <b-form-file
          v-model="file"
          :state="Boolean(file)"
          placeholder="Choose a file or drop it here..."
          drop-placeholder="Drop file here..."
        ></b-form-file>
        <!-- <input type="file" @change="onChange" /> -->
        <xlsx-read :file="file">
          <xlsx-json>
            <template #default="{ collection }">
              <div class="d-none" ref="dataPengguna">
                {{ collection }}
              </div>
            </template>
          </xlsx-json>
        </xlsx-read>
      </section>
      <b-button
        block
        class="btn-form my-2"
        :disabled="file == null"
        @click="getDataPengguna"
      >
        Lihat Data
      </b-button>
      <b-table
        sticky-header
        :current-page="currentPage"
        :per-page="perPage"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        class="table-pengguna"
        ref="tablePengguna"
        hover
        striped
        :items="collection"
        :fields="fields"
        responsive="sm"
        show-empty
      >
        <template v-slot:table-busy>
          <div class="text-center my-2">
            <ring-loader class="loading-page" color="#20a506" :size="50" />
            <strong class="loading-text">Loading...</strong>
          </div>
        </template>
        <template v-slot:empty>
          <h5 class="text-center">Tidak ada data pengguna</h5>
        </template>

        <!-- // ? Action
        <template v-slot:cell(action)="data">
          <b-button class="btn-form" @click="ubahData()">
            Ubah
          </b-button>
        </template> -->
        <!-- // ? ######################################################################## -->
      </b-table>
      <!-- // ? Pagination -->
      <b-pagination
        v-model="currentPage"
        :total-rows="totalRows"
        :per-page="perPage"
        align="fill"
        size="sm"
        class="mb-2"
      ></b-pagination>
      <div>
        <b-row>
          <b-col class="h4">Role Pengguna</b-col>
        </b-row>
        <b-row>
          <b-col
            ><b-form-select
              v-model="id_pengguna_role"
              :options="roles_options"
            ></b-form-select
          ></b-col>
        </b-row>
        <b-row class="my-2">
          <b-col class="text-right">
            <button
              type="submit"
              @click="onSubmit"
              class="btn btn-form"
              :disabled="
                !collection || collection.length <= 0 || !id_pengguna_role
              "
            >
              Simpan Data
            </button>
          </b-col>
        </b-row>
      </div>
    </div>
  </b-container>
</template>

<script>
import Axios from "axios";
import config from "../../../config";
export default {
  name: "ImportPengguna",
  data() {
    return {
      file: null,
      collection: null,
      // * Pagination
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      // * Sorting
      sortBy: "NRP",
      sortDesc: false,
      // * Table
      fields: [
        { key: "kode_pengguna", label: "Kode Pengguna", sortable: true },
        { key: "nama_pengguna", label: "Nama Pengguna", sortable: true },
        // { key: "action", sortable: false },
      ],
      id_pengguna_role: 3,
      roles_options: [
        {
          value: 2,
          text: "Dosen",
        },
        {
          value: 3,
          text: "Mahasiswa",
        },
      ],
      isLoading: false,
    };
  },
  methods: {
    onChange(event) {
      this.file = event.target.files ? event.target.files[0] : null;
    },
    getDataPengguna() {
      this.collection = JSON.parse(this.$refs.dataPengguna.innerHTML);
      this.totalRows = this.collection.length;
    },
    resetData() {
      this.isLoading = false;
      this.file = null;
      this.collection = null;
      this.totalRows = 1;
      this.currentPage = 1;
    },
    onSubmit() {
      this.isLoading = true;
      const formData = new FormData();

      formData.append("data_pengguna", JSON.stringify(this.collection));
      formData.append("id_pengguna_role", this.id_pengguna_role);
      Axios.post(`${config.apiKoorKpUrl}/Users/register-pengguna`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          if (response.data.message == "Success") {
            this.resetData();
            this.makeToast("Success", "Pengguna Berhasil Ditambahkan");
          }
        })
        .catch((response) => {
          this.isLoading = false;
        });
    },
    makeToast(title = "", message = "", variant = "success", append = false) {
      this.$bvToast.toast(`${message}`, {
        title: `${title}`,
        variant: variant,
        autoHideDelay: 5000,
        appendToast: append,
      });
    },
  },
};
</script>
<style scoped>
.isi-website {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
/* // * CSS Table */
.table-pengguna {
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

.btn-form:hover {
  font-size: 15px;
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}
</style>