<template>
  <b-container>
    <!-- // ? Aktif -->
    <div class="form-komponen-penilaian-aktif">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">Komponen Penilaian Sidang Aktif</h3>
        </div>
      </div>
      <div class="komponen-penilaian">
        <div class="row btn-select">
          <b-button-group class="col" size="sm">
            <div
              :key="role_penilaian.id_role"
              v-for="role_penilaian in kumpulan_role_penilaian"
            >
              <b-button
                class="btn-radio"
                v-if="role_penilaian.id_role == role"
                >{{ role_penilaian.nama_role }}</b-button
              >
              <b-button
                class="btn-radio-disable"
                @click="ubahRole(role_penilaian.id_role)"
                v-else
                >{{ role_penilaian.nama_role }}</b-button
              >
            </div>
          </b-button-group>
          <div class="col-3 text-right">
            <router-link
              :to="`/sidang/komponen-penilaian/add`"
              class="btn btn-form"
              >Tambah Komponen</router-link
            >
          </div>
        </div>
        <b-table
          sticky-header
          class="tabel-komponen-penilaian-aktif"
          no-border-collapse
          :items="filteredKomponenPenilaianAktif"
          :fields="fields_aktif"
          hover
          striped
          responsive="sm"
          :busy="isBusyAktif"
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
          <template v-slot:table-colgroup="scope">
            <col
              v-for="field in scope.fields_aktif"
              :key="field.key"
              :style="{ width: field.key === 'bobot' ? '100px' : '' }"
            />
          </template>
          <template v-slot:cell(bobot)="data" style="width: 100px">{{
            parseFloat(data.item.bobot_komponen_penilaian * 100).toFixed(1)
          }}</template>

          <template v-slot:cell(status_komponen_penilaian)="row">
            <b-button
              @click="info(row.item, row.index, $event.target)"
              class="mr-1 btn-form"
              >Non-Aktifkan</b-button
            >
          </template>

          <template slot="bottom-row">
            <td colspan="2" class="text-right">Total</td>
            <td colspan="2">{{ totalBobot }}</td>
          </template>
        </b-table>
        <!-- // * Modal -->
        <div>
          <b-modal
            :id="infoModal.id"
            centered
            scrollable
            @ok="updateStatusKomponenPenilaian(infoModal.content, 0)"
            @cancel="resetInfoModal"
            title="Non-Aktifkan Komponen Penilaian ?"
          >
            <div
              class="judul"
              v-html="infoModal.content.judul_komponen_penilaian"
            ></div>
            <div
              class="deskripsi"
              v-html="infoModal.content.deskripsi_komponen_penilaian"
            ></div>
            <!-- // * Footer -->
            <template v-slot:modal-footer="{ ok, cancel }">
              <!-- Emulate built in modal footer ok and cancel button actions -->
              <b-button size="sm" class="btn-modal" @click="cancel()"
                >Tidak</b-button
              >
              <b-button size="sm" class="btn-modal" @click="ok()">Ya</b-button>
            </template>
          </b-modal>
        </div>
      </div>
    </div>
    <!-- // ? History -->
    <div class="form-komponen-penilaian-history">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="my-2">History Komponen Penilaian Sidang</h3>
        </div>
      </div>
      <div class="komponen-penilaian">
        <div class="row btn-select">
          <b-button-group class="col" size="sm">
            <div
              :key="role_penilaian.id_role"
              v-for="role_penilaian in kumpulan_role_penilaian"
            >
              <b-button
                class="btn-radio"
                v-if="role_penilaian.id_role == role"
                >{{ role_penilaian.nama_role }}</b-button
              >
              <b-button
                class="btn-radio-disable"
                @click="ubahRole(role_penilaian.id_role)"
                v-else
                >{{ role_penilaian.nama_role }}</b-button
              >
            </div>
          </b-button-group>
          <div class="col-3 text-right">
            <router-link
              :to="`/sidang/komponen-penilaian/add`"
              class="btn btn-form"
              >Tambah Komponen</router-link
            >
          </div>
        </div>
        <!-- <div :key="role_penilaian.id_role" v-for="role_penilaian in kumpulan_role_penilaian"> -->
        <b-table
          sticky-header
          class="tabel-komponen-penilaian"
          no-border-collapse
          :items="filteredHistoryKomponenPenilaian"
          :fields="fields_history"
          hover
          striped
          responsive="sm"
          :busy="isBusyHistory"
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
          <template v-slot:table-colgroup="scope">
            <col
              v-for="field in scope.fields_history"
              :key="field.key"
              :style="{ width: field.key === 'bobot' ? '100px' : '' }"
            />
          </template>
          <template v-slot:cell(bobot)="data" style="width: 100px">{{
            parseFloat(data.item.bobot_komponen_penilaian * 100).toFixed(1)
          }}</template>
        </b-table>
        <!-- </div> -->
        <div class="text-right">
          <b-button
            @click="getPrevKomponenPenilaian()"
            :disabled="links.prev == null"
            class="btn-form"
            >Prev</b-button
          >
          <b-button
            @click="getNextKomponenPenilaian()"
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
      role: "",
      komponen_penilaian_history: [],
      komponen_penilaian_aktif: [],
      kumpulan_role_penilaian: [],
      fields_aktif: [
        { key: "judul_komponen_penilaian", label: "Judul" },
        { key: "deskripsi_komponen_penilaian", label: "Deskripsi" },
        { key: "bobot", label: "Bobot (%)" },
        { key: "status_komponen_penilaian", label: "Status" },
      ],
      fields_history: [
        { key: "judul_komponen_penilaian", label: "Judul" },
        { key: "deskripsi_komponen_penilaian", label: "Deskripsi" },
        { key: "bobot", label: "Bobot (%)" },
      ],
      links: {},
      loading: false,
      show: true,
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
      totalBobot: 0,
      isBusyAktif: true,
      isBusyHistory: true,
    };
  },
  mounted() {
    this.getListRoleKomponenPenilaian();
  },
  methods: {
    getListRoleKomponenPenilaian() {
      this.loading = true;
      Axios.get(`${config.apiKoorKpUrl}/role/komponen-penilaian`)
        .then((response) => {
          this.kumpulan_role_penilaian = response.data.data;
          if (!this.role) {
            this.role = this.kumpulan_role_penilaian[0].id_role;
          }
          this.loading = false;
          this.getListKomponenPenilaianAktif();
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    getListKomponenPenilaianAktif() {
      this.isBusyAktif = true;
      this.loading = true;
      const formData = new FormData();
      formData.append("id_role", this.role);
      formData.append("status_komponen_penilaian", 1);
      Axios.post(
        `${config.apiKoorKpUrl}/komponen-penilaian-role-status`,
        formData
      )
        .then((response) => {
          this.loading = false;
          this.komponen_penilaian_aktif = response.data.data;
          this.isBusyAktif = false;
          this.getListHistoryKomponenPenilaian();
        })
        .catch((response) => {
          this.isBusyAktif = false;
          this.loading = false;
          console.log(response);
        });
    },
    getListHistoryKomponenPenilaian(
      url = `${config.apiKoorKpUrl}/komponen-penilaian/` + this.role
    ) {
      this.isBusyHistory = true;
      this.loading = true;
      Axios.get(url)
        .then((response) => {
          this.isBusyHistory = false;
          this.komponen_penilaian_history = response.data.data;
          this.links = response.data.links;
          this.loading = false;
        })
        .catch((response) => {
          this.isBusyHistory = false;
          this.loading = false;
          console.log(response);
        });
    },
    getPrevKomponenPenilaian() {
      this.getListHistoryKomponenPenilaian(this.links.prev);
    },
    getNextKomponenPenilaian() {
      this.getListHistoryKomponenPenilaian(this.links.next);
    },
    ubahRole(role) {
      this.totalBobot = 0;
      this.role = role;
      this.getListKomponenPenilaianAktif();
    },
    updateStatusKomponenPenilaian(data, status) {
      this.loading = true;
      Axios.put(
        `${config.apiKoorKpUrl}/komponen-penilaian`,
        {
          id_komponen_penilaian: data.id_komponen_penilaian,
          judul_komponen_penilaian: data.judul_komponen_penilaian,
          deskripsi_komponen_penilaian: data.deskripsi_komponen_penilaian,
          bobot_komponen_penilaian: data.bobot_komponen_penilaian,
          status_komponen_penilaian: status,
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
          this.getListRoleKomponenPenilaian();
        })
        .catch(() => {
          this.loading = false;
        });
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
    hitungTotalBobot(bobot) {
      this.totalBobot += bobot;
    },
  },
  computed: {
    // * Filter Komponen Penilaian Berdasarkan Role
    filteredHistoryKomponenPenilaian() {
      if (this.role) {
        return this.komponen_penilaian_history.filter((item) => {
          return item.id_role == this.role;
        });
      } else {
        return this.komponen_penilaian_history;
      }
    },
    filteredKomponenPenilaianAktif() {
      if (this.role) {
        return this.komponen_penilaian_aktif.filter((item) => {
          return item.id_role == this.role;
        });
      } else {
        return this.komponen_penilaian_aktif;
      }
    },
  },
  watch: {
    komponen_penilaian_aktif: function (val) {
      val.forEach((element) => {
        this.hitungTotalBobot(
          parseFloat(
            parseFloat(element.bobot_komponen_penilaian * 100).toFixed(1)
          )
        );
      });
    },
  },
};
</script>
<style scoped>
/* // * Penjadwalan Sidang */
.komponen-penilaian {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

/* // * Css Table */
.tabel-komponen-penilaian {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}

.tabel-komponen-penilaian-aktif {
  background-color: rgb(255, 255, 255);
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