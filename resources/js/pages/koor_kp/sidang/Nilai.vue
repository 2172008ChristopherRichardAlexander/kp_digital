<template>
  <b-container>
    <div class="form-penilaian-mahasiswa">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">
            Penilaian Koordinator Kerja Praktek
          </h3>
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
      <div class="penilaian-mahasiswa" v-else>
        <div class="form-penilaian">
          <h4 class="keterangan-website">
            Form Penilaian: {{ sidang.nama }} - {{ sidang.topik.id_pengaju }}
          </h4>
          <div class="form-penilaian-item">
            <b-table-simple>
              <b-tbody>
                <b-tr class="table-penilaian-title">
                  <b-th class="table-penilaian-title-item"
                    >Komponen Penilaian</b-th
                  >
                  <b-th class="table-penilaian-title-item text-center"
                    >Bobot (%)</b-th
                  >
                  <b-th class="table-penilaian-title-item text-center"
                    >Nilai</b-th
                  >
                </b-tr>

                <b-tr
                  class="table-penilaian-row"
                  v-for="komponen in komponen_penilaian_sidang"
                  :key="komponen.id_komponen_penilaian"
                >
                  <b-td class="table-penilaian-col col-kriteria">
                    <div class="table-penilaian-col-title">
                      {{ komponen.komponen_penilaian.judul_komponen_penilaian }}
                    </div>
                    <div class="table-penilaian-col-isi">
                      {{
                        komponen.komponen_penilaian.deskripsi_komponen_penilaian
                      }}
                    </div>
                  </b-td>
                  <b-td class="table-penilaian-col col-bobot">{{
                    (komponen.komponen_penilaian.bobot_komponen_penilaian *
                      100) %
                      1 ==
                    0
                      ? (
                          komponen.komponen_penilaian.bobot_komponen_penilaian *
                          100
                        ).toFixed(0)
                      : (
                          komponen.komponen_penilaian.bobot_komponen_penilaian *
                          100
                        ).toFixed(1)
                  }}</b-td>
                  <b-td class="table-penilaian-col col-nilai">
                    <div v-if="sidang.nilai_koor_kp">
                      {{ komponen.nilai_komponen_penilaian_sidang }}
                    </div>
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
                      v-else
                    ></b-form-input>
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
          <div class="btn-select text-right">
            <b-button
              class="btn-form"
              @click="periksaNilai"
              :disabled="sidang.nilai_koor_kp"
              >Nilai</b-button
            >
          </div>
        </div>

        <!-- // ? Modal -->
        <b-modal
          id="modal-penilaian-akhir"
          ref="modal-penilaian-akhir"
          centered
          title="Penilaian Koordinator KP"
          @ok="onSubmit"
        >
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
export default {
  data() {
    return {
      sidang: {},
      id_pengguna: this.$store.getters.pengguna.UserId,
      show: true,
      nilai_total: 0,
      loading: false,
      loading_page: true,
      isBusySidang: true,
      komponen_penilaian_sidang: [],
      submit_status: null,
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
    this.getSidangMahasiswa();
  },
  methods: {
    getSidangMahasiswa() {
      this.loading = true;
      Axios.post(
        `${config.apiKoorKpUrl}/sidang/detail`,
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
          this.loading = false;
          this.sidang = response.data.data;
          this.changeDataSidang();
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    changeDataSidang() {
      // Axios.get(
      //   `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
      //     this.sidang.topik.id_pengaju
      // ).then(response => {
      //   this.sidang.nama = response.data[0].Name;
      // });
      this.isBusySidang = false;
      this.getKomponenPenilaianSidang();
    },
    getKomponenPenilaianSidang() {
      const formData = new FormData();
      formData.append("id_sidang", this.sidang.id_sidang);
      formData.append("id_role", 12);

      Axios.post(`${config.apiUrl}/komponen-penilaian-sidang/role`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          if (response.data.message != "kosong") {
            this.komponen_penilaian_sidang = response.data.data;
          } else {
            this.komponen_penilaian_sidang = [];
          }
          this.loading_page = false;
        })
        .catch((response) => {
          this.loading_page = false;
          console.log(response);
        });
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
    onSubmit() {
      this.loading = true;
      if (this.submit_status === "BERHASIL") {
        this.komponen_penilaian_sidang.forEach((element) => {
          Axios.put(`${config.apiUrl}/komponen-penilaian-sidang/update-nilai`, {
            id_komponen_penilaian_sidang: element.id_komponen_penilaian_sidang,
            nilai_komponen_penilaian_sidang:
              element.nilai_komponen_penilaian_sidang,
          }).catch((response) => {
            console.log(response);
          });
        });
        Axios.put(
          `${config.apiKoorKpUrl}/sidang/update-nilai`,
          {
            id_sidang: this.sidang.id_sidang,
            nilai_koor_kp: this.nilai_total,
          },
          {
            headers: {
              "Content-Type": "application/json",
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
      }
      this.$refs["modal-penilaian-akhir"].hide();
      this.$router.push("/sidang/penilaian");
    },
    hitungNilaiTotal() {
      // var hasil_satu = (this.nilai_total_sikap * this.bobot_total_sikap) / 100;
      // var hasil_dua = (this.nilai_total_proses * this.bobot_total_proses) / 100;
      // var hasil = parseFloat(hasil_satu) + parseFloat(hasil_dua);
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
.penilaian-mahasiswa {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 5px 40px 10px 10px;
  margin: 0px 10px 0px 0px;
  background-color: rgb(255, 255, 255);
}
/* // * Form Penilaian */
.form-penilaian {
  padding-top: 20px;
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
.table-penilaian {
  width: 100%;
  background-color: red;
}
/* // * Title */
.table-penilaian-title,
.table-penilaian-title-item {
  padding: 5px;
  background-color: rgb(202, 206, 147);
  color: rgb(243, 249, 215);
  min-width: 125px;
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
}

.table-penilaian-col.col-bobot {
  width: 80px;
}

.table-penilaian-col.col-nilai {
  min-width: 130px;
  max-width: 130px;
}
.input-nilai-number {
  font-size: 23px;
}

.table-penilaian-col.col-nomor {
  text-align: center;
  font-size: 18px;
  font-weight: bold;
}

/* .input-nilai-number{

} */
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

/* // * Button Select Lulus atau Tidak Lulus */
.btn-select {
  padding-right: 20px;
  margin-top: 20px;
  margin-bottom: 0px;
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
  width: 130px;
}

.btn-form:hover {
  font-size: 15px;
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

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>