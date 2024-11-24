<template>
  <b-container>
    <div class="form-tambah-syarat-ketentuan">
      <!-- Title Keterangan Halaman -->
      <div class="row">
        <div class="col">
          <h3 class="keterangan-website my-2">Tambah Syarat dan Ketentuan</h3>
        </div>
      </div>
      <div class="tambah-syarat-ketentuan">
        <b-form @submit.prevent="onSubmit" v-if="show" enctype="multipart/form-data" method="post">
          <b-table-simple class="table-tambah-syarat-ketentuan">
            <b-thead>
              <b-tr class="row-tambah-syarat-ketentuan-title">
                <b-th class="space-kosong">&nbsp;</b-th>
                <b-th colspan="3">
                  <h4 class="my-2">Form Tambah Syarat dan Ketentuan</h4>
                </b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr class="row-tambah-syarat-ketentuan">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Judul</b-td>
                <b-td class="input-form-text">
                  <b-form-input
                    class="input-form-text-item"
                    id="input-judul"
                    v-model.trim="$v.judul.$model"
                    placeholder="Judul"
                    size="sm"
                  ></b-form-input>
                </b-td>
                <b-td class="feedback-validasi">
                  <span class="feedback-validasi-false" v-if="!$v.judul.required">&#10006;</span>
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="row-tambah-syarat-ketentuan">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Deskripsi</b-td>
                <b-td class="input-form-textarea">
                  <!-- <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-deskripsi"
                    v-model.trim="deskripsi"
                    placeholder="Deskripsi"
                    rows="5"
                    max-rows="5"
                  ></b-form-textarea>-->

                  <wysiwyg class="input-form-textarea-item" v-model="deskripsi" />
                </b-td>
                <b-td class="feedback-validasi">
                  <!-- <span class="feedback-validasi-false" v-if="!$v.deskripsi.required">&#10006;</span>
                  <span class="feedback-validasi-true" v-else>&#10004;</span>-->
                </b-td>
              </b-tr>

              <b-tr class="row-tambah-syarat-ketentuan">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Jenis Role</b-td>
                <b-td class="input-form-select-box">
                  <b-form-select
                    class="input-form-select-box-item"
                    v-model.trim="$v.role.$model"
                    size="sm"
                  >
                    <template v-slot:first>
                      <option :value="null" disabled>Pilih Role</option>
                    </template>
                    <template v-if="kumpulan_role">
                      <option
                        :value="role.id_role"
                        v-for="role in kumpulan_role"
                        :key="role.id_role"
                      >{{role.nama_role}}</option>
                    </template>
                  </b-form-select>
                </b-td>
                <b-td class="feedback-validasi">
                  <span class="feedback-validasi-false" v-if="!$v.role.required">&#10006;</span>
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="row-tambah-syarat-ketentuan">
                <b-td colspan="3" class="input-form-submit text-right">
                  <b-button type="submit" class="btn-form">Tambah</b-button>
                </b-td>
                <b-td class="feedback-validasi">&nbsp;</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-form>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import { required, requiredIf, between } from "vuelidate/lib/validators";
export default {
  data() {
    return {
      judul: "",
      deskripsi: "",
      role: null,
      kumpulan_role: [],
      syarat_ketentuan: [],
      show: true,
      loading: false,
      submit_status: null
    };
  },
  mounted() {
    this.getListRoleSyaratKetentuan();
  },
  validations: {
    judul: {
      required
    },
    // deskripsi: {
    //   required
    // },
    role: {
      required
    }
  },
  methods: {
    getListRoleSyaratKetentuan() {
      this.loading = true;
      Axios.get(`${config.apiKoorKpUrl}/role/syarat-ketentuan`)
        .then(response => {
          this.kumpulan_role = response.data.data;
          this.loading = false;
        })
        .catch(response => {
          this.loading = false;
          console.log(response);
        });
    },
    getListSyaratKetentuanAktif(
      url = `${config.apiKoorKpUrl}/syarat-ketentuan-aktif/` + this.role
    ) {
      this.loading = true;
      Axios.get(url)
        .then(response => {
          this.syarat_ketentuan = response.data.data;
          this.syarat_ketentuan.forEach(element => {
            this.updateStatusSyaratKetentuan(element, 0);
          });
        })
        .catch(response => {
          this.loading = false;
          console.log(response);
        });
    },
    updateStatusSyaratKetentuan(data, status) {
      this.loading = true;
      Axios.put(
        `${config.apiKoorKpUrl}/syarat-ketentuan`,
        {
          id_syarat_ketentuan_sidang: data.id_syarat_ketentuan_sidang,
          judul_syarat_ketentuan_sidang: data.judul_syarat_ketentuan_sidang,
          deskripsi_syarat_ketentuan_sidang:
            data.deskripsi_syarat_ketentuan_sidang,
          status_syarat_ketentuan_sidang: status,
          id_role: data.id_role
        },
        // formData,
        {
          headers: {
            "Content-Type": "application/json"
            // "Content-Type": "multipart/form-data"
          }
        }
      )
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    onSubmit() {
      this.loading = true;
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.submit_status = "GAGAL";
      } else {
        this.getListSyaratKetentuanAktif();
        this.submit_status = "BERHASIL";
      }
      if (this.submit_status === "BERHASIL") {
        const formData = new FormData();
        formData.append("judul_syarat_ketentuan_sidang", this.judul);
        formData.append("deskripsi_syarat_ketentuan_sidang", this.deskripsi);
        formData.append("status_syarat_ketentuan_sidang", 1);
        formData.append("id_role", this.role);
        Axios.post(`${config.apiKoorKpUrl}/syarat-ketentuan`, formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
          .then(() => {
            this.loading = false;
            this.$router.push("/sidang/syarat-dan-ketentuan");
          })
          .catch(() => {
            this.loading = false;
          });
      }
    }
  }
};
</script>
<style scoped>
/* .keterangan-website {
  margin-left: 10px;
  padding-left: 10px;
} */
.tambah-syarat-ketentuan {
  background-color: white;
  border-bottom: 3px solid rgb(202, 206, 147);
}

.row-tambah-syarat-ketentuan-title {
  background-color: rgb(202, 206, 147);
  /* color: rgb(243, 249, 215); */
  color: rgb(255, 255, 255);
}

.row-tambah-syarat-ketentuan:nth-child(even) {
  background-color: rgb(202, 206, 147);
  /* color: rgb(243, 249, 215); */
  color: rgb(255, 255, 255);
}
/* // * CSS Space Kosong */
.space-kosong {
  min-width: 1vw;
}
/* // * CSS Label Form */
.label-form {
  min-width: 50px;
  max-width: 50px;
}
/* // * CSS Input Textarea Item */
.input-form-textarea-item {
  max-width: 100%;
  min-width: 100%;
  background-color: rgb(255, 255, 255);
  color: rgb(0, 0, 0);
}
/* // * CSS Input Select Box Item */
.input-form-select-box-item {
  max-width: 100%;
  min-width: 100%;
}
/* // ! CSS Validasi Form */
.feedback-validasi {
  text-align: left;
  font-size: 20px;
}
.feedback-validasi-false {
  color: red;
}
.feedback-validasi-true {
  color: green;
}
/* // * Submit Button Item */
.btn-form {
  padding: 4px 20px;
  font-size: 15px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border-radius: 5px;
  border: none;
  text-align: center;
}
.btn-form:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}
</style>