<template>
  <b-container>
    <div class="form-revisi-sidang">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Revisi Sidang</h3>
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
      <div class="revisi-sidang" v-else>
        <h4 class="keterangan-website">Form Revisi</h4>

        <b-form
          @submit.prevent="onSubmit"
          v-if="show"
          enctype="multipart/form-data"
          method="post"
        >
          <div class="form-revisi-item">
            <div class="isi-form" v-viewer>
              <h5 class="keterangan-website">Revisi Pembimbing</h5>
              <b-row>
                <b-col>
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-text_revisi"
                    v-model.trim="text_revisi_pembimbing"
                    placeholder="Text Revisi"
                    rows="7"
                    max-rows="7"
                  ></b-form-textarea>
                  <!-- <div class="caption-revisi text-center">Text Revisi</div> -->
                </b-col>
                <b-col>
                  <PreviewUploadImage
                    title="Foto Revisi"
                    v-model="foto_revisi_pembimbing"
                    sub_title="Revisi Pembimbing"
                    status="true"
                    :link="
                      '/public/sidang/' +
                      id_pengaju +
                      '/foto_revisi/' +
                      foto_revisi_pembimbing
                    "
                    v-if="foto_revisi_pembimbing"
                  />
                  <PreviewUploadImage
                    title="Foto Revisi"
                    sub_title="Belum ada Gambar"
                    v-model="foto_revisi_pembimbing"
                    v-else
                  />
                </b-col>
              </b-row>
              <h5 class="keterangan-website">Revisi Penguji</h5>
              <b-row>
                <b-col>
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-text_revisi"
                    v-model.trim="text_revisi_penguji"
                    placeholder="Text Revisi"
                    rows="7"
                    max-rows="7"
                  ></b-form-textarea>
                  <!-- <div class="caption-revisi text-center">Text Revisi</div> -->
                </b-col>
                <b-col>
                  <PreviewUploadImage
                    title="Foto Revisi"
                    v-model="foto_revisi_penguji"
                    sub_title="Revisi Penguji"
                    status="true"
                    :link="
                      '/public/sidang/' +
                      id_pengaju +
                      '/foto_revisi/' +
                      foto_revisi_penguji
                    "
                    v-if="foto_revisi_penguji"
                  />
                  <PreviewUploadImage
                    title="Foto Revisi"
                    sub_title="Belum ada Gambar"
                    v-model="foto_revisi_penguji"
                    v-else
                  />
                </b-col>
              </b-row>
            </div>
            <b-button class="btn-form" @click="updateRevisi"
              >Update Revisi</b-button
            >
          </div>
        </b-form>
        <h4 class="keterangan-website my-2">Konfirmasi Dosen</h4>
        <div class="konfirmasi-dosen">
          <b-table-simple>
            <b-thead>
              <b-tr>
                <b-th>Dosen</b-th>
                <b-th>Status</b-th>
                <b-th>Konfirmasi</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr>
                <b-td>Pembimbing</b-td>
                <b-td>{{
                  cekStatusPembimbing(sidang.status_revisi_pembimbing)
                }}</b-td>
                <b-td>
                  <b-button
                    class="btn-form"
                    id="show-btn"
                    @click="$bvModal.show('bv-modal-konfirmasi-pembimbing')"
                    >Konfirmasi</b-button
                  >
                </b-td>
              </b-tr>
              <b-tr>
                <b-td>Penguji</b-td>
                <b-td>{{
                  cekStatusPenguji(sidang.status_revisi_penguji)
                }}</b-td>
                <b-td>
                  <b-button
                    class="btn-form"
                    id="show-btn"
                    @click="$bvModal.show('bv-modal-konfirmasi-penguji')"
                    >Konfirmasi</b-button
                  >
                </b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>

          <b-modal
            ref="bv-modal-konfirmasi-pembimbing"
            id="bv-modal-konfirmasi-pembimbing"
            hide-footer
          >
            <template v-slot:modal-title>Konfirmasi Pembimbing</template>
            <b-form
              @submit.prevent="konfirmasiDosenPembimbing"
              v-if="show"
              enctype="multipart/form-data"
              method="post"
            >
              <div class="d-block form-input-text text-center">
                <b-form-input
                  class="form-input-text-item"
                  id="input_user_password_pembimbing"
                  v-model="input_user_password_pembimbing"
                  type="password"
                  required
                  placeholder="Password"
                  size="sm"
                ></b-form-input>
              </div>
              <div class="pesan-invalid" v-if="pesan_pembimbing">
                <small class="text-danger">{{ pesan_pembimbing }}</small>
              </div>
              <div class="text-right">
                <b-button type="submit" class="btn-form">Konfirmasi</b-button>
              </div>
            </b-form>
          </b-modal>

          <b-modal
            ref="bv-modal-konfirmasi-penguji"
            id="bv-modal-konfirmasi-penguji"
            hide-footer
          >
            <template v-slot:modal-title>Konfirmasi Penguji</template>
            <b-form
              @submit.prevent="konfirmasiDosenPenguji"
              v-if="show"
              enctype="multipart/form-data"
              method="post"
            >
              <div class="d-block form-input-text text-center">
                <b-form-input
                  class="form-input-text-item"
                  id="input_user_password_penguji"
                  v-model="input_user_password_penguji"
                  type="password"
                  required
                  placeholder="Password"
                  size="sm"
                ></b-form-input>
              </div>
              <div class="pesan-invalid" v-if="pesan_penguji">
                <small class="text-danger">{{ pesan_penguji }}</small>
              </div>
              <div class="text-right">
                <b-button type="submit" class="btn-form">Konfirmasi</b-button>
              </div>
            </b-form>
          </b-modal>
        </div>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import FileSelect from "../../../components/FileSelect";
import PreviewUploadImage from "../../../components/PreviewUploadImage";
export default {
  components: {
    FileSelect,
    PreviewUploadImage,
  },
  data() {
    return {
      sidang: {},
      fileUrl: "/public/sidang/",
      text_revisi: "",
      text_revisi_pembimbing: null,
      text_revisi_penguji: null,
      foto_revisi: null,
      foto_revisi_pembimbing: null,
      foto_revisi_penguji: null,
      pesan_pembimbing: null,
      input_user_password_pembimbing: null,
      pesan_penguji: null,
      input_user_password_penguji: null,
      loading: false,
      show: true,
      jenis_konfirmasi: "",
      loading_page: true,
      id_pengaju: this.$store.getters.pengguna.UserId,
    };
  },
  mounted() {
    this.getSidang();
  },
  methods: {
    addFile(fileKey, event) {
      this[fileKey] = event.target.files[0];
    },
    getSidang() {
      this.loading_page = true;
      Axios.post(
        `${config.apiMahasiswaUrl}/sidang/detail`,
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
          this.text_revisi_pembimbing = this.sidang.text_revisi_pembimbing;
          this.foto_revisi_pembimbing = this.sidang.foto_revisi_pembimbing;
          this.text_revisi_penguji = this.sidang.text_revisi_penguji;
          this.foto_revisi_penguji = this.sidang.foto_revisi_penguji;
          this.loading = false;
          this.changeNama();
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    updateRevisi() {
      this.update(
        "text_revisi_pembimbing",
        "foto_revisi_pembimbing",
        this.text_revisi_pembimbing,
        this.foto_revisi_pembimbing
      );
      this.update(
        "text_revisi_penguji",
        "foto_revisi_penguji",
        this.text_revisi_penguji,
        this.foto_revisi_penguji
      );
      this.getSidang();
    },
    update(jenis_text_revisi, jenis_foto_revisi, text_revisi, foto_revisi) {
      const formData = new FormData();
      formData.append("id_sidang", this.sidang.id_sidang);
      formData.append("status_sidang", this.sidang.status_sidang);
      formData.append("id_pengaju", this.sidang.topik.id_pengaju);
      formData.append("jenis_text_revisi", jenis_text_revisi);
      formData.append("text_revisi", text_revisi);
      formData.append("jenis_foto_revisi", jenis_foto_revisi);
      formData.append("foto_revisi", foto_revisi);
      formData.append("id_batch", this.sidang.batch.id_batch);

      Axios.post(`${config.apiMahasiswaUrl}/sidang/revisi/update`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.loading = false;
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    konfirmasiDosenPembimbing() {
      this.loading = true;
      Axios.post(
        `${config.apiLoginUrl}`,
        {
          input_user_id: this.sidang.topik.id_pembimbing,
          input_user_password: this.input_user_password_pembimbing,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.loading = false;
          if (response.data.Message == "Login Success") {
            this.jenis_konfirmasi = "status_revisi_pembimbing";
            this.konfirmasiRevisi();
          } else {
            this.pesan_pembimbing = "Password Salah";
          }
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    konfirmasiDosenPenguji() {
      this.loading = true;
      Axios.post(
        `${config.apiLoginUrl}`,
        {
          input_user_id: this.sidang.id_penguji_sidang,
          input_user_password: this.input_user_password_penguji,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.loading = false;
          if (response.data.Message == "Login Success") {
            this.jenis_konfirmasi = "status_revisi_penguji";
            this.konfirmasiRevisi();
          } else {
            this.pesan_penguji = "Password Salah";
          }
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    konfirmasiRevisi() {
      Axios.put(
        `${config.apiMahasiswaUrl}/sidang/revisi/konfirmasi`,
        {
          id_sidang: this.sidang.id_sidang,
          jenis_konfirmasi: this.jenis_konfirmasi,
          status_revisi: 1,
        },
        {
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          this.loading = false;
          // this.$router.push("/sidang");
          location.reload();
          this.$refs["bv-modal-konfirmasi-pembimbing"].hide();
          this.$refs["bv-modal-konfirmasi-penguji"].hide();
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    changeNama() {
      Axios.get(
        `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
          this.sidang.topik.id_pembimbing
      ).then((response) => {
        this.sidang.topik.id_pembimbing = response.data[0].Name;
      });
      Axios.get(
        `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
          this.sidang.id_penguji_sidang
      ).then((response) => {
        this.sidang.id_penguji_sidang = response.data[0].Name;
      });
      this.loading_page = false;
    },
    cekStatusPembimbing(index) {
      var status = ["ditolak", "diterima", "belum di konfirmasi"];
      if (index >= 0 && index <= status.length) {
        return status[index];
      } else {
        return "";
      }
    },
    cekStatusPenguji(index) {
      var status = ["ditolak", "diterima", "belum di konfirmasi"];
      if (index >= 0 && index <= status.length) {
        return status[index];
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
  computed: {
    authUser() {
      return this.$root.auth;
    },
  },
};
</script>
<style scoped>
.revisi-sidang {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}

/* // * Form Revisi */
.form-revisi {
  padding-top: 20px;
}
.form-revisi-item {
  padding: 10px;
}
/* // * CSS Konfirmasi Dosen */
.konfirmasi-dosen {
  padding: 10px;
}

.text-revisi {
  white-space: pre-wrap;
  background-color: rgb(243, 249, 215);
  padding: 20px;
}
.caption-revisi {
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
  height: 46px;
  text-align: center;
  font-size: 20px;
  padding: 5px;
}

/* // * CSS Input File Item */
input[type="text"].input-form-file {
  background-color: rgb(228, 243, 188);
}
.revisi-sidang {
  margin-top: 20px;
}
.col-foto-revisi {
  margin: 0px;
  padding: 0px;
  max-width: 100%;
  min-width: 100%;
}
.col-foto-revisi-button {
  padding: 0px;
  margin: 0px;
  max-width: 21px;
  min-width: 21px;
}
.input-form-file-item-text {
  max-width: 100%;
  min-width: 100%;
  border-radius: 5px 0px 0px 5px;
}
/* // * CSS Gambar */
.gambar {
  width: 50%;
}
.gambar:hover {
  cursor: pointer;
}

/* // * Revisi Button Item */
.btn-form {
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
  text-decoration-line: none;
}

.btn-form:disabled,
.btn-form:disabled:hover {
  background-color: rgb(198, 205, 180);
  color: rgb(28, 31, 1);
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