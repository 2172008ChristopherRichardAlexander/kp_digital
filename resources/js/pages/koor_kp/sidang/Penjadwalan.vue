<template>
  <b-container>
    <div class="form-penjadwalan-mahasiswa">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Penjadwalan Sidang</h3>
        </div>
      </div>
      <div class="penjadwalan-mahasiswa">
        <div class="pembimbing-penguji-sidang">
          <div class="row">
            <div class="col">
              <h3 class="keterangan-website">
                Daftar Pembimbing dan Penguji Sidang
              </h3>
            </div>
          </div>
          <b-row v-if="sidang.topik">
            <b-col sm="2">Pembimbing</b-col>
            <b-col>: {{ sidang.topik.pembimbing }} ({{ sidang.topik.id_pembimbing }})</b-col>
            <template v-if="sidang.topik.pembimbing_lapangan">
              <b-col sm="2">Pembimbing Lapangan</b-col>
              <b-col>: {{ sidang.topik.pembimbing_lapangan }} ({{ sidang.topik.id_pembimbing_lapangan }})</b-col>
            </template>
          </b-row>
          <b-row v-if="sidang.penguji_sidang">
            <b-col sm="2">Penguji</b-col>
            <b-col>: {{ sidang.penguji_sidang }} ({{ sidang.id_penguji_sidang }})</b-col>
            <template v-if="sidang.penguji_sidang_dua">
              <b-col sm="2">Penguji Dua</b-col>
              <b-col>: {{ sidang.penguji_sidang_dua }} ({{ sidang.id_penguji_sidang_dua }})</b-col>
            </template>
          </b-row>
        </div>
        <div class="penjadwalan-mahasiswa-item">
          <div class="row my-2">
            <div class="col text-left">
              <h4 class="keterangan-website">Daftar Jadwal Mahasiswa</h4>
            </div>
            <!-- Filter -->
            <div class="col-3 component-filter">
              <b-form-group
                label-size="sm"
                label-for="filterInput"
                class="mb-0"
              >
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
                    >
                      Hapus
                    </b-button>
                  </b-input-group-append>
                </b-input-group>
              </b-form-group>
            </div>
          </div>
          <b-table
            sticky-header
            class="table-penjadwalan-mahasiswa"
            :items="jadwal_mahasiswa"
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
                <!-- Jika menggunakan b-spinner -->
                <b-spinner
                  label="Loading..."
                  small
                  variant="success"
                ></b-spinner>
                <strong class="loading-text">Loading...</strong>
                <!-- Jika menggunakan RingLoader -->
                <!-- <ring-loader class="loading-page" color="#20a506" size="50" /> -->
              </div>
            </template>
            <template v-slot:empty>
              <h5 class="text-center">Tidak ada data jadwal mahasiswa</h5>
            </template>
          </b-table>
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
            <b-col class="text-right">&nbsp;</b-col>
          </b-row>
        </div>
        <!-- Menentukan Jadwal Mahasiswa -->
        <div class="row my-2">
          <div class="col text-left">
            <h4 class="keterangan-website">Menentukan Jadwal Mahasiswa</h4>
          </div>
        </div>
        <b-form
          @submit.prevent="onSubmit"
          v-if="show"
          enctype="multipart/form-data"
          method="post"
        >
          <div class="tambah-jadwal">
            <div class="form-tambah-jadwal-title">
              <h4 class="my-2">Form Tambah Jadwal</h4>
            </div>
            <div class="form-tambah-jadwal-item">
              <b-row>
                <b-col class="label-form">Tanggal</b-col>
                <b-col class="input-form-data">
                  <VueCtkDateTimePicker
                    label="Tanggal"
                    class="input-form-date-item"
                    id="input-tanggal_sidang"
                    format="DD-MM-YYYY"
                    formatted="LL"
                    outputFormat="YYYY-MM-DD"
                    v-model.trim="$v.tanggal_sidang.$model"
                    noLabel
                    onlyDate
                    inputSize="sm"
                  />
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.tanggal_sidang.required"
                  >
                    &#10006;
                  </span>
                  <span class="feedback-validasi-true" v-else>
                    &#10004;
                  </span>
                </b-col>
              </b-row>
            </div>
            <div class="form-tambah-jadwal-item">
              <b-row>
                <b-col class="label-form">Waktu</b-col>
                <b-col class="input-form-time text-left">
                  <VueCtkDateTimePicker
                    label="Waktu Mulai"
                    class="input-form-time-item"
                    id="input-waktu_mulai_sidang"
                    format="hh:mm a"
                    formatted="hh:mm a"
                    outputFormat="hh:mm a"
                    minute-interval="5"
                    only-time
                    v-model.trim="$v.waktu_mulai_sidang.$model"
                    noLabel
                    inputSize="sm"
                  />
                </b-col>
                <b-col class="simbol-sampai-dengan">&#9644;</b-col>
                <b-col class="input-form-time text-right">
                  <VueCtkDateTimePicker
                    label="Waktu Selesai"
                    class="input-form-time-item"
                    id="input-waktu_akhir_sidang"
                    format="hh:mm a"
                    formatted="hh:mm a"
                    outputFormat="hh:mm a"
                    minute-interval="5"
                    only-time
                    v-model.trim="$v.waktu_akhir_sidang.$model"
                    noLabel
                    inputSize="sm"
                  />
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="
                      !$v.waktu_mulai_sidang.required ||
                      !$v.waktu_akhir_sidang.required
                    "
                  >
                    &#10006;
                  </span>
                  <span class="feedback-validasi-true" v-else>
                    &#10004;
                  </span>
                </b-col>
              </b-row>
            </div>
            <div class="form-tambah-jadwal-item">
              <b-row>
                <b-col class="label-form">Ruang Sidang</b-col>
                <b-col class="input-form-text">
                  <b-form-input
                    class="input-form-text-item"
                    id="input-ruangan_sidang"
                    v-model="ruangan_sidang"
                    placeholder="Ruangan Sidang"
                    size="sm"
                    required
                  ></b-form-input>
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.ruangan_sidang.required"
                  >
                    &#10006;
                  </span>
                  <span class="feedback-validasi-true" v-else>
                    &#10004;
                  </span>
                </b-col>
              </b-row>
            </div>
            <!-- Row Konfirmasi -->
            <div class="form-tambah-jadwal-item row-konfirmasi">
              <b-row>
                <b-col class="input-form-submit text-right">
                  <!-- Tombol Kirim Email -->
                  <b-button
                    @click.prevent="openEmailModal"
                    :disabled="loading"
                    class="btn-form mr-2"
                    variant="info"
                  >
                    Kirim Email
                  </b-button>

                  <!-- Tombol Tentukan Jadwal -->
                  <b-button type="submit" :disabled="loading" class="btn-form">
                    {{ loading ? "Tentukan..." : "Tentukan Jadwal" }}
                    <!-- Jika menggunakan b-spinner -->
                    <b-spinner
                      small
                      label="Loading..."
                      v-if="loading"
                      class="ml-2"
                    ></b-spinner>
                    <!-- Jika menggunakan RingLoader -->
                    <!-- <ring-loader
                      class="loading-page"
                      color="white"
                      size="25"
                      v-if="loading"
                    /> -->
                  </b-button>
                </b-col>
                <b-col class="feedback-validasi">&nbsp;</b-col>
              </b-row>
            </div>
          </div>
        </b-form>

        <!-- Modal Kirim Email -->
        <b-modal
          id="emailModal"
          ref="emailModal"
          title="Kirim Email Sidang"
          hide-footer
          @hide="resetEmailData"
        >
          <b-form @submit.prevent="sendEmail">
            <b-form-group label="Penerima" label-for="recipients">
              <b-form-input
                id="recipients"
                v-model="emailData.recipients"
                placeholder="Masukkan email penerima (pisahkan dengan koma)"
                required
              />
              <small class="text-danger" v-if="!$v.emailData.recipients.required">
                Email penerima diperlukan.
              </small>
              <small class="text-danger" v-else-if="!$v.emailData.recipients.emailList">
                Format email tidak valid.
              </small>
            </b-form-group>
            <b-form-group label="Subjek" label-for="subject">
              <b-form-input
                id="subject"
                v-model="emailData.subject"
                placeholder="Masukkan subjek email"
                required
              />
              <small class="text-danger" v-if="!$v.emailData.subject.required">
                Subjek email diperlukan.
              </small>
            </b-form-group>
            <b-form-group label="Pesan" label-for="message">
              <b-form-textarea
                id="message"
                v-model="emailData.message"
                rows="4"
                placeholder="Masukkan pesan"
                required
              />
              <small class="text-danger" v-if="!$v.emailData.message.required">
                Pesan email diperlukan.
              </small>
            </b-form-group>
            <div class="text-right">
              <b-button variant="secondary" @click="hideEmailModal">Batal</b-button>
              <b-button type="submit" variant="primary" :disabled="loading">
                {{ loading ? 'Mengirim...' : 'Kirim Email' }}
                <!-- Jika menggunakan b-spinner -->
                <b-spinner
                  small
                  label="Loading..."
                  v-if="loading"
                  class="ml-2"
                ></b-spinner>
                <!-- Jika menggunakan RingLoader -->
                <!-- <ring-loader
                  class="loading-page"
                  color="white"
                  size="20"
                  v-if="loading"
                /> -->
              </b-button>
            </div>
          </b-form>
        </b-modal>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config"; // Pastikan config sudah sesuai dengan base URL API Anda
import { required } from "vuelidate/lib/validators";
// Jika masih menggunakan RingLoader, pastikan diimpor
import { RingLoader } from 'vue-spinner/dist/vue-spinner.min.js';

export default {
  components: {
    RingLoader, // Jika menggunakan RingLoader
    // Jika menggunakan b-spinner, tidak perlu mengimpor komponen tambahan
  },
  data() {
    return {
      filter: null,
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      sortBy: "tanggal",
      sortDesc: false,
      sidang: {},
      jadwal_mahasiswa: [],
      tanggal_sidang:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate(),
      waktu_mulai_sidang: null,
      waktu_akhir_sidang: null,
      fields: [
        { key: "hari_jadwal_mahasiswa", label: "Hari", sortable: true },
        {
          key: "waktu_mulai_jadwal_mahasiswa",
          label: "Waktu Mulai",
          sortable: true,
        },
        {
          key: "waktu_selesai_jadwal_mahasiswa",
          label: "Waktu Selesai",
          sortable: true,
        },
        {
          key: "keterangan_jadwal_mahasiswa",
          label: "Keterangan",
          sortable: true,
        },
      ],
      show: true,
      submit_status: null,
      ruangan_sidang: "",
      loading: false, // Ubah default menjadi false
      isBusyPenjadwalan: true,
      id_batch: null,
      pilihan_batch: [],
      id_semester: null,
      semester: null,
      isBusySemester: true,
      nama_pembimbing: null,
      nama_pembimbing_lapangan: null,
      nama_penguji: null,
      nama_penguji_dua: null,

      // Data untuk modal email
      emailData: {
        recipients: '',
        subject: '',
        message: ''
      },
      showEmailModal: false, // Kontrol tampilan modal
    };
  },
  validations: {
    tanggal_sidang: {
      required,
    },
    waktu_mulai_sidang: {
      required,
    },
    waktu_akhir_sidang: {
      required,
    },
    ruangan_sidang: {
      required,
    },
    emailData: {
      recipients: {
        required,
        // Anda dapat menambahkan validasi email jika diperlukan
      },
      subject: {
        required,
      },
      message: {
        required,
      },
    },
  },
  mounted() {
    this.getDetailSidang();
  },
  methods: {
    getDetailSidang() {
      this.loading = true;
      Axios.post(
        `${config.apiKoorKpUrl}/sidang-detail`,
        {
          id_sidang: this.acakAcak(this.$route.params.id, 1),
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          if (response.data.data.length > 0) {
            this.sidang = response.data.data[0];
            this.id_batch = this.sidang.batch.id_batch;
            if (this.sidang.tanggal_sidang) {
              this.tanggal_sidang = this.sidang.tanggal_sidang;
            }
            if (this.sidang.waktu_mulai_sidang) {
              this.waktu_mulai_sidang = this.sidang.waktu_mulai_sidang;
            }
            if (this.sidang.waktu_akhir_sidang) {
              this.waktu_akhir_sidang = this.sidang.waktu_akhir_sidang;
            }
            if (this.sidang.ruangan_sidang) {
              this.ruangan_sidang = this.sidang.ruangan_sidang;
            }
            this.changeDataTopik();
          } else {
            this.$bvToast.toast('Data sidang tidak ditemukan.', {
              variant: 'danger',
              solid: true,
            });
          }
        })
        .catch((error) => {
          console.error(error);
          this.$bvToast.toast('Gagal mengambil detail sidang.', {
            variant: 'danger',
            solid: true,
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    changeDataTopik() {
      this.getJadwal();
    },
    getJadwal() {
      this.isBusyPenjadwalan = true;
      const hari = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
      Axios.post(
        `${config.apiKoorKpUrl}/list-jadwal-batch-topik`,
        {
          id_batch: this.id_batch,
          id_topik: this.sidang.topik.id_topik,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.jadwal_mahasiswa = response.data.data;
          this.jadwal_mahasiswa.forEach((element) => {
            if (
              element.hari_jadwal_mahasiswa >= 1 &&
              element.hari_jadwal_mahasiswa <= 7
            )
              element.hari_jadwal_mahasiswa =
                hari[element.hari_jadwal_mahasiswa - 1];
            else element.hari_jadwal_mahasiswa = "";
          });
          this.totalRows = response.data.data.length;
          this.isBusyPenjadwalan = false;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
          this.$bvToast.toast('Gagal mengambil jadwal mahasiswa.', {
            variant: 'danger',
            solid: true,
          });
          this.loading = false;
        });
    },
    formatTime(waktu) {
      const jam = Number(waktu.match(/^(\d+)/)[1]);
      const menit = Number(waktu.match(/:(\d+)/)[1]);
      const AMPM = waktu.match(/\s(.*)$/)[1].toLowerCase();

      let jam24 = jam;
      if ((AMPM === "pagi" || AMPM === "am") && jam === 12) {
        jam24 = jam - 12;
      }
      if (
        (AMPM === "siang" ||
          AMPM === "sore" ||
          AMPM === "malam" ||
          AMPM === "pm") &&
        jam < 12
      )
        jam24 = jam + 12;

      const sJam = jam24 < 10 ? `0${jam24}` : jam24.toString();
      const sMenit = menit < 10 ? `0${menit}` : menit.toString();

      return `${sJam}:${sMenit}`;
    },
    checkFormat() {
      if (this.waktu_mulai_sidang && this.waktu_mulai_sidang.match(/\s(.*)$/)) {
        this.waktu_mulai_sidang = this.formatTime(this.waktu_mulai_sidang);
      }
      if (this.waktu_akhir_sidang && this.waktu_akhir_sidang.match(/\s(.*)$/)) {
        this.waktu_akhir_sidang = this.formatTime(this.waktu_akhir_sidang);
      }
    },
    onSubmit() {
      this.loading = true;

      this.$v.$touch();
      if (this.$v.$invalid) {
        this.submit_status = "GAGAL";
        this.loading = false;
        this.$bvToast.toast('Silakan lengkapi semua field yang diperlukan dengan benar.', {
          variant: 'danger',
          solid: true,
        });
        return;
      } else {
        this.submit_status = "BERHASIL";
      }
      if (this.submit_status === "BERHASIL") {
        this.checkFormat();

        Axios.put(
          `${config.apiUrl}/sidang`,
          {
            id_sidang: this.sidang.id_sidang,
            ruangan_sidang: this.ruangan_sidang,
            tanggal_sidang: this.tanggal_sidang,
            waktu_mulai_sidang: this.waktu_mulai_sidang,
            waktu_akhir_sidang: this.waktu_akhir_sidang,
            nilai_pembimbing: this.sidang.nilai_pembimbing,
            nilai_pembimbing_lapangan: this.sidang.nilai_pembimbing_lapangan,
            nilai_penguji: this.sidang.nilai_penguji,
            nilai_penguji_dua: this.sidang.nilai_penguji_dua,
            nilai_koor_kp: this.sidang.nilai_koor_kp,
            text_revisi: this.sidang.text_revisi,
            foto_revisi: this.sidang.foto_revisi,
            status_pembimbing_utama: this.sidang.status_pembimbing_utama,
            status_pembimbing_lapangan: this.sidang.status_pembimbing_lapangan,
            status_sidang: 4,
            id_syarat_ketentuan_sidang_mahasiswa: this.sidang
              .syarat_ketentuan_sidang_mahasiswa.id_syarat_ketentuan_sidang,
            id_syarat_ketentuan_sidang_dosen: this.sidang
              .syarat_ketentuan_sidang_dosen.id_syarat_ketentuan_sidang,
            id_penguji_sidang: this.sidang.id_penguji_sidang,
            id_penguji_sidang_dua: this.sidang.id_penguji_sidang_dua,
            id_topik: this.sidang.topik.id_topik,
            id_batch: this.sidang.batch.id_batch,
            id_komponen_nilai: this.sidang.komponen_nilai.id_komponen_nilai,
            id_jenis_sidang: this.sidang.jenis_sidang.id_jenis_sidang,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
          .then(() => {
            this.loading = false;
            this.$bvToast.toast('Jadwal berhasil ditentukan.', {
              variant: 'success',
              solid: true,
            });
            this.$router.push("/sidang/penjadwalan");
          })
          .catch(() => {
            this.loading = false;
            this.$bvToast.toast('Gagal menentukan jadwal.', {
              variant: 'danger',
              solid: true,
            });
          });
      }
    },
    // Metode untuk mengenkripsi/dekripsi ID (sesuai dengan logika Anda)
    acakAcak(isi, status) {
      isi = Array.from(String(isi));
      let code = "";
      if (status == 0) {
        isi.forEach((element) => {
          const mapping = {
            "0": "c",
            "1": "h",
            "2": "r",
            "3": "i",
            "4": "s",
            "5": "t",
            "6": "a",
            "7": "n",
            "8": "o",
            "9": "7",
          };
          code += mapping[element] || element;
        });
      } else if (status == 1) {
        isi.forEach((element) => {
          const reverseMapping = {
            "c": "0",
            "h": "1",
            "r": "2",
            "i": "3",
            "s": "4",
            "t": "5",
            "a": "6",
            "n": "7",
            "o": "8",
            "7": "9",
          };
          code += reverseMapping[element] || element;
        });
      }
      return code;
    },
    onFiltered(filteredItems) {
      this.currentPage = 1;
      this.totalRows = filteredItems.length;
    },
    getDayName(dateString) {
    const date = new Date(dateString);
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    return days[date.getDay()];
  },
    // Metode untuk Membuka Modal Email
    openEmailModal() {
      // Mendapatkan nama hari dari tanggal sidang
      const hari = this.getDayName(this.tanggal_sidang);

      // Menyusun pesan email
      this.emailData.subject = `Pemberitahuan Jadwal Sidang Anda`;
      this.emailData.message = `
Yth. Bapak/Ibu dan Mahasiswa,

Berikut adalah detail jadwal sidang Anda:

Hari: ${hari}
Tanggal: ${this.tanggal_sidang}
Waktu: ${this.waktu_mulai_sidang} - ${this.waktu_akhir_sidang}
Ruangan: ${this.ruangan_sidang}

Penguji:
- ${this.sidang.penguji_sidang || 'Penguji 1'}
- ${this.sidang.penguji_sidang_dua || 'Penguji 2'}

Pembimbing:
- ${this.sidang.topik.pembimbing || 'Pembimbing Utama'}
- ${this.sidang.topik.pembimbing_lapangan || 'Pembimbing Lapangan'}

Mahasiswa:
- ${this.sidang.mahasiswa ? this.sidang.mahasiswa.nama : 'Nama Mahasiswa'}

Mohon hadir tepat waktu.

Terima kasih.
      `;

      // Menampilkan modal
      this.showEmailModal = true;
      this.$refs.emailModal.show();
    },
    // Menutup Modal Email
    hideEmailModal() {
      this.$refs.emailModal.hide();
    },
    // Mengirim Email
    sendEmail() {
      // Validasi data email
      this.$v.emailData.$touch();
      if (this.$v.emailData.$invalid) {
        this.$bvToast.toast('Silakan lengkapi semua field email dengan benar.', {
          variant: 'danger',
          solid: true,
        });
        return;
      }

      this.loading = true;

      // Siapkan payload email
      const emailPayload = {
        recipients: this.emailData.recipients.split(',').map(email => email.trim()),
        subject: this.emailData.subject,
        message: this.emailData.message,
        // Tambahkan data tambahan jika diperlukan
        id_sidang: this.sidang.id_sidang,
        tanggal_sidang: this.tanggal_sidang,
        waktu_mulai_sidang: this.waktu_mulai_sidang,
        waktu_akhir_sidang: this.waktu_akhir_sidang,
        ruangan_sidang: this.ruangan_sidang,
      };

      Axios.post(`${config.apiUrl}/send-email`, emailPayload, {
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then(() => {
          this.loading = false;
          this.$bvToast.toast('Email berhasil dikirim.', {
            variant: 'success',
            solid: true,
          });
          this.hideEmailModal();
          this.resetEmailData();
        })
        .catch((error) => {
          console.error(error);
          this.loading = false;
          this.$bvToast.toast('Gagal mengirim email. Silakan coba lagi.', {
            variant: 'danger',
            solid: true,
          });
        });
    },
    // Reset data email setelah modal ditutup
    resetEmailData() {
      this.emailData = {
        recipients: '',
        subject: '',
        message: ''
      };
    },
    getUserEmailById(id_pengguna) {
      const user = this.users.find(user => user.id_pengguna === id_pengguna);
      return user ? user.email : '';
    },
    prepareEmailRecipients() {
      const recipients = [];

      // Mengambil email pembimbing utama
      if (this.sidang.topik.id_pembimbing) {
        const emailPembimbing = this.getUserEmailById(this.sidang.topik.id_pembimbing);
        if (emailPembimbing) recipients.push(emailPembimbing);
      }

      // Mengambil email pembimbing lapangan
      if (this.sidang.topik.id_pembimbing_lapangan) {
        const emailPembimbingLapangan = this.getUserEmailById(this.sidang.topik.id_pembimbing_lapangan);
        if (emailPembimbingLapangan) recipients.push(emailPembimbingLapangan);
      }

      // Mengambil email penguji pertama
      if (this.sidang.id_penguji_sidang) {
        const emailPenguji1 = this.getUserEmailById(this.sidang.id_penguji_sidang);
        if (emailPenguji1) recipients.push(emailPenguji1);
      }

      // Mengambil email penguji kedua (jika ada)
      if (this.sidang.id_penguji_sidang_dua) {
        const emailPenguji2 = this.getUserEmailById(this.sidang.id_penguji_sidang_dua);
        if (emailPenguji2) recipients.push(emailPenguji2);
      }

      // Mengambil email mahasiswa
      if (this.sidang.id_mahasiswa) { // Asumsikan ada field id_mahasiswa
        const emailMahasiswa = this.getUserEmailById(this.sidang.id_mahasiswa);
        if (emailMahasiswa) recipients.push(emailMahasiswa);
      }

      // Menghapus duplikasi email
      return [...new Set(recipients)];
    },
  },
};
</script>
<style scoped>
/* // * Penjadwalan Mahasiswa */
.penjadwalan-mahasiswa {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

.pilihan-batch,
.component-filter {
  margin: auto;
}

/* // * Css Table */
.table-penjadwalan-mahasiswa {
  background-color: rgb(255, 255, 255);
  max-height: 60vh;
}

/* // * CSS Tentukan Jadwal Sidang */
.tambah-jadwal {
  background-color: white;
  border-bottom: 3px solid rgb(202, 206, 147);
}

.form-tambah-jadwal-title {
  padding: 8px;
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
}

.form-tambah-jadwal-item:nth-child(odd) {
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
}

.row-konfirmasi {
  padding: 10px;
}

/* // * CSS Label Form */
.label-form {
  min-width: 180px;
  max-width: 180px;
  font-size: 18px;
  margin: auto;
  padding-left: 30px;
}

/* // * CSS Input Date Item */
.input-form-date-item {
  max-width: 100%;
  min-width: 100%;
}

/* // * CSS Input Time Item */
.input-form-time-item {
  padding: 0px;
  margin: 0px;
  max-width: 100%;
  min-width: 100%;
}

/* // * CSS Input Text Item */
.input-form-text-item {
  max-width: 100%;
  min-width: 100%;
}

/* // * CSS Simbol Sampai Dengan */
.simbol-sampai-dengan {
  min-width: 60px;
  max-width: 60px;
  font-size: 18px;
  margin: auto;
  color: rgb(243, 249, 215);
  text-align: center;
}

/* // ! CSS Validasi Form */
.feedback-validasi {
  text-align: left;
  margin: auto;
  min-width: 50px;
  max-width: 50px;
  font-size: 20px;
  padding: 0px;
}

.feedback-validasi-false {
  color: red;
}

.feedback-validasi-true {
  color: green;
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

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}

.loading-text {
  color: rgb(32, 165, 6);
}
</style>