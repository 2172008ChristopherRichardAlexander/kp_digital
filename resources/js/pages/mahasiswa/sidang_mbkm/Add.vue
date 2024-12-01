<template>
  <b-container>
    <div class="form-sidang-mahasiswa">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website">Pengajuan Sidang</h3>
        </div>
      </div>
      <!-- // ! Sidang Mahasiswa -->
      <div v-if="loading_page">
        <ring-loader class="loading-page" color="#bada55" :loading="loading_page" :size="150" />
      </div>
      <div class="sidang-mahasiswa" v-else>
        <template v-if="sidang_topik.length != 0">
          <DetailPengajuan :topik="topik" :sidang="sidang_topik" :batch="batch" />
        </template>
        <template v-else>
          <!-- // ! Pengajuan Sidang -->
          <div class="sidang-mahasiswa-item pengajuan-sidang">
            <div v-if="isBusySyarat">
              <div class="text-center my-2">
                <ring-loader class="loading-page" color="#20a506" :size="50" />
                <strong class="loading-text">Loading...</strong>
              </div>
            </div>
            <div class="row" v-else>
              <div class="col text-left">
                <h4 class="keterangan-website">Pernyataan Mahasiswa</h4>
                <div class="surat-pernyataan">
                  <h5 class="surat-pernyataan-title text-center">
                    {{
                      syarat_ketentuan_mahasiswa.judul_syarat_ketentuan_sidang
                    }}
                  </h5>
                  <div class="surat-pernyataan-isi" v-html="syarat_ketentuan_mahasiswa.deskripsi_syarat_ketentuan_sidang
                    "></div>
                  <div class="check-persetujuan-surat-pernyataan">
                    <b-form-checkbox id="checkbox-persetujuan-surat-pernyataan" v-model="menyetujui_syarat"
                      name="checkbox-persetujuan-surat-pernyataan" value="accepted" unchecked-value="not_accepted"
                      :disabled="menyetujui_syarat == 'accepted'">Saya menyetujui
                      {{
                        syarat_ketentuan_mahasiswa.judul_syarat_ketentuan_sidang
                      }}</b-form-checkbox>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- // ! Jadwal dan Bimbingan -->
          <div class="row">
            <div class="col-6">
              <!-- // ! Jadwal Mahasiswa -->
              <!-- Title Jadwal Mahasiswa -->
              <div class="sidang-mahasiswa-item jadwal-mahasiswa">
                <div class="row">
                  <div class="col text-left">
                    <h4 class="keterangan-website">Daftar Jadwal</h4>
                  </div>
                  <div class="col text-right">
                    <router-link :to="`/sidang/jadwal-mahasiswa/${this.$route.params.id}`" class="btn btn-form"
                      v-if="batch && batch.status_batch == 1">Tambah Jadwal</router-link>
                    <b-button class="btn-form" disabled v-else>Tambah Jadwal</b-button>
                  </div>
                </div>

                <!-- // ? Table Jadwal Mahasiswa  -->
                <b-table sticky-header class="tabel-jadwal-mahasiswa" no-border-collapse :items="jadwal"
                  :fields="fields" hover striped responsive="sm" :busy="isBusyJadwal" show-empty>
                  <template v-slot:table-busy>
                    <div class="text-center my-2">
                      <ring-loader class="loading-page" color="#20a506" :size="50" />
                      <strong class="loading-text">Loading...</strong>
                    </div>
                  </template>
                  <template v-slot:empty>
                    <h6 class="text-center">Tidak ada data jadwal</h6>
                  </template>
                  <template v-slot:cell(jadwal)="data">{{ data.item.hari_jadwal_mahasiswa }},
                    {{ data.item.waktu_mulai_jadwal_mahasiswa.substr(0, 5) }} -
                    {{
                      data.item.waktu_selesai_jadwal_mahasiswa.substr(0, 5)
                    }}</template>
                  <template v-slot:cell(aksi)="row">
                    <!-- // TODO: Delete,Update Jadwal -->
                    <router-link :to="`/sidang/update-jadwal-mahasiswa/${acakAcak(
                      row.item.id_jadwal_mahasiswa,
                      0
                    )}`" class="btn-form">
                      <i class="fa fa-pencil i-icon icon-update"></i>
                    </router-link>

                    <b-button @click="info(row.item, row.index, $event.target)" class="btn-form-table">
                      <i class="fa fa-remove i-icon icon-remove"></i>
                    </b-button>
                  </template>
                </b-table>
                <div class="jadwal-mahasiswa-item">
                  <div class="row">
                    <div class="col text-left">
                      Tambahkan jadwal mata kuliah
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <!-- // ! Bimbingan Mahasiswa -->
              <div class="sidang-mahasiswa-item bimbingan-mahasiswa">
                <div class="row">
                  <div class="col text-left">
                    <h4 class="keterangan-website">Daftar Bimbingan</h4>
                  </div>
                  <div class="col text-right">
                    <router-link :to="`/bimbingan/${this.$route.params.id}`"
                      class="btn btn-form">Bimbingan</router-link>
                  </div>
                </div>

                <!-- // ? Table Bimbingan Mahasiswa  -->
                <b-table sticky-header class="table-histori-bimbingan" ref="tabelHistory" no-border-collapse hover
                  striped :items="bimbingan" :fields="fields_bimbingan" responsive="sm" :busy="isBusyHistory"
                  show-empty>
                  <template v-slot:table-busy>
                    <div class="text-center my-2">
                      <ring-loader class="loading-page" color="#20a506" :size="50" />
                      <strong class="loading-text">Loading...</strong>
                    </div>
                  </template>
                  <template v-slot:empty>
                    <h6 class="text-center">Tidak ada data bimbingan</h6>
                  </template>
                </b-table>

                <div class="bimbingan-mahasiswa-item">
                  <div class="row">
                    <div class="col text-left">
                      Minimal Bimbingan: {{ minimal_bimbingan }}
                    </div>
                    <div class="col text-right">
                      Jumlah Bimbingan: {{ jumlah_bimbingan }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- // ! Tombol Ajukan -->
          <!-- <div class="pengajuan-sidang-item">
            <div class="keterangan-pengajuan-sidang feedback-validasi-false">
              <p>
                <span v-if="!$v.jumlah_bimbingan.minValue">
                  *Jumlah Bimbingan Masih Kurang
                  <br />
                </span>
                <span v-if="menyetujui_syarat == 'not_accepted'">
                  *Pernyataan Mahasiswa harus di setujui
                  <br />
                </span>

                <span v-if="!batch || batch.status_batch == 0">*Batch Pengajuan Sidang Belum di Buka</span>
              </p>
            </div>

            <b-btn class="btn-form" :disabled="!status_pengajuan" @click="ajukanSidang">
              {{ loading ? "" : "Ajukan" }}
              <ring-loader class="loading-page" color="white" :size="25" v-if="loading" />
            </b-btn>
          </div> -->
          <!-- // * Modal -->
          <b-modal :id="infoModal.id" centered scrollable
            @ok="hapusJadwalMahasiswa(infoModal.content.id_jadwal_mahasiswa)" @cancel="resetInfoModal"
            title="Hapus Jadwal Berhalangan">
            <b-table-simple borderless v-if="infoModal.content">
              <b-tbody>
                <b-tr>
                  <b-td class="label-form">Jadwal</b-td>
                  <b-td class="text-form">{{ infoModal.content.hari_jadwal_mahasiswa }},
                    {{
                      infoModal.content.waktu_mulai_jadwal_mahasiswa.substr(
                        0,
                        5
                      )
                    }}
                    -
                    {{
                      infoModal.content.waktu_selesai_jadwal_mahasiswa.substr(
                        0,
                        5
                      )
                    }}</b-td>
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
        </template>
        <div class="sidang-mahasiswa-item pengajuan-sidang">
          <div class="row">
            <div class="col">
              <h4 class="keterangan-website">Unggah Laporan dan Logbook</h4>

              <!-- Input File Laporan (PDF) -->
              <div class="form-pengajuan-item">
                <b-row>
                  <b-col class="label-form">Laporan (PDF)</b-col>
                  <b-col class="input-form-file">
                    <b-form-file
                      v-model="laporanFile"
                      :state="laporanFile ? true : false"
                      accept=".pdf"
                      placeholder="Pilih file laporan (.pdf)"
                    />
                  </b-col>
                  <b-col class="feedback-validasi">
                    <span class="feedback-validasi-true" v-if="laporanFile">
                      &#10004; {{ laporanFile.name }}
                    </span>
                    <span class="feedback-validasi-false" v-else>
                      &#10006; 
                    </span>
                  </b-col>
                </b-row>
              </div>

              <!-- Input File Logbook (CSV) -->
              <div class="form-pengajuan-item">
                <b-row>
                  <b-col class="label-form">Logbook (CSV)</b-col>
                  <b-col class="input-form-file">
                    <b-form-file
                      v-model="logbookFile"
                      :state="logbookFile ? true : false"
                      accept=".csv"
                      placeholder="Pilih file logbook (.csv)"
                    />
                  </b-col>
                  <b-col class="feedback-validasi">
                    <span class="feedback-validasi-true" v-if="logbookFile">
                      &#10004; {{ logbookFile.name }}
                    </span>
                    <span class="feedback-validasi-false" v-else>
                      &#10006; 
                    </span>
                  </b-col>
                </b-row>
              </div>
            </div>
          </div>
        </div>

        <!-- Tombol Ajukan -->
        <div class="pengajuan-sidang-item">
          <div class="keterangan-pengajuan-sidang feedback-validasi-false">
            <p>
              <span v-if="menyetujui_syarat == 'not_accepted'">
                *Pernyataan Mahasiswa harus di setujui
              </span>
              <span v-if="!laporanFile">
                *Laporan belum diupload
              </span>
              <span v-if="!logbookFile">
                *Logbook belum diupload
              </span>
            </p>
          </div>
          <b-btn
            class="btn-form"
            :disabled="!laporanFile || !logbookFile || menyetujui_syarat == 'not_accepted'"
            @click="ajukanSidang"
          >
            {{ loading ? "" : "Ajukan" }}
            <ring-loader
              class="loading-page"
              color="white"
              :size="25"
              v-if="loading"
            />
          </b-btn>
        </div>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import { minValue } from "vuelidate/lib/validators";
import DetailPengajuan from "./DetailPengajuan";
export default {
  components: {
    DetailPengajuan,
  },
  data() {
    return {
      jadwal: [],
      topik: {},
      fields: [
        "jadwal",
        // { key: "hari_jadwal_mahasiswa", label: "Hari" },
        // { key: "waktu_mulai_jadwal_mahasiswa", label: "Waktu Mulai" },
        // { key: "waktu_selesai_jadwal_mahasiswa", label: "Waktu Selesai" },
        { key: "keterangan_jadwal_mahasiswa", label: "Keterangan" },
        "aksi",
      ],
      fields_bimbingan: [
        { key: "tanggal_bimbingan", label: "Tanggal" },
        { key: "deskripsi_bimbingan", label: "Deskripsi" },
        { key: "status_bimbingan", label: "Status" },
      ],
      bimbingan: [],
      jumlah_bimbingan: null,
      syarat_ketentuan_mahasiswa: {},
      syarat_ketentuan_dosen: {},
      isBusyHistory: true,
      isBusyJadwal: true,
      isBusySyarat: true,
      batch: null,
      komponen_nilai: null,
      jenis_sidang: {},
      minimal_bimbingan: null,
      menyetujui_syarat: "not_accepted",
      sidang_topik: [],
      loading_page: true,
      loading: false,
      status_pengajuan: false,
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
      statusAjukanSidang: false,
      statusAjukanKomponen: false,
    };
  },
  mounted() {
    this.getTopik();
    this.getSyaratKetentuanMahasiswaAktif();
    this.getSyaratKetentuanDosenAktif();
  },
  validations() {
    return {
      jumlah_bimbingan: {
        minValue: minValue(this.minimal_bimbingan),
      },
    };
  },
  methods: {
    getTopik() {
      this.loading = true;
      this.loading_page = true;
      Axios.get(`${config.apiMahasiswaUrl}/topik/${this.$route.params.id}`)
        .then((response) => {
          this.topik = response.data.data;
          this.getJumlahBimbingan();
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    getSyaratKetentuanMahasiswaAktif() {
      this.isBusySyarat = true;
      Axios.get(`${config.apiUrl}/syarat-ketentuan-aktif/13`)
        .then((response) => {
          this.syarat_ketentuan_mahasiswa = response.data.data[0];

          this.isBusySyarat = false;
        })
        .catch((response) => {
          this.isBusySyarat = false;
          console.log(response);
        });
    },
    getSyaratKetentuanDosenAktif() {
      this.isBusySyarat = true;
      Axios.get(`${config.apiUrl}/syarat-ketentuan-aktif/5`)
        .then((response) => {
          this.syarat_ketentuan_dosen = response.data.data[0];
          this.isBusySyarat = false;
        })
        .catch((response) => {
          this.isBusySyarat = false;
          console.log(response);
        });
    },
    getSidangTopik() {
      this.loading = true;
      Axios.post(
        `${config.apiUrl}/sidang-topik`,
        {
          id_topik: this.topik.id_topik,
          id_batch: this.batch.id_batch,
          id_komponen_nilai: this.komponen_nilai.id_komponen_nilai,
          id_jenis_sidang: this.jenis_sidang.id_jenis_sidang,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.loading_page = false;
          if (response.data.data.length != 0) {
            this.sidang_topik = response.data.data[0];
            this.changeData();
          }
          this.loading = false;
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    changeData() {
      // this.sidang_topik.id_penguji_sidang = "";
      // this.sidang_topik.id_penguji_sidang_dua = "";
      // this.sidang_topik.status_sidang = this.cekStatusSidang(
      //   this.sidang_topik.status_sidang
      // );
      this.sidang_topik.status_pembimbing_utama = this.cekStatusPembimbingUtama(
        this.sidang_topik.status_pembimbing_utama
      );
      this.sidang_topik.status_pembimbing_lapangan =
        this.cekStatusPembimbingLapangan(
          this.sidang_topik.status_pembimbing_lapangan
        );
    },
    cekStatusBimbingan(index) {
      const status = ["ditolak", "diterima", "sedang diajukan"];
      if (index >= 0 && index < 5) {
        return status[index];
      } else {
        return "";
      }
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
    },
    cekStatusPembimbingUtama(status) {
      var arrStatus = ["ditolak", "diterima", "menunggu persetujuan"];
      if (status >= 0 && status <= 2) return arrStatus[status];
      else return "";
    },
    cekStatusPembimbingLapangan(status) {
      var arrStatus = [
        "ditolak",
        "diterima",
        "menunggu persetujuan",
        "tidak membutuhkan persetujuan",
      ];
      if (status >= 0 && status <= 3) return arrStatus[status];
      else return "";
    },
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
      this.loading = true;
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
          this.getNewestKomponenNilai();
        })
        .catch((response) => {
          this.loading = false;
          this.isBusyJadwal = false;
        });
    },
    getJumlahBimbingan() {
      this.loading = true;
      Axios.post(
        `${config.apiMahasiswaUrl}/list-bimbingan-konfirmasi`,
        {
          id_topik: this.topik.id_topik,
          id_pembimbing_bimbingan: this.topik.id_pembimbing,
          status_bimbingan: 1,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.bimbingan = response.data.data;
          this.jumlah_bimbingan = this.bimbingan.length;
          this.changeStatusBimbingan();
        })
        .catch((response) => {
          this.loading = false;
        });
    },
    changeStatusBimbingan() {
      this.bimbingan.forEach((element) => {
        element.status_bimbingan = this.cekStatusBimbingan(
          element.status_bimbingan
        );
      });
      this.isBusyHistory = false;
      this.getBatchSidang();
    },
    getBatchSidang() {
      if (this.$store.getters.batchSidang) {
        this.batch = this.$store.getters.batchSidang;
        if (this.batch) {
          this.minimal_bimbingan = this.batch.minimal_bimbingan;
          this.getJadwalMahasiswa();
        } else {
          this.loading_page = false;
          this.loading = false;
          this.isBusyJadwal = false;
        }
      } else {
        this.$store.dispatch("batchSidang").then((response) => {
          this.batch = this.$store.getters.batchSidang;
          if (this.batch) {
            this.minimal_bimbingan = this.batch.minimal_bimbingan;
            this.getJadwalMahasiswa();
          } else {
            this.loading = false;
            this.loading_page = false;
            this.isBusyJadwal = false;
          }
        });
      }
    },
    getNewestKomponenNilai() {
      this.loading = true;
      Axios.get(`${config.apiUrl}/komponen-nilai/newest`)
        .then((response) => {
          this.komponen_nilai = response.data;
          this.getNewestJenisSidang();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getNewestJenisSidang() {
      this.loading = true;
      Axios.get(`${config.apiUrl}/jenis-sidang/newest`)
        .then((response) => {
          this.jenis_sidang = response.data;
          this.getSidangTopik();
        })
        .catch((response) => {
          console.log(response);
        });
    },
    ajukanSidang() {
      if (!this.statusAjukanSidang) {
        this.statusAjukanSidang = true;
        this.loading = true;
        Axios.post(
          `${config.apiUrl}/sidang`,
          {
            ruangan_sidang: null,
            tanggal_sidang: null,
            waktu_mulai_sidang: null,
            waktu_akhir_sidang: null,
            nilai_pembimbing: null,
            nilai_pembimbing_lapangan: null,
            nilai_penguji: null,
            nilai_penguji_dua: null,
            nilai_koor_kp: null,
            text_revisi: null,
            foto_revisi: null,
            text_revisi_pembimbing: null,
            foto_revisi_pembimbing: null,
            text_revisi_penguji: null,
            foto_revisi_penguji: null,
            status_pembimbing_utama: 2,
            status_pembimbing_lapangan: 2,
            status_sidang: 2,
            id_penguji_sidang: this.topik.id_penguji_sidang,
            id_penguji_sidang_dua: this.topik.id_penguji_sidang_dua,
            id_topik: this.topik.id_topik,
            id_batch: this.batch.id_batch,
            id_komponen_nilai: this.komponen_nilai.id_komponen_nilai,
            id_jenis_sidang: this.jenis_sidang.id_jenis_sidang,
            id_syarat_ketentuan_sidang_mahasiswa:
              this.syarat_ketentuan_mahasiswa.id_syarat_ketentuan_sidang,
            id_syarat_ketentuan_sidang_dosen:
              this.syarat_ketentuan_dosen.id_syarat_ketentuan_sidang,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then((response) => {
            if (!this.statusAjukanKomponen) {
              this.statusAjukanKomponen = true;
              this.getKomponenPenilaian();
            }
          })
          .catch((response) => {
            this.loading = false;
            console.log(response);
          });
      }
    },
    getKomponenPenilaian() {
      this.loading = true;
      Axios.get(`${config.apiUrl}/komponen-penilaian/aktif`)
        .then((response) => {
          this.getSidangTopikPengajuan(response.data.data);
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    getSidangTopikPengajuan(komponen_penilaian) {
      this.loading = true;
      Axios.post(
        `${config.apiUrl}/sidang-topik`,
        {
          id_topik: this.topik.id_topik,
          id_batch: this.batch.id_batch,
          id_komponen_nilai: this.komponen_nilai.id_komponen_nilai,
          id_jenis_sidang: this.jenis_sidang.id_jenis_sidang,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          if (response.data.data.length != 0) {
            this.insertKomponenPenilaian(
              komponen_penilaian,
              response.data.data[0]
            );
          }
          this.loading = false;
        })
        .catch((response) => {
          console.log(response);
          this.loading = false;
        });
    },
    insertKomponenPenilaian(komponen_penilaian, sidang) {
      this.loading = true;
      Axios.post(
        `${config.apiUrl}/komponen-penilaian-sidang/data`,
        {
          komponen_penilaian: komponen_penilaian,
          id_sidang: sidang.id_sidang,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.getTopik();
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    periksaStatusPengajuan() {
      if (
        !this.batch ||
        this.menyetujui_syarat == "not_accepted" ||
        this.batch.status_batch == 0
      ) {
        this.status_pengajuan = false;
      } else {
        this.status_pengajuan = true;
      }
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
  watch: {
    batch() {
      this.periksaStatusPengajuan();
    },
    menyetujui_syarat() {
      this.periksaStatusPengajuan();
    },
  },
};
</script>
<style scoped>
/* .keterangan-website {
  margin-left: 0px;
  padding-left: 0px;
} */
/* // * Keperluan Sidang */
.sidang-mahasiswa {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 20px 20px;
  background-color: rgb(255, 255, 255);
}

/* // * Css Table */
.tabel-jadwal-mahasiswa,
.tabel-sidang-mahasiswa,
.table-histori-bimbingan {
  margin: 0px 0px 0px 0px;
  background-color: rgb(243, 249, 215);
  max-height: 40vh;
  white-space: pre-wrap;
}

/* // * Css Surat Pernyataan */
.surat-pernyataan-isi {
  padding: 0px 80px;
  text-align: justify;
}

.surat-pernyataan-title {
  padding-top: 10px;
  font-weight: bold;
}

.check-persetujuan-surat-pernyataan {
  padding-bottom: 15px;
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

/* // ! CSS Validasi Form */
.feedback-validasi {
  text-align: left;
  min-width: 20vw;
  max-width: 20vw;
  font-size: 20px;
}

.feedback-validasi-false {
  color: red;
}

.feedback-validasi-true {
  color: green;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}

.loading-text {
  color: rgb(32, 165, 6);
}
</style>