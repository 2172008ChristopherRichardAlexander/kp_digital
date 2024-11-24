<template>
  <b-container>
    <div class="form-syarat-ketentuan">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Syarat Dan Ketentuan Sidang</h3>
        </div>
      </div>
      <div class="syarat-ketentuan">
        <!-- // ! Button Select Role -->
        <div class="row btn-select">
          <b-button-group class="col" size="sm" style="padding: 0px">
            <div :key="role.id_role" v-for="role in kumpulan_role">
              <b-button class="btn-radio" v-if="role.id_role == jabatan">{{
                role.nama_role
              }}</b-button>
              <b-button
                class="btn-radio-disable"
                @click="ubahRole(role.id_role)"
                v-else
                >{{ role.nama_role }}</b-button
              >
            </div>
          </b-button-group>
          <div class="col-3 text-right" style="margin: auto; padding: 0px">
            <router-link
              :to="`/sidang/syarat-dan-ketentuan/add`"
              class="btn-form"
              >Tambah Komponen</router-link
            >
          </div>
        </div>
        <!-- // ! Table -->
        <b-table
          sticky-header
          class="tabel-syarat-ketentuan"
          no-border-collapse
          :items="filteredSyaratKetentuan"
          :fields="fields"
          hover
          striped
          responsive="sm"
          :busy="isBusySyarat"
          show-empty
        >
          <template v-slot:table-busy>
            <div class="text-center my-2">
              <ring-loader class="loading-page" color="#20a506" :size="50" />
              <strong class="loading-text">Loading...</strong>
            </div>
          </template>
          <template v-slot:empty>
            <h5 class="text-center">Tidak ada data</h5>
          </template>
          <template v-slot:cell(deskripsi_syarat_ketentuan_sidang)="row">
            <b-button
              size="sm"
              @click="row.toggleDetails"
              class="mr-2 btn-form"
              >{{ row.detailsShowing ? "Tutup" : "Lihat" }}</b-button
            >
          </template>
          <!-- // * Detail Deskripsi -->
          <template v-slot:row-details="row">
            <div class="detail">
              <div
                class="deskripsi"
                v-html="row.item.deskripsi_syarat_ketentuan_sidang"
              ></div>
            </div>
            <b-button class="btn-form" size="sm" @click="row.toggleDetails"
              >Tutup</b-button
            >
          </template>

          <template v-slot:cell(status_syarat_ketentuan_sidang)="data">
            <div v-if="data.item.status_syarat_ketentuan_sidang == 1">
              <b-button v-b-modal.modal-detail class="btn-form"
                >Non-Aktifkan</b-button
              >
              <!-- // * Modal -->
              <div>
                <b-modal
                  id="modal-detail"
                  centered
                  scrollable
                  @ok="updateStatusSyaratKetentuan(data.item, 0)"
                  title="Non-Aktifkan Syarat dan Ketentuan ?"
                >
                  <div
                    class="judul"
                    v-html="data.item.judul_syarat_ketentuan_sidang"
                  ></div>
                  <div
                    class="deskripsi"
                    v-html="data.item.deskripsi_syarat_ketentuan_sidang"
                  ></div>
                  <!-- // * Footer -->
                  <template v-slot:modal-footer="{ ok, cancel }">
                    <!-- Emulate built in modal footer ok and cancel button actions -->
                    <b-button size="sm" class="btn-modal" @click="cancel()"
                      >Tidak</b-button
                    >
                    <b-button size="sm" class="btn-modal" @click="ok()"
                      >Ya</b-button
                    >
                  </template>
                </b-modal>
              </div>
            </div>
            <div v-else>Tidak Aktif</div>
          </template>
        </b-table>
        <!-- </div> -->
        <!-- // ! Pagination -->
        <div class="text-right">
          <b-button
            @click="getPrevSyaratKetentuan()"
            :disabled="links.prev == null"
            class="btn-form"
            >Prev</b-button
          >
          <b-button
            @click="getNextSyaratKetentuan()"
            :disabled="links.next == null"
            class="btn-form"
            >Next</b-button
          >
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
      jabatan: null,
      syarat_ketentuan: [],
      kumpulan_role: [],
      fields: [
        { key: "judul_syarat_ketentuan_sidang", label: "Judul" },
        { key: "deskripsi_syarat_ketentuan_sidang", label: "Deskripsi" },
        { key: "status_syarat_ketentuan_sidang", label: "Status" },
      ],
      links: {},
      loading: false,
      show: true,
      isBusySyarat: true,
    };
  },
  mounted() {
    this.getListRoleSyaratKetentuan();
  },
  methods: {
    getListRoleSyaratKetentuan() {
      this.loading = true;
      Axios.get(`${config.apiKoorKpUrl}/role/syarat-ketentuan`)
        .then((response) => {
          this.kumpulan_role = response.data.data;
          if (!this.jabatan) {
            this.jabatan = this.kumpulan_role[0].id_role;
          }
          this.loading = false;
          this.getListSyaratKetentuan();
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    updateStatusSyaratKetentuan(data, status) {
      this.loading = true;
      Axios.put(
        `${config.apiKoorKpUrl}/syarat-ketentuan`,
        {
          id_syarat_ketentuan_sidang: data.id_syarat_ketentuan_sidang,
          judul_syarat_ketentuan_sidang: data.judul_syarat_ketentuan_sidang,
          deskripsi_syarat_ketentuan_sidang:
            data.deskripsi_syarat_ketentuan_sidang,
          status_syarat_ketentuan_sidang: status,
          id_role: data.id_role,
        },
        // formData,
        {
          headers: {
            "Content-Type": "application/json",
            // "Content-Type": "multipart/form-data"
          },
        }
      )
        .then(() => {
          this.loading = false;
          this.getListRoleSyaratKetentuan();
        })
        .catch(() => {
          this.loading = false;
        });
    },
    getListSyaratKetentuan(
      url = `${config.apiKoorKpUrl}/syarat-ketentuan/` + this.jabatan
    ) {
      this.isBusySyarat = true;
      this.loading = true;
      Axios.get(url)
        .then((response) => {
          this.syarat_ketentuan = response.data.data;
          this.links = response.data.links;
          this.loading = false;
          this.isBusySyarat = false;
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    getPrevSyaratKetentuan() {
      this.getListSyaratKetentuan(this.links.prev);
    },
    getNextSyaratKetentuan() {
      this.getListSyaratKetentuan(this.links.next);
    },
    ubahRole(role) {
      this.jabatan = role;
      this.getListSyaratKetentuan();
    },
  },
  computed: {
    // * Filter Syarat dan Ketentuan Berdasarkan Jabatan
    filteredSyaratKetentuan() {
      if (this.jabatan) {
        return this.syarat_ketentuan.filter((item) => {
          return item.id_role == this.jabatan;
        });
      } else {
        return this.syarat_ketentuan;
      }
    },
  },
};
</script>
<style scoped>
/* // * Syarat Ketentuang */
.syarat-ketentuan {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
/* // * Css Table */
.tabel-syarat-ketentuan {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}
/* // * CSS Button Radio */
.btn-radio {
  font-size: 15px;
  border-radius: 0px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
}

.btn-radio:focus {
  box-shadow: none;
}

.btn-radio:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}

.btn-radio-disable {
  font-size: 15px;
  border-radius: 0px;
  color: rgb(0, 0, 0);
  background-color: rgb(233, 236, 239);
  border: none;
  text-align: center;
}

.btn-radio-disable:hover {
  color: rgb(0, 0, 0);
  background-color: rgb(212, 216, 219);
  cursor: pointer;
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

/* // * CSS Modal */
.judul {
  text-align: center;
  font-weight: bold;
  font-size: 20px;
  margin-bottom: 10px;
}
.deskripsi {
  text-align: justify;
  padding: 10px;
}

/* // * CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>