<template>
  <div>
    <!-- // ? Modal Ubah Profile -->
    <b-modal
      id="modal-ubah-profile"
      ref="modalUbahProfile"
      title="Perbaharui Profile"
      centered
      scrollable
    >
      <!-- // ? Form Ubah Profile -->
      <form ref="form" @submit.prevent="validasiForm">
        <div v-if="user">
          <b-row>
            <b-col>
              <PreviewUploadImage
                title="Ubah Profile"
                v-model="photo_profile"
                sub_title=""
                status="true"
                :link="user.photo_profile"
                v-if="user.photo_profile"
              />
              <PreviewUploadImage
                title="Photo Profile"
                sub_title=""
                v-model="photo_profile"
                v-else
              />
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="4">Nama</b-col>
            <b-col>{{ user.nama_pengguna }}</b-col>
          </b-row>
          <b-row>
            <b-col cols="4"> NIK/NRP </b-col>
            <b-col> {{ user.kode_pengguna }} </b-col>
          </b-row>
          <b-row>
            <b-col cols="4">Tempat Lahir</b-col>
            <b-col>
              <MyInputText
                v-model="$v.kota_lahir.$model"
                id="tempat"
                placeholder="Tempat"
              />
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="4">Tanggal Lahir</b-col>
            <b-col>
              <MyDatePicker
                v-model="$v.tanggal_lahir.$model"
                id="tanggal-lahir"
                placeholder="Tanggal Lahir"
              />
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="4"> Email </b-col>
            <b-col>
              <MyInputText
                v-model="$v.email.$model"
                id="email"
                placeholder="Email"
              />
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="4"> Nomor HP </b-col>
            <b-col>
              <MyInputText
                v-model="$v.nomor_hp.$model"
                id="nomor-hp"
                placeholder="Nomor HP"
                description="Ex: 081XXXXXXXXX"
              />
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="4"> Nomor Telepon </b-col>
            <b-col>
              <MyInputText
                v-model="nomor_telepon"
                id="nomor-telepon"
                placeholder="Nomor Telepon"
                description="Ex: (022) XXXXXXX"
              />
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="4"> Kontak Lainnya </b-col>
            <b-col>
              <MyInputTextarea
                v-model.trim="kontak_lainnya"
                id="kotak-lainnya"
                :placeholder="placeholder"
                description="Ex: Facebook, Telegram, Instagram, WhatsApp, Line, ..."
                rows="5"
                max-rows="5"
              />
            </b-col>
          </b-row>
        </div>
      </form>
      <template v-slot:modal-footer>
        <div class="w-100">
          <p class="float-left" v-if="message">
            <b class="text-success" v-if="status_message">{{ message }}</b>
            <b class="text-danger" v-else>{{ message }}</b>
          </p>
          <b-button
            size="sm"
            class="btn-form float-right"
            @click="validasiForm()"
            :disabled="loading_submit"
          >
            {{ loading_submit ? "" : "Perbaharui" }}
            <ring-loader
              class="loading-page"
              color="white"
              :size="25"
              v-if="loading_submit"
            />
          </b-button>
        </div>
      </template>
    </b-modal>
  </div>
</template>
<script>
import { required, email } from "vuelidate/lib/validators";
import MyInputText from "./MyInputText";
import MyInputEmail from "./MyInputEmail";
import MyDatePicker from "./MyDatePicker";
import MyInputTextarea from "./MyInputTextarea";
import PreviewUploadImage from "./PreviewUploadImage";
import Axios from "axios";
import config from "../config";

export default {
  props: ["pengguna"],
  components: {
    MyInputText,
    MyDatePicker,
    MyInputEmail,
    PreviewUploadImage,
    MyInputTextarea,
  },
  data() {
    return {
      user: this.pengguna,
      kode_pengguna: this.pengguna.kode_pengguna,
      email: this.pengguna.email,
      kota_lahir: this.pengguna.kota_lahir,
      tanggal_lahir: this.pengguna.tanggal_lahir,
      nomor_hp: this.pengguna.nomor_hp,
      nomor_telepon: this.pengguna.nomor_telepon,
      kontak_lainnya: this.pengguna.kontak_lainnya
        ? this.pengguna.kontak_lainnya
        : "",
      photo_profile: this.pengguna.photo_profile,
      loading_submit: false,
      message: null,
      status_message: false,
      placeholder:
        "Facebook: ...\nTelegram: ...\nInstagram: ...\nWhatsApp: ...\nLine: ...",
    };
  },
  validations: {
    kode_pengguna: {
      required,
    },
    email: {
      required,
      email,
    },
    kota_lahir: {
      required,
    },
    tanggal_lahir: {
      required,
    },
    nomor_hp: {
      required,
    },
    photo_profile: {
      required,
    },
  },
  methods: {
    validasiForm() {
      this.loading_submit = true;
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.loading_submit = false;
      } else {
        this.ubahPengguna();
      }
    },
    ubahPengguna() {
      this.loading_submit = true;
      const formData = new FormData();
      formData.append("kode_pengguna", this.kode_pengguna);
      formData.append("email", this.email);
      formData.append("kota_lahir", this.kota_lahir);
      formData.append("tanggal_lahir", this.tanggal_lahir);
      formData.append("nomor_hp", this.nomor_hp);
      formData.append("nomor_telepon", this.nomor_telepon);
      formData.append("kontak_lainnya", this.kontak_lainnya);
      formData.append("photo_profile", this.photo_profile);
      formData.append("path", config.photoProfilePath);
      Axios.post(`${config.apiIbatsBiasaUrl}/UpdateProfile`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.$emit("updated", response.data.data);
          this.$bvModal.hide("modal-ubah-profile");
          this.loading_submit = false;
        })
        .catch((response) => {
          this.message = "Server Error";
          this.loading_submit = false;
        });
    },
  },
};
</script>
<style scoped>
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

/* // * CSS Label Form */
.label-form {
  min-width: 100px;
  max-width: 100px;
  font-size: 18px;
  margin: auto;
  padding-left: 30px;
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
/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>