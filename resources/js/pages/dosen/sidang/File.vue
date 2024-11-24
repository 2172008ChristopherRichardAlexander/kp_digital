<template>
  <b-container>
    <div class="form-file-sidang">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">File Sidang</h3>
        </div>
      </div>
      <div v-if="loading_page">
        <ring-loader
          class="loading-page"
          color="#bada55"
          :loading="loading_page"
          :size="150"
        />
      </div>
      <div class="file-sidang" v-else>
        <div class="file-sidang-item">
          <!-- // ? Surat Pernyataan -->
          <div class="surat-pernyataan">
            <h4 class="keterangan-website">Surat Pernyataan</h4>
            <h5 class="surat-pernyataan-title text-center">
              {{
                sidang.syarat_ketentuan_sidang_dosen
                  .judul_syarat_ketentuan_sidang
              }}
            </h5>
            <div
              class="surat-pernyataan-isi"
              v-html="
                sidang.syarat_ketentuan_sidang_dosen
                  .deskripsi_syarat_ketentuan_sidang
              "
            ></div>
            <div class="check-persetujuan-surat-pernyataan">
              <b-form-checkbox
                id="checkbox-persetujuan-surat-pernyataan"
                v-model="menyetujui_syarat"
                name="checkbox-persetujuan-surat-pernyataan"
                value="accepted"
                unchecked-value="not_accepted"
                :disabled="menyetujui_syarat == 'accepted'"
                >I accept the terms and use</b-form-checkbox
              >
            </div>
          </div>
          <b-form
            @submit.prevent="onSubmit"
            v-if="show"
            enctype="multipart/form-data"
            method="post"
          >
            <!-- // ? Form Penilaian -->
            <div class="form-penilaian" v-if="menyetujui_syarat === 'accepted'">
              <h4 class="keterangan-website">
                Form Penilaian: {{ sidang.nama }} -
                {{ sidang.topik.id_pengaju }}
              </h4>
              <h4
                class="form-penilaian-title text-center"
                v-if="jenis_nilai_pengguna === 'nilai_pembimbing'"
              >
                Form Penilaian Pembimbing
              </h4>
              <h4
                class="form-penilaian-title text-center"
                v-if="
                  jenis_nilai_pengguna === 'nilai_penguji' ||
                  jenis_nilai_pengguna === 'nilai_penguji_dua'
                "
              >
                Form Penilaian Penguji
              </h4>
              <div class="form-penilaian-item">
                <b-table-simple
                  bordered
                  class="table-penilaian"
                  responsive="sm"
                >
                  <b-thead>
                    <b-tr class="table-penilaian-title">
                      <b-th class="table-penilaian-title-item">No</b-th>
                      <b-th class="table-penilaian-title-item"
                        >Kriteria Penilaian</b-th
                      >
                      <b-th class="table-penilaian-title-item text-center"
                        >Bobot (%)</b-th
                      >
                      <b-th class="table-penilaian-title-item text-center"
                        >Nilai</b-th
                      >
                    </b-tr>
                  </b-thead>
                  <b-tbody>
                    <b-tr
                      class="table-penilaian-row"
                      v-for="(komponen, index) in komponen_penilaian_sidang"
                      :key="komponen.id_komponen_penilaian"
                    >
                      <b-td class="table-penilaian-col col-nomor">{{
                        index + 1
                      }}</b-td>
                      <b-td class="table-penilaian-col col-kriteria">
                        <div class="table-penilaian-col-title">
                          {{
                            komponen.komponen_penilaian.judul_komponen_penilaian
                          }}
                        </div>
                        <div class="table-penilaian-col-isi">
                          {{
                            komponen.komponen_penilaian
                              .deskripsi_komponen_penilaian
                          }}
                        </div>
                      </b-td>
                      <b-td class="table-penilaian-col col-bobot">{{
                        (komponen.komponen_penilaian.bobot_komponen_penilaian *
                          100) %
                          1 ==
                        0
                          ? (
                              komponen.komponen_penilaian
                                .bobot_komponen_penilaian * 100
                            ).toFixed(0)
                          : (
                              komponen.komponen_penilaian
                                .bobot_komponen_penilaian * 100
                            ).toFixed(1)
                      }}</b-td>
                      <b-td class="table-penilaian-col col-nilai">
                        <div
                          v-if="
                            (jenis_nilai_pengguna == 'nilai_pembimbing' &&
                              sidang.nilai_pembimbing) ||
                            (jenis_nilai_pengguna == 'nilai_penguji' &&
                              sidang.nilai_penguji) ||
                            (jenis_nilai_pengguna == 'nilai_penguji_dua' &&
                              sidang.nilai_penguji_dua)
                          "
                        >
                          {{ komponen.nilai_komponen_penilaian_sidang }}
                        </div>
                        <div v-else>
                          <b-form-input
                            type="number"
                            step="0.01"
                            min="0"
                            max="100"
                            @change="hitungNilaiTotal"
                            v-model.trim.lazy="
                              komponen.nilai_komponen_penilaian_sidang
                            "
                            class="input-nilai-number"
                          ></b-form-input>
                        </div>
                        <!-- <span
                      class="feedback-validasi-false"
                      v-if="!$v.komponen.nilai_komponen_penilaian_sidang.between"
                    >&#10006;</span>
                        <span class="feedback-validasi-true" v-else>&#10004;</span>-->
                      </b-td>
                    </b-tr>
                  </b-tbody>
                  <b-tfoot>
                    <b-tr class="table-penilaian-footer">
                      <b-td
                        colspan="2"
                        class="table-penilaian-footer-item col-keterangan"
                        >Nilai Total</b-td
                      >
                      <b-td colspan="2" class="table-penilaian-footer-item">{{
                        nilai_total
                      }}</b-td>
                    </b-tr>
                  </b-tfoot>
                </b-table-simple>
              </div>
            </div>

            <!-- // ? Form Revisi -->
            <div class="form-revisi" v-if="menyetujui_syarat === 'accepted'">
              <h4 class="keterangan-website">
                Form Revisi: {{ sidang.nama }} - {{ sidang.topik.id_pengaju }}
              </h4>
              <div
                class="row"
                v-if="
                  jenis_nilai_pengguna == 'nilai_pembimbing' &&
                  sidang.nilai_pembimbing
                "
              >
                <div class="col text-revisi">
                  {{
                    sidang.text_revisi_pembimbing
                      ? sidang.text_revisi_pembimbing
                      : "Tidak ada text revisi"
                  }}
                </div>
                <div class="col" v-viewer>
                  <p v-if="!sidang.foto_revisi_pembimbing">Tidak ada Foto</p>
                  <img
                    class="gambar"
                    :src="
                      '/public/sidang/' +
                      sidang.topik.id_pengaju +
                      '/foto_revisi/' +
                      sidang.foto_revisi_pembimbing
                    "
                    alt="Tidak ada Foto"
                    v-else
                  />
                </div>
              </div>
              <div
                class="row"
                v-else-if="
                  (jenis_nilai_pengguna == 'nilai_penguji' &&
                    sidang.nilai_penguji) ||
                  (jenis_nilai_pengguna == 'nilai_penguji_dua' &&
                    sidang.nilai_penguji_dua)
                "
              >
                <div class="col text-revisi">
                  {{
                    sidang.text_revisi_penguji
                      ? sidang.text_revisi_penguji
                      : "Tidak text ada revisi"
                  }}
                </div>
                <div class="col" v-viewer>
                  <p v-if="!sidang.foto_revisi_penguji">Tidak ada Foto</p>
                  <img
                    class="gambar"
                    :src="
                      '/public/sidang/' +
                      sidang.topik.id_pengaju +
                      '/foto_revisi/' +
                      sidang.foto_revisi_penguji
                    "
                    alt="Tidak ada Foto"
                    v-else
                  />
                </div>
              </div>
              <div v-else>
                <div class="form-revisi-item">
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-text_revisi"
                    v-model.trim="text_revisi"
                    placeholder="Text Revisi"
                    rows="10"
                    max-rows="10"
                  ></b-form-textarea>
                  <div style="max-width: 100%; min-width: 100%">
                    <b-form inline>
                      <b-form-input
                        type="text"
                        class="input-form-file-item-text"
                        v-if="foto_revisi"
                        :value="foto_revisi.name"
                        disabled
                        size="sm"
                      />
                      <b-form-input
                        type="text"
                        class="input-form-file-item-text"
                        v-else
                        placeholder="Foto Revisi"
                        disabled
                        size="sm"
                      />
                      <file-select
                        class="input-form-file-item-button"
                        v-model="foto_revisi"
                      ></file-select>
                    </b-form>
                  </div>
                </div>
              </div>
            </div>

            <!-- <b-button type="submit" class="btn-form">Simpan</b-button> -->
            <div class="btn-select" v-if="menyetujui_syarat === 'accepted'">
              <div
                v-if="
                  (jenis_nilai_pengguna == 'nilai_pembimbing' &&
                    sidang.nilai_pembimbing) ||
                  (jenis_nilai_pengguna == 'nilai_penguji' &&
                    sidang.nilai_penguji) ||
                  (jenis_nilai_pengguna == 'nilai_penguji_dua' &&
                    sidang.nilai_penguji_dua)
                "
              >
                <b-button
                  class="btn-form"
                  disabled
                  v-if="sidang.status_sidang == 1"
                  >Lulus Sidang</b-button
                >
                <b-button
                  class="btn-form"
                  disabled
                  v-else-if="sidang.status_sidang == 0"
                  >Tidak Lulus Sidang</b-button
                >
              </div>
              <div v-else>
                <b-button class="btn-form" @click="lulusSidang"
                  >Lulus Sidang</b-button
                >
                <b-button class="btn-form" @click="tidakLulusSidang"
                  >Tidak Lulus Sidang</b-button
                >
              </div>
            </div>
          </b-form>
        </div>
        <!-- // ? Modal -->
        <b-modal
          id="modal-penilaian-akhir"
          ref="modal-penilaian-akhir"
          centered
          title="Penilaian Sidang"
          @ok="onSubmit"
        >
          <p v-if="status_sidang == 1">Mahasiswa berikut ini Lulus Sidang</p>
          <p v-else>Mahasiswa berikut ini Tidak Lulus Sidang</p>
          <b-table-simple>
            <b-tbody>
              <b-tr>
                <b-td>NRP</b-td>
                <b-td>:</b-td>
                <b-td>{{ sidang.topik.id_pengaju }}</b-td>
              </b-tr>
              <b-tr>
                <b-td>Nama Mahasiswa</b-td>
                <b-td>:</b-td>
                <b-td>{{ sidang.nama }}</b-td>
              </b-tr>
              <b-tr>
                <b-td>Nilai Akhir</b-td>
                <b-td>:</b-td>
                <b-td>{{ nilai_total }}</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
          <p>* Nilai Tidak Dapat di Ubah</p>
          <template v-slot:modal-footer="{ ok, cancel }">
            <b-button size="sm" class="btn-form" @click="cancel"
              >Tidak</b-button
            >
            <b-button size="sm" type="submit" @click="ok" class="btn-form"
              >Ya</b-button
            >
          </template>
        </b-modal>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import { between } from "vuelidate/lib/validators";
import FileSelect from "../../../components/FileSelect";
import Revisi from "./Revisi";
export default {
  components: {
    FileSelect,
    Revisi,
  },
  data() {
    return {
      sidang: {},
      menyetujui_syarat: "not_accepted",
      komponen_penilaian_sidang: [],
      nilai: [],
      text_revisi: "",
      foto_revisi: null,
      id_pengguna: this.$store.getters.pengguna.UserId,
      jenis_nilai_pengguna: "",
      submit_status: null,
      loading: false,
      loading_page: true,
      status_sidang: null,
      show: true,
      nilai_total: 0,
    };
  },
  validations: {
    komponen_penilaian_sidang: {
      $each: {
        nilai_komponen_penilaian_sidang: {
          between: between(0, 100),
        },
      },
    },
  },
  mounted() {
    this.getDetailSidang();
  },
  methods: {
    getDetailSidang() {
      Axios.post(
        `${config.apiDosenUrl}/sidang-detail`,
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
          this.sidang = response.data.data[0];
          if (this.sidang.topik.id_pembimbing == this.id_pengguna) {
            if (this.sidang.nilai_pembimbing) {
              this.nilai_total = this.sidang.nilai_pembimbing;
            }
            this.text_revisi = this.sidang.text_revisi_pembimbing;
          } else if (this.sidang.id_penguji_sidang == this.id_pengguna) {
            if (this.sidang.nilai_penguji) {
              this.nilai_total = this.sidang.nilai_penguji;
            }
            this.text_revisi = this.sidang.text_revisi_penguji;
          } else if (this.sidang.id_penguji_sidang_dua == this.id_pengguna) {
            if (this.sidang.nilai_penguji_dua) {
              this.nilai_total = this.sidang.nilai_penguji_dua;
            }
            this.text_revisi = this.sidang.text_revisi_penguji;
          }
          this.loading = false;
          this.changeDataSidang();
        })
        .catch((response) => {
          console.log(response);
          this.loading = false;
        });
    },
    changeDataSidang() {
      Axios.get(
        `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
          this.sidang.topik.id_pengaju
      ).then((response) => {
        this.sidang.nama = response.data[0].Name;
      });
      this.checkRolePengguna();
    },
    addFile(fileKey, event) {
      this[fileKey] = event.target.files[0];
    },
    checkRolePengguna() {
      if (this.sidang.topik.id_pembimbing == this.id_pengguna) {
        this.jenis_nilai_pengguna = "nilai_pembimbing";
        if (this.sidang.nilai_pembimbing) {
          this.menyetujui_syarat = "accepted";
        }
      } else if (this.sidang.id_penguji_sidang == this.id_pengguna) {
        this.jenis_nilai_pengguna = "nilai_penguji";
        if (this.sidang.nilai_penguji) {
          this.menyetujui_syarat = "accepted";
        }
      } else if (this.sidang.id_penguji_sidang_dua == this.id_pengguna) {
        this.jenis_nilai_pengguna = "nilai_penguji_dua";
        if (this.sidang.nilai_penguji_dua) {
          this.menyetujui_syarat = "accepted";
        }
      }
      this.getListKomponenPenilaianSidang();
    },
    getListKomponenPenilaianSidang() {
      this.loading = true;
      const formData = new FormData();
      formData.append("id_sidang", this.sidang.id_sidang);
      if (this.jenis_nilai_pengguna == "nilai_pembimbing") {
        formData.append("id_role", 9);
      } else {
        formData.append("id_role", 11);
      }
      Axios.post(`${config.apiUrl}/komponen-penilaian-sidang/role`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.komponen_penilaian_sidang = response.data.data;
          this.loading = false;
        })
        .catch((response) => {
          this.loading = false;
        });
      this.loading_page = false;
    },
    periksaNilai() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.submit_status = "GAGAL";
        this.$bvModal
          .msgBoxOk("Mohon diperhatikan nilai hanya bisa dari 0 - 100", {
            title: "Kesalahan Penilaian",
            size: "sm",
            buttonSize: "sm",
            okVariant: "info",
            headerClass: "p-2 border-bottom-0",
            footerClass: "p-2 border-top-0",
            centered: true,
          })
          .then((value) => {
            // successed
          })
          .catch((err) => {
            // An error occurred
          });
      } else {
        this.submit_status = "BERHASIL";
        this.$refs["modal-penilaian-akhir"].show();
      }
    },
    lulusSidang() {
      this.status_sidang = 1;
      this.periksaNilai();
    },
    tidakLulusSidang() {
      this.status_sidang = 0;
      this.periksaNilai();
    },

    onSubmit() {
      this.loading = true;
      if (this.submit_status === "BERHASIL") {
        // ! Belum di Coba
        this.komponen_penilaian_sidang.forEach((element) => {
          Axios.put(`${config.apiUrl}/komponen-penilaian-sidang/update-nilai`, {
            id_komponen_penilaian_sidang: element.id_komponen_penilaian_sidang,
            nilai_komponen_penilaian_sidang:
              element.nilai_komponen_penilaian_sidang,
          }).catch((response) => {
            console.log(response);
          });
        });

        const formData = new FormData();
        formData.append("id_sidang", this.sidang.id_sidang);
        formData.append("id_pengaju", this.sidang.topik.id_pengaju);
        formData.append("nilai_total", this.nilai_total);
        if (this.jenis_nilai_pengguna == "nilai_pembimbing") {
          formData.append("jenis_foto_revisi", "foto_revisi_pembimbing");
          formData.append("jenis_text_revisi", "text_revisi_pembimbing");
        } else {
          formData.append("jenis_foto_revisi", "foto_revisi_penguji");
          formData.append("jenis_text_revisi", "text_revisi_penguji");
        }
        formData.append("text_revisi", this.text_revisi);
        formData.append("foto_revisi", this.foto_revisi);

        formData.append("jenis_nilai_pengguna", this.jenis_nilai_pengguna);
        formData.append("status_sidang", this.status_sidang);
        formData.append("id_batch", this.sidang.batch.id_batch);
        Axios.post(
          `${config.apiDosenUrl}/sidang/hasil-penilaian-revisi`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
          .then((response) => {
            this.loading = false;
          })
          .catch((response) => {
            this.loading = false;
            console.log(response);
          });
        this.$router.push("/sidang/acara");
        this.$refs["modal-penilaian-akhir"].hide();
      }
    },
    hitungNilaiTotal() {
      var hasil = 0;
      this.komponen_penilaian_sidang.forEach((element, index) => {
        hasil =
          hasil +
          element.nilai_komponen_penilaian_sidang *
            element.komponen_penilaian.bobot_komponen_penilaian;
      });
      if (hasil % 1 == 0) {
        this.nilai_total = hasil.toFixed(0);
      } else {
        this.nilai_total = hasil.toFixed(2);
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
  watch: {
    komponen_penilaian_sidang() {
      this.hitungNilaiTotal();
    },
  },
};
</script>
<style scoped>
/* // * Acara Sidang */
.file-sidang {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
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
  padding-left: 15px;
}

/* // * Form Penilaian */
.form-penilaian {
  padding-top: 20px;
}
.form-penilaian-title {
  font-weight: bold;
}
.form-penilaian-item {
  padding: 10px;
}

/* // * Tabel Penilaian */
.table-penilaian,
.table-penilaian-title,
.table-penilaian-title-item,
.table-penilaian-row,
.table-penilaian-col,
.table-penilaian-footer,
.table-penilaian-footer-item {
  border: 3px solid rgb(202, 206, 147);
}

/* // * Title */
.table-penilaian-title,
.table-penilaian-title-item {
  padding: 5px;
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
}

.table-penilaian-title {
  font-weight: bold;
  font-size: 23px;
}
.table-penilaian-col-title {
  font-weight: bold;
  font-size: 18px;
}

/* // * Isi */
.table-penilaian-col.col-kriteria {
  padding: 10px;
}
.table-penilaian-col.col-bobot,
.table-penilaian-col.col-nilai {
  text-align: center;
  font-size: 23px;
  font-weight: bold;
  min-width: 125px;
}

.table-penilaian-col.col-bobot {
  width: 80px;
}

.table-penilaian-col.col-nilai {
  min-width: 130px;
  font-size: 23px;
}
.input-nilai-number {
  font-size: 23px;
}

.table-penilaian-col.col-nomor {
  text-align: center;
  font-size: 18px;
  font-weight: bold;
}

/* // * Footer */
.table-penilaian-footer-item {
  padding: 5px;
  background-color: rgb(243, 249, 215);
  color: rgb(202, 206, 147);
  font-weight: bold;
  font-size: 20px;
  text-align: center;
}
.table-penilaian-footer-item.col-keterangan {
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
  font-weight: bold;
  font-size: 20px;
  text-align: center;
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

/* // * Form Revisi */
/* .form-revisi {
  padding-top: 5px;
} */
.form-revisi-item {
  padding: 10px;
}

/* // * CSS Input File Item */
input[type="text"].input-form-file {
  background-color: rgb(228, 243, 188);
}
.foto-revisi {
  margin-top: 20px;
}
.col-foto-revisi {
  margin: 0px;
  padding: 0px;
}
.col-foto-revisi-button {
  padding: 0px;
  margin: 0px;
  min-width: 30px;
  max-width: 30px;
}
.input-form-file-item-text {
  max-width: 90%;
  min-width: 90%;
  border-radius: 5px 0px 0px 5px;
}

/* // * Submit Button Item */
.btn-form {
  margin-left: 10px;
  padding: 4px 20px;
  font-size: 15px;
  border-radius: 5px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
}
.btn-form:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}
.btn-form:disabled,
.btn-form:disabled:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  cursor: default;
}
/* // * Text Revisi */
.text-revisi {
  padding: 10px 25px;
  white-space: pre-wrap;
}
/* // * Gambar */
.gambar {
  /* width: 100%;
  height: 100%; */
  display: block;
  max-width: 250px;
  max-height: 250px;
  /* width: 200px; */
  margin: auto;
  height: auto;
}
.gambar:hover {
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