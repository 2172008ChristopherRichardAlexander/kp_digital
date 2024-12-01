<template>
  <div class="row">
    <div class="col-6">
      <div class="row">
        <div class="col text-left">
          <h4 class="my-1 text-left judul">Detail Topik</h4>
        </div>
        <div class="col text-right">
          <router-link
            :to="`/sidang/revisi/${acakAcak(sidang.id_sidang, 0)}`"
            class="btn btn-form"
            v-if="sidang.status_sidang == 1 || sidang.status_sidang === 'Lulus'"
            >Revisi</router-link
          >
          <div class="btn btn-form-disabled disabled" v-else>Revisi</div>
        </div>
      </div>
      <div class="text-center my-2" v-if="loading">
        <ring-loader class="loading-page" color="#20a506" :size="50" />
        <strong class="loading-text">Loading...</strong>
      </div>
      <b-table-simple v-else>
        <b-tbody>
          <b-tr>
            <b-td>Judul</b-td>
            <b-td>:</b-td>
            <b-td>{{ topik.judul_topik }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>Ruangan Sidang</b-td>
            <b-td>:</b-td>
            <b-td>{{ sidang.ruangan_sidang }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>Tanggal Sidang</b-td>
            <b-td>:</b-td>
            <b-td>{{ sidang.tanggal_sidang }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>Waktu Sidang</b-td>
            <b-td>:</b-td>
            <b-td
              >{{ sidang.waktu_mulai_sidang }} -
              {{ sidang.waktu_akhir_sidang }}</b-td
            >
          </b-tr>
          <b-tr>
            <b-td>Status Sidang</b-td>
            <b-td>:</b-td>
            <b-td>{{ sidang.status_sidang }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>Pembimbing Utama</b-td>
            <b-td>:</b-td>
            <b-td>{{ topik.id_pembimbing }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>Pembimbing Lapangan</b-td>
            <b-td>:</b-td>
            <b-td>{{ topik.id_pembimbing_lapangan }}</b-td>
          </b-tr>
          <b-tr>
            <b-td>Penguji Sidang</b-td>
            <b-td>:</b-td>
            <b-td>{{ sidang.id_penguji_sidang }}</b-td>
          </b-tr>
          <b-tr v-if="sidang.id_penguji_sidang_dua != '-'">
            <b-td>Penguji Sidang Dua</b-td>
            <b-td>:</b-td>
            <b-td>{{ sidang.id_penguji_sidang_dua }}</b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
      <!-- // ! Jadwal Mahasiswa -->
      <!-- Title Jadwal Mahasiswa -->
      <div class="sidang-mahasiswa-item jadwal-mahasiswa">
        <div class="row">
          <div class="col text-left">
            <h4 class="keterangan-website">Daftar Jadwal</h4>
          </div>
          <div class="col text-right">
            <router-link
              :to="`/sidang/jadwal-mahasiswa/${this.$route.params.id}`"
              class="btn btn-form"
              v-if="batch && batch.status_batch == 1"
              >Tambah Jadwal</router-link
            >
            <div class="btn btn-form-disabled disabled" v-else>
              Tambah Jadwal
            </div>
          </div>
        </div>

        <!-- // ? Table Jadwal Mahasiswa  -->
        <b-table
          sticky-header
          class="tabel-jadwal-mahasiswa"
          no-border-collapse
          :items="jadwal"
          :fields="fields"
          hover
          striped
          responsive="sm"
          :busy="isBusyJadwal"
          show-empty
        >
          <template v-slot:table-busy>
            <div class="text-center my-2">
              <ring-loader class="loading-page" color="#20a506" :size="50" />
              <strong class="loading-text">Loading...</strong>
            </div>
          </template>
          <template v-slot:empty>
            <h6 class="text-center">Tidak ada data jadwal</h6>
          </template>
          <template v-slot:cell(jadwal)="data"
            >{{ data.item.hari_jadwal_mahasiswa }},
            {{ data.item.waktu_mulai_jadwal_mahasiswa.substr(0, 5) }} -
            {{
              data.item.waktu_selesai_jadwal_mahasiswa.substr(0, 5)
            }}</template
          >
          <template v-slot:cell(aksi)="row">
            <!-- // TODO: Delete,Update Jadwal -->
            <router-link
              :to="`/sidang/update-jadwal-mahasiswa/${acakAcak(
                row.item.id_jadwal_mahasiswa,
                0
              )}`"
              class="btn-form"
            >
              <i class="fa fa-pencil i-icon icon-update"></i>
            </router-link>
            <b-button
              @click="info(row.item, row.index, $event.target)"
              class="btn-form-table"
            >
              <i class="fa fa-remove i-icon icon-remove"></i>
            </b-button>
          </template>
        </b-table>
        <div class="jadwal-mahasiswa-item">
          <div class="row">
            <div class="col text-left">Tambahkan jadwal mata kuliah</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6">
      <h4 class="my-1 text-center judul">Tahapan Sidang Kerja Praktek</h4>
      <h5 class="my-1 judul">Tahap Sidang</h5>
      <div class="pengajuan-topik">
        <Stepper
          v-for="(step, index) in steps_pengajuan_sidang"
          :key="step.id_step"
          :detail="step"
          :status="sidang.status_sidang"
          :icon="index + 1"
        />
      </div>
      <h5 class="my-1 judul">Tahap Revisi</h5>
      <div class="pengajuan-topik">
        <Stepper
          v-for="(step, index) in steps_revisi"
          :key="step.id_step"
          :detail="step"
          :status="sidang.status_revisi"
          :icon="index + 1"
        />
      </div>
    </div>
    <!-- // * Modal -->
    <b-modal
      :id="infoModal.id"
      centered
      scrollable
      @ok="hapusJadwalMahasiswa(infoModal.content.id_jadwal_mahasiswa)"
      @cancel="resetInfoModal"
      title="Hapus Jadwal Berhalangan"
    >
      <b-table-simple borderless v-if="infoModal.content">
        <b-tbody>
          <b-tr>
            <b-td class="label-form">Jadwal</b-td>
            <b-td class="text-form"
              >{{ infoModal.content.hari_jadwal_mahasiswa }},
              {{ infoModal.content.waktu_mulai_jadwal_mahasiswa.substr(0, 5) }}
              -
              {{
                infoModal.content.waktu_selesai_jadwal_mahasiswa.substr(0, 5)
              }}</b-td
            >
          </b-tr>
          <b-tr>
            <b-td class="label-form">Keterangan</b-td>
            <b-td class="text-form">{{
              infoModal.content.keterangan_jadwal_mahasiswa
            }}</b-td>
          </b-tr>
        </b-tbody>
      </b-table-simple>
      <!-- // * Footer -->
      <template v-slot:modal-footer="{ ok, cancel }">
        <!-- Emulate built in modal footer ok and cancel button actions -->
        <b-button size="sm" class="btn-modal" @click="cancel()">Batal</b-button>
        <b-button size="sm" class="btn-modal" @click="ok()">Hapus</b-button>
      </template>
    </b-modal>
  </div>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import Stepper from "../../../components/Stepper";
export default {
  components: {
    Stepper,
  },
  data() {
    return {
      steps_pengajuan_sidang: [
        {
          id_step: -2,
          judul: "menyetujui syarat dan ketentuan",
          isi: "",
        },
        {
          id_step: -1,
          judul: "mengajukan sidang",
          isi: "",
        },
        {
          id_step: 2,
          judul: "menunggu persetujuan pembimbing",
          isi: "",
        },
        {
          id_step: 3,
          judul: "menentukan jadwal berhalangan, menunggu penjadwalan",
          isi: "",
        },
        {
          id_step: 4,
          judul: "sidang",
          isi: "",
        },
        {
          id_step: 5,
          judul: "revisi",
          isi: "",
        },
      ],
      steps_revisi: [
        {
          id_step: -1,
          judul: "upload foto revisi dan/atau menuliskan text revisi",
          isi: "",
        },
        {
          id_step: 3,
          judul: "melakukan revisi",
          isi: "",
        },
        {
          id_step: 2,
          judul: "mengajukan penyelesaian revisi",
          isi: "",
        },
        {
          id_step: 4,
          judul: "revisi diterima dosen pembimbing",
          isi: "",
        },
        {
          id_step: 5,
          judul: "revisi diterima dosen penguji",
          isi: "",
        },
      ],
      jadwal: [],
      fields: [
        "jadwal",
        // { key: "hari_jadwal_mahasiswa", label: "Hari" },
        // { key: "waktu_mulai_jadwal_mahasiswa", label: "Waktu Mulai" },
        // { key: "waktu_selesai_jadwal_mahasiswa", label: "Waktu Selesai" },
        { key: "keterangan_jadwal_mahasiswa", label: "Keterangan" },
        "aksi",
      ],
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
      isBusyJadwal: true,
      loading: false,
    };
  },
  mounted() {
    this.changeName();
    this.getJadwalMahasiswa();
  },
  methods: {
    getJadwalMahasiswa() {
      var hari = [
        "Senin",
        "Selasa",
        "Rabu",
        "Kamis",
        "Jumat",
        "Sabtu",
        "Minggu",
      ];
      this.isBusyJadwal = true;
      Axios.post(
        `${config.apiMahasiswaUrl}/list-jadwal-mahasiswa`,
        {
          id_topik: this.topik.id_topik,
          id_batch: this.batch.id_batch,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.jadwal = response.data.data;
          this.jadwal.forEach((element) => {
            if (
              element.hari_jadwal_mahasiswa >= 1 &&
              element.hari_jadwal_mahasiswa <= 7
            )
              element.hari_jadwal_mahasiswa =
                hari[element.hari_jadwal_mahasiswa - 1];
            else element.hari_jadwal_mahasiswa = "";
          });
          this.isBusyJadwal = false;
        })
        .catch((response) => {
          this.loading = false;
          this.isBusyJadwal = false;
        });
    },
    hapusJadwalMahasiswa(id_jadwal_mahasiswa) {
      const formData = new FormData();
      formData.append("id_jadwal_mahasiswa", id_jadwal_mahasiswa);
      Axios.post(
        `${config.apiMahasiswaUrl}/jadwal-mahasiswa/delete`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      ).then((response) => {
        this.getJadwalMahasiswa();
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
    changeName() {
      this.loading = true;
      if (this.topik.id_pembimbing) {
        Axios.get(
          `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
            this.topik.id_pembimbing
        ).then((response) => {
          this.topik.id_pembimbing = response.data[0].Name;
        });
      } else {
        this.topik.id_pembimbing = "-";
      }
      if (this.topik.id_pembimbing_lapangan) {
        Axios.get(
          `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
            this.topik.id_pembimbing_lapangan
        ).then((response) => {
          this.topik.id_pembimbing_lapangan = response.data[0].Name;
        });
      } else {
        this.topik.id_pembimbing_lapangan = "-";
      }
      if (this.sidang.id_penguji_sidang) {
        Axios.get(
          `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
            this.sidang.id_penguji_sidang
        ).then((response) => {
          this.sidang.id_penguji_sidang = response.data[0].Name;
        });
      } else {
        this.sidang.id_penguji_sidang = "-";
      }
      if (this.sidang.id_penguji_sidang_dua) {
        Axios.get(
          `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
            this.sidang.id_penguji_sidang_dua
        ).then((response) => {
          this.sidang.id_penguji_sidang_dua = response.data[0].Name;
        });
      } else {
        this.sidang.id_penguji_sidang_dua = "-";
      }
      // if (!!this.topik.id_penguji_sidang) {
      //   Axios.get(
      //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //       this.topik.id_penguji_sidang
      //   ).then(response => {
      //     this.topik.id_penguji_sidang = response.data[0].Name;
      //   });
      // } else {
      //   this.topik.id_penguji_sidang = "-";
      // }
      // if (!!this.topik.id_penguji_sidang_dua) {
      //   Axios.get(
      //     `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //       this.topik.id_penguji_sidang_dua
      //   ).then(response => {
      //     this.topik.id_penguji_sidang_dua = response.data[0].Name;
      //   });
      // } else {
      //   this.topik.id_penguji_sidang_dua = "-";
      // }
      this.topik.status_topik = this.cekStatus(this.topik.status_topik);
      this.sidang.status_sidang = this.cekStatusSidang(
        this.sidang.status_sidang
      );
      // if (this.sidang) {
      // }
      this.loading = false;
    },
    cekStatusSidang(index) {
      const status = [
        "Tidak Lulus",
        "Lulus",
        "Menunggu Persetujuan",
        "Sidang Sedang Diajukan Penjadwalan",
        "Sidang Sudah di jadwalankan",
      ];
      if (index >= 0 && index < 5) {
        return status[index];
      } else {
        return "";
      }
      // if (index == 0) {
      //   return "Tidak Lulus";
      // } else if (index == 1) {
      //   return "Lulus";
      // } else if (index == 2) {
      //   return "Menunggu Persetujuan";
      // } else if (index == 3) {
      //   return "Sidang Sedang Diajukan Penjadwalan";
      // } else if (index == 4) {
      //   return "Sidang Sudah di jadwalankan";
      // } else {
      //   return "";
      // }
    },
    cekStatus(status) {
      if (status == 0) {
        return "ditolak Koordinator KP / Dosen";
      } else if (status == 1) {
        return "diterima Koordinator KP";
      } else if (status == 2) {
        return "sedang diajukan";
      } else if (status == 3) {
        return "menunggu persetujuan dosen";
      } else if (status == 4) {
        return "ditolak Koordinator KBK";
      } else if (status == 5) {
        return "diterima Koordinator KBK";
      } else if (status == 6) {
        return "topik dapat di Revisi";
      } else if (status == 7) {
        return "masa sidang";
      } else if (status == 8) {
        return "topik tidak aktif";
      } else {
        return "";
      }
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
  },
  props: ["topik", "sidang", "batch"],
};
</script>
<style scoped>
/* // * Judul Topik */
.judul {
  font-weight: bold;
}
.md-card {
  width: 320px;
  margin: 4px;
  display: inline-block;
  vertical-align: top;
}
.pengajuan-topik,
.bimbingan,
.sidang {
  padding-left: 20px;
}

/* // * Css Tabel */
.tabel-jadwal-mahasiswa,
.table-histori-bimbingan {
  margin: 0px 0px 0px 0px;
  background-color: rgb(243, 249, 215);
  max-height: 40vh;
  white-space: pre-wrap;
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
  width: 195px;
}

.btn-form:disabled,
.btn-form:disabled:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  cursor: default;
}
.btn-form-disabled,
.btn-form-disabled:hover {
  padding: 4px 20px;
  font-size: 15px;
  border-radius: 5px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  cursor: default;
  width: 195px;
}

.btn-form:hover,
.btn-modal:hover,
.btn-form-table:hover {
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

.btn-form-table {
  margin: 0px;
  padding: 4px 20px;
  font-size: 10px;
  border-radius: 5px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
}

.btn-form-table:disabled,
.btn-form-table:disabled:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  cursor: default;
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

.text-form {
  white-space: pre-wrap;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>