<template>
  <b-container>
    <div class="form-revisi-sidang">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website">Revisi Sidang</h3>
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
        <h4 class="keterangan-website">
          Form Revisi: {{ sidang.nama }} - {{ sidang.topik.id_pengaju }}
        </h4>
        <b-form
          @submit.prevent="onSubmit"
          v-if="show"
          enctype="multipart/form-data"
          method="post"
        >
          <div class="form-revisi-item">
            <div class="isi-form" v-viewer>
              <b-row>
                <b-col>
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-text_revisi"
                    v-model.trim="text_revisi"
                    placeholder="Text Revisi"
                    rows="7"
                    max-rows="7"
                  ></b-form-textarea>
                  <!-- <div class="caption-revisi text-center">Text Revisi</div> -->
                </b-col>
                <b-col>
                  <PreviewUploadImage
                    title="Foto Revisi"
                    v-model="foto_revisi"
                    sub_title
                    status="true"
                    :link="
                      fileUrl +
                      sidang.topik.id_pengaju +
                      '/foto_revisi/' +
                      foto_revisi
                    "
                    v-if="foto_revisi"
                  />
                  <PreviewUploadImage
                    title="Foto Revisi"
                    sub_title="Belum ada Gambar"
                    v-model="foto_revisi"
                    v-else
                  />
                </b-col>
              </b-row>
            </div>

            <div class="btn-select">
              <b-button
                class="btn-form"
                @click="konfirmasiRevisi"
                v-if="!konfirmasi"
                >Konfirmasi Revisi</b-button
              >
              <b-button class="btn-form" @click="kembaliListRevisi" v-else
                >Sudah di Konfirmasi</b-button
              >
              <b-button
                class="btn-form"
                v-if="!konfirmasi"
                @click="updateRevisi"
                :disabled="loading_update"
              >
                {{ loading_update ? "" : "Update Revisi" }}
                <ring-loader
                  class="loading-page"
                  color="white"
                  :size="25"
                  v-if="loading_update"
                />
              </b-button>
              <b-button class="btn-form" disabled v-else
                >Update Revisi</b-button
              >
            </div>
          </div>
        </b-form>
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
      foto_revisi: null,
      pesan_pembimbing: null,
      input_user_password_pembimbing: null,
      pesan_penguji: null,
      input_user_password_penguji: null,
      loading: false,
      show: true,
      jenis_konfirmasi: "",
      id_pengguna: this.$store.getters.pengguna.UserId,
      konfirmasi: false,
      loading_page: true,
      loading_update: false,
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
      this.loading = true;
      this.loading_update = false;
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
          Axios.get(
            `${config.apiIbatsUrl}/Users/GetFiltered?input_user_nrp=` +
              this.sidang.topik.id_pengaju
          ).then((response) => {
            this.sidang.nama = response.data[0].Name;
          });
          if (this.sidang.topik.id_pembimbing == this.id_pengguna) {
            this.text_revisi = this.sidang.text_revisi_pembimbing;
            this.foto_revisi = this.sidang.foto_revisi_pembimbing;
            this.jenis_konfirmasi = "status_revisi_pembimbing";
            if (this.sidang.status_revisi_pembimbing == 1) {
              this.konfirmasi = true;
            }
          } else if (this.sidang.id_penguji_sidang == this.id_pengguna) {
            this.text_revisi = this.sidang.text_revisi_penguji;
            this.foto_revisi = this.sidang.foto_revisi_penguji;
            this.jenis_konfirmasi = "status_revisi_penguji";
            if (this.sidang.status_revisi_penguji == 1) {
              this.konfirmasi = true;
            }
          }
          this.loading = false;
          this.loading_page = false;
          console.log(this.sidang);
        })
        .catch((response) => {
          this.loading = false;
        });
    },
    updateRevisi() {
      this.loading_update = true;
      const formData = new FormData();
      formData.append("id_sidang", this.sidang.id_sidang);
      formData.append("id_pengaju", this.sidang.topik.id_pengaju);
      formData.append("status_sidang", this.sidang.status_sidang);
      if (this.sidang.topik.id_pembimbing == this.id_pengguna) {
        formData.append("jenis_text_revisi", "text_revisi_pembimbing");
        formData.append("jenis_foto_revisi", "foto_revisi_pembimbing");
      } else if (this.sidang.id_penguji_sidang == this.id_pengguna) {
        formData.append("jenis_text_revisi", "text_revisi_penguji");
        formData.append("jenis_foto_revisi", "foto_revisi_penguji");
      }
      formData.append("text_revisi", this.text_revisi);
      formData.append("foto_revisi", this.foto_revisi);
      formData.append("id_batch", this.sidang.batch.id_batch);

      Axios.post(`${config.apiDosenUrl}/sidang/revisi/update`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then(() => {
          this.loading = false;
          // this.$router.push("/sidang");
          // location.reload();
          // this.$router.push("/sidang/list-revisi");
          this.getSidang();
        })
        .catch(() => {
          this.loading = false;
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
          this.kembaliListRevisi();
        })
        .catch((response) => {
          this.loading = false;
          console.log(response);
        });
    },
    kembaliListRevisi() {
      this.$router.push("/sidang/list-revisi");
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
.form-revisi-item {
  padding: 10px;
}

/* // * CSS Input File Item */
input[type="text"].input-form-file {
  background-color: rgb(228, 243, 188);
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
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  cursor: default;
}
/* // * Button Select */
.btn-select {
  padding-right: 10px;
  margin-top: 20px;
  margin-bottom: 0px;
}
/* // * CSS Konfirmasi Dosen */
.konfirmasi-dosen {
  padding: 10px 20px 0px 50px;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>