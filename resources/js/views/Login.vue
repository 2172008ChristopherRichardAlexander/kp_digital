<template>
  <b-row style="padding:0px;margin:0px;">
    <b-col cols="7" style="padding:0px;margin:0px;">
      <b-img
        class="gambar"
        left
        src="https://media.istockphoto.com/photos/programming-code-abstract-technology-background-of-software-deve-picture-id629285904?k=6&m=629285904&s=612x612&w=0&h=GbdupQECY8IAzSkFu5fZuXV7angui0S3j64KRS2Ed6M="
        alt="Left image"
      ></b-img>
    </b-col>
    <b-col cols="5">
      <b-row>
        <b-col md="7" offset-md="2">
          <b-card
            class="border-0"
            style="background-color: rgba(255, 255, 255, 0); margin-top:20vh;"
          >
            <b-form v-if="show" @submit.prevent="login">
              <b-form-group id="input-group-1">
                <b-form-input
                  id="input_user_id"
                  v-model="input_user_id"
                  type="text"
                  required
                  placeholder="Kode Pengguna"
                  size="sm"
                ></b-form-input>
                <!-- error handling -->
                <!-- <div class="errors" v-if="errors.email">
              <small class="text-danger" :key="error" v-for="error in errors.email">{{error}}</small>
                </div>-->
              </b-form-group>

              <b-form-group id="input-group-2">
                <b-form-input
                  id="input_user_password"
                  v-model="input_user_password"
                  type="password"
                  required
                  placeholder="Kata Sandi"
                  size="sm"
                ></b-form-input>
                <!-- error handling -->
                <!-- <div class="errors" v-if="errors.password">
              <small class="text-danger" :key="error" v-for="error in errors.password">{{error}}</small>
                </div>-->
                <div class="errors" v-if="pesan">
                  <small class="text-danger">{{pesan}}</small>
                </div>
              </b-form-group>
              <b-form-group class="text-center">
                <b-button
                  type="submit"
                  class="form-control"
                  variant="info"
                  size="sm"
                  :disabled="loading"
                >
                  {{loading?'':'Login'}}
                  <ring-loader class="loading-page" color="white" :size="25" v-if="loading" />
                </b-button>
                <!-- <b-button
                  type="button"
                  @click="loginUser()"
                  class="form-control"
                  variant="info"
                  size="sm"
                >Login</b-button>-->
              </b-form-group>
            </b-form>
          </b-card>
        </b-col>
      </b-row>
    </b-col>
  </b-row>
</template>
<script>
// import Axios from "axios";
// import config from "../config";
export default {
  data() {
    return {
      input_user_id: "",
      input_user_password: "",
      show: true,
      errors: {},
      loading: false,
      pesan: null
    };
  },
  methods: {
    login: function() {
      this.loading = true;
      let input_user_id = this.input_user_id;
      let input_user_password = this.input_user_password;
      this.$store
        .dispatch("login", { input_user_id, input_user_password })
        .then(response => {
          if (response.data.Message == "Login Success") {
            this.getListJabatan(response.data.UserId);
          } else {
            this.pesan = "Kode Pengguna atau Kata Sandi Salah";
            this.loading = false;
          }
        })
        .catch(response => {
          console.log(response);
        });
    },
    getListJabatan: function(input_user_id) {
      let id_pengguna = input_user_id;
      this.$store
        .dispatch("jabatan", { id_pengguna })
        .then(response => {
          this.loading = false;
          this.$router.push("/");
        })
        .catch(response => {
          console.log(response);
        });
    }
    // loginUser() {
    //   this.loading = true;
    //   //api request
    //   Axios.post(`${config.apiLoginUrl}`, {
    //     input_user_id: this.input_user_id,
    //     input_user_password: this.input_user_password
    //   })
    //     .then(response => {
    //       this.loading = false;
    //       // after success
    //       this.$root.auth = response.data;
    //       // console.log(JSON.stringify(response.data));
    //       localStorage.setItem("auth", JSON.stringify(response.data));
    //       // this.$noty.success("Successfully logged in.");
    //       // this.$router.push("/topik/pengajuan");
    //     })
    //     // Axios.post("https://react-blog-api.bahdcasts.com/api/auth/login", {
    //     //   email: this.input_user_id,
    //     //   password: this.input_user_password
    //     // })
    //     // .then(response => {
    //     //   this.loading = false;
    //     //   // after success
    //     //   this.$root.auth = response.data.data;
    //     //   localStorage.setItem("auth", JSON.stringify(response.data.data));
    //     //   // this.$noty.success("Successfully logged in.");
    //     //   this.$router.push("/");
    //     // })
    //     .catch(({ response }) => {
    //       // if there is error
    //       this.loading = false;
    //       // this.$noty.error("Oops ! something went wrong.");
    //       // if (response.status == 401) {
    //       //   this.errors = {
    //       //     email: ["These credentials do not match our records."]
    //       //   };
    //       // } else {
    //       //   this.errors = response.data;
    //       // }
    //       // this.afterLogin();
    //     });
    // }
    // afterLogin() {
    //   var jabatan = 0;
    //   if (this.$root.auth.Id_Roles) {
    //     jabatan = 3;
    //   }
    //   Axios.post(`${config.apiUrl}/jabatanPengguna/firstLogin`, {
    //     id_pengguna: this.input_user_id,
    //     id_jabatan: jabatan
    //   });
    // }
  }
};
</script>

<style scoped>
.gambar {
  background-size: cover;
  width: 100%;
  padding: 0px;
  margin: 0px;
  left: 0;
  min-height: 100%;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
}
.loading-text {
  color: rgb(32, 165, 6);
}
</style>