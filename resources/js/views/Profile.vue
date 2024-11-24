<template>
  <b-container>
    <div class="halaman-profile">
      <div v-if="loading_page">
        <ring-loader
          class="loading-page"
          color="#bada55"
          :loading="loading_page"
          :size="150"
        />
      </div>
      <div v-else>
        <div class="row">
          <div class="col">
            <h3 class="my-2">Profile</h3>
          </div>
        </div>
        <div class="profile">
          <b-card no-body class="overflow-hidden card-profile">
            <b-row no-gutters>
              <b-col md="3">
                <b-row>
                  <b-col>
                    <!-- // ? Gambar Profile  -->
                    <div style="width: 100%; height: 100%" class="p-3">
                      <b-img
                        thumbnail
                        fluid
                        center
                        v-bind="mainProps"
                        alt="Profile Image"
                        v-if="!pengguna.photo_profile"
                      ></b-img>
                      <b-img
                        thumbnail
                        fluid
                        center
                        blank-color="black"
                        :src="pengguna.photo_profile"
                        alt="Profile Image"
                        v-else
                      ></b-img>
                    </div>
                  </b-col>
                </b-row>
                <b-row>
                  <b-col>
                    <b-button
                      block
                      class="btn-form"
                      v-b-modal.modal-ubah-profile
                    >
                      Perbaharui Profile
                    </b-button>
                  </b-col>
                </b-row>
                <b-row class="mt-2">
                  <b-col>
                    <!-- // ? Button Ubah Password -->
                    <!-- <div class="btn-bottom"> -->
                    <b class="text-success" v-if="status_pesan && pesan">
                      {{ pesan }}
                    </b>
                    <b class="text-danger" v-else-if="!status_pesan && pesan">
                      {{ pesan }}
                    </b>
                    <b-button
                      class="btn-form"
                      block
                      v-b-modal.modal-ubah-password
                    >
                      Ubah Kata Sandi
                    </b-button>
                    <!-- </div> -->
                  </b-col>
                </b-row>
              </b-col>
              <b-col md="8">
                <b-card-body>
                  <b-table-simple
                    class="table-detail-profile"
                    hover
                    striped
                    responsive
                  >
                    <b-tbody>
                      <b-tr>
                        <b-td>Nama</b-td>
                        <b-td>{{ this.pengguna.nama_pengguna }}</b-td>
                      </b-tr>
                      <b-tr>
                        <b-td>NRP/NIK</b-td>
                        <b-td>{{ this.pengguna.kode_pengguna }}</b-td>
                      </b-tr>
                      <b-tr>
                        <b-td>Tempat, Tanggal Lahir</b-td>
                        <b-td>
                          {{ this.pengguna.kota_lahir }}
                          {{ this.pengguna.kota_lahir != null ? ", " : "" }}
                          {{ this.pengguna.tanggal_lahir }}
                        </b-td>
                      </b-tr>
                      <b-tr>
                        <b-td>Email</b-td>
                        <b-td>{{ this.pengguna.email }}</b-td>
                      </b-tr>
                      <b-tr>
                        <b-td>Nomor HP</b-td>
                        <b-td>{{ this.pengguna.nomor_hp }}</b-td>
                      </b-tr>
                      <b-tr>
                        <b-td>Nomor Telepon</b-td>
                        <b-td>{{ this.pengguna.nomor_telepon }}</b-td>
                      </b-tr>
                      <b-tr>
                        <b-td>Kontak Lainnya</b-td>
                        <b-td>
                          <b-form-textarea
                            id="textarea-plaintext"
                            plaintext
                            max-rows="5"
                            :value="this.pengguna.kontak_lainnya"
                          ></b-form-textarea>
                        </b-td>
                      </b-tr>
                    </b-tbody>
                  </b-table-simple>
                </b-card-body>
              </b-col>
            </b-row>
          </b-card>
          <!-- // ? Modal Ubah Password -->
          <b-modal
            id="modal-ubah-password"
            ref="modalUbahPassword"
            title="Ubah Kata Sandi"
            centered
          >
            <!-- // ? Form Ubah Password -->
            <form ref="form" @submit.prevent="validasiUbahPassword">
              <b-row>
                <b-col class="label-form">Lama</b-col>
                <b-col class="input-form-text">
                  <b-form-input
                    class="input-form-text-item"
                    id="input-user-pass"
                    v-model.trim="$v.input_user_pass.$model"
                    placeholder="Kata Sandi Lama"
                    type="password"
                  ></b-form-input>
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.input_user_pass.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-col>
              </b-row>
              <b-row>
                <b-col class="label-form">Baru</b-col>
                <b-col class="input-form-text">
                  <b-form-input
                    class="input-form-text-item"
                    id="input-new-pass"
                    v-model.trim="$v.password.$model"
                    placeholder="Kata Sandi Baru"
                    type="password"
                  ></b-form-input>
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.password.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-col>
              </b-row>
              <b-row>
                <b-col class="label-form">Ulangi</b-col>
                <b-col class="input-form-text">
                  <b-form-input
                    class="input-form-text-item"
                    id="input-new-pass"
                    v-model.trim="$v.repeat_password.$model"
                    placeholder="Ulangi Kata Sandi Baru"
                    type="password"
                  ></b-form-input>
                </b-col>
                <b-col class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="
                      !$v.repeat_password.sameAsPassword ||
                      !$v.repeat_password.required
                    "
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-col>
              </b-row>
            </form>
            <template v-slot:modal-footer>
              <div class="w-100">
                <p class="float-left" v-if="pesan">
                  <b class="text-success" v-if="status_pesan">{{ pesan }}</b>
                  <b class="text-danger" v-else>{{ pesan }}</b>
                </p>
                <b-button
                  size="sm"
                  class="btn-form float-right"
                  @click="validasiUbahPassword()"
                  :disabled="loading_ubah"
                >
                  {{ loading_ubah ? "" : "Ubah" }}
                  <ring-loader
                    class="loading-page"
                    color="white"
                    :size="25"
                    v-if="loading_ubah"
                  />
                </b-button>
              </div>
            </template>
          </b-modal>
          <ModalUpdateProfile
            v-if="pengguna"
            :pengguna="pengguna"
            @updated="updatedProfile"
          />
        </div>
      </div>
    </div>
  </b-container>
</template>
<script>
import { required, sameAs } from "vuelidate/lib/validators";
import MyInputText from "../components/MyInputText";
import MyDatePicker from "../components/MyDatePicker";
import ModalUpdateProfile from "../components/ModalUpdateProfile";
import Axios from "axios";
import config from "../config";
export default {
  components: {
    MyInputText,
    MyDatePicker,
    ModalUpdateProfile,
  },
  data() {
    return {
      mainProps: {
        blank: true,
        blankColor: "#777",
        width: 160,
        height: 210,
        class: "m1",
      },
      width_img: 160,
      height_img: 210,
      pengguna: null,
      input_user_id: this.$store.getters.pengguna.UserId,
      input_user_pass: null,
      password: null,
      repeat_password: null,
      loading_page: true,
      loading_ubah: false,
      pesan: null,
      status_pesan: false,
    };
  },
  validations: {
    input_user_pass: {
      required,
    },
    password: {
      required,
    },
    repeat_password: {
      required,
      sameAsPassword: sameAs("password"),
    },
  },
  async mounted() {
    await this.getDataProfile();
  },
  methods: {
    async getDataProfile() {
      await Axios.get(
        `${config.apiIbatsBiasaUrl}/GetProfile?kode_pengguna=` +
          this.input_user_id
      ).then((response) => {
        this.pengguna = response.data.data;
        console.log(this.pengguna);
        this.loading_page = false;
      });
    },
    validasiUbahPassword() {
      this.loading_ubah = true;
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.loading_ubah = false;
      } else {
        this.ubahPassword();
      }
    },
    ubahPassword() {
      this.loading_ubah = true;
      const formData = new FormData();

      formData.append("input_user_id", this.input_user_id);
      formData.append("input_user_pass", this.input_user_pass);
      formData.append("input_new_pass", this.password);
      Axios.post(`${config.apiIbatsBiasaUrl}/UpdatePass`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          if (response.data.id == 0) {
            this.pesan = "Kata Sandi Gagal diubah";
            this.status_pesan = false;
          } else if (response.data.id == 1) {
            this.pesan = "Kata Sandi Berhasil diubah";
            this.status_pesan = true;
            this.$bvModal.hide("modal-ubah-password");
          }
          this.loading_ubah = false;
        })
        .catch((response) => {
          this.pesan = "Server Error";
          this.loading_ubah = false;
        });
    },
    updatedProfile(profile) {
      console.log("before updated", this.pengguna);
      this.pengguna = profile;
      console.log("after updated", this.pengguna);
    },
  },
};
</script>
<style scoped>
.profile {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 10px;
  background-color: rgb(255, 255, 255);
}
.card-profile {
  margin: auto;
}
.table-detail-profile {
  margin: 0px;
  padding: 0px;
}
/* // * Css Button */
.btn-bottom {
  position: absolute;
  bottom: 10px;
  right: 20px;
  text-align: right;
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