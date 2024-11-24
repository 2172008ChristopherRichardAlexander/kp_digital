import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios'

import config from "../config"

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    status: '',
    pengguna: localStorage.getItem('auth') || '',
    jabatan: [],
    topik: {},
    batch_topik: null,
    batch_sidang: null,
    list_batch: null,
    list_batch_topik: null,
    list_batch_sidang: null,
    list_mahasiswa: null,
    list_dosen: null,
  },
  mutations: {
    auth_request(state) {
      state.status = 'loading'
    },
    auth_success(state, user) {
      state.status = 'success'
      state.pengguna = user
    },
    auth_error(state) {
      state.status = 'error'
    },
    logout(state) {
      state.status = ''
      state.pengguna = null
      state.jabatan = []
    },
    jabatan(state, jabatan) {
      state.jabatan = [
        ...state.jabatan,
        jabatan
      ]
    },
    batchTopik(state, batch) {
      state.batch_topik = batch
    },
    batchSidang(state, batch) {
      state.batch_sidang = batch
    },
    listBatch(state, list) {
      state.list_batch = list
    },
    listBatchTopik(state, list) {
      state.list_batch_topik = list
    },
    listBatchSidang(state, list) {
      state.list_batch_sidang = list
    },
    listMahasiswa(state, list) {
      state.list_mahasiswa = list
    },
    listDosen(state, list) {
      state.list_dosen = list
    },
    topik(state, topik) {
      state.topik = topik
    },
  },
  actions: {
    login({ commit }, user) {
      return new Promise((resolve, reject) => {
        commit('auth_request')
        Axios({ url: `${config.apiLoginUrl}`, data: user, method: 'POST' })
          .then(resp => {
            const user = resp.data
            if (user.Message == "Login Success") {
              localStorage.setItem("auth", JSON.stringify(user));
              commit('auth_success', JSON.stringify(user))
            }
            resolve(resp)
          })
          .catch(err => {
            commit('auth_error')
            localStorage.removeItem('auth')
            reject(err)
          })
      })
    },
    logout({ commit }) {
      return new Promise((resolve, reject) => {
        commit('logout')
        localStorage.removeItem('auth')
        resolve()
      })
    },
    jabatan({ commit }, id_pengguna) {
      return new Promise((resolve, reject) => {
        commit('auth_request')
        Axios({ url: `${config.apiUrl}/jabatan-pengguna/list`, data: id_pengguna, method: 'POST' })
          .then(resp => {
            const listJabatan = resp.data.data
            listJabatan.forEach(element => {
              if (element.jabatan.nama_jabatan.includes("Ketua KBK") || element.jabatan.nama_jabatan.includes("Sekretaris KBK")) {
                if (!this.state.jabatan.includes("Koordinator KBK")) {
                  commit('jabatan', "Koordinator KBK")
                }
                if (!this.state.jabatan.includes(element.jabatan.nama_jabatan)) {
                  commit('jabatan', element.jabatan.nama_jabatan)
                }
              } else if (element.jabatan.id_role == 5) {
                if (!this.state.jabatan.includes("Dosen")) {
                  commit('jabatan', "Dosen")
                }
              } else {
                if (!this.state.jabatan.includes(element.jabatan.nama_jabatan)) {
                  commit('jabatan', element.jabatan.nama_jabatan)
                }
              }
            });
            if (this.state.pengguna) {
              const role = JSON.parse(this.state.pengguna).Id_Roles;
              var jenis = ''
              if (role == 3) {
                jenis = 'Mahasiswa'
              } else if (role == 2) {
                jenis = 'Dosen'
              } else if (role == 4) {
                jenis = 'Tata Usaha'
              }
              if (!this.state.jabatan.includes(jenis)) {
                commit('jabatan', jenis)
              }
            }
            resolve(resp)
          })
          .catch(err => {
            commit('jabatan', null)
            reject(err)
          })
      })
    },
    batchTopik({ commit }) {
      return new Promise((resolve, reject) => {
        // Axios({ url: `${config.apiUrl}/batch/newest/1`, method: 'GET' })
        Axios({ url: `${config.apiUrl}/batch/latest/1`, method: 'GET' })
          .then(response => {
            const batch_response = response.data
            commit('batchTopik', batch_response)
            resolve(response)
          }).catch(err => {
            reject(err)
          });
      });
    },
    batchSidang({ commit }) {
      return new Promise((resolve, reject) => {
        // Axios({ url: `${config.apiUrl}/batch/newest/2`, method: 'GET' })
        Axios({ url: `${config.apiUrl}/batch/latest/2`, method: 'GET' })
          .then(response => {
            const batch_response = response.data
            commit('batchSidang', batch_response)
            resolve(response)
          }).catch(err => {
            reject(err)
          });
      });
    },
    listBatch({ commit }, data) {
      return new Promise((resolve, reject) => {
        Axios.post(`${config.apiUrl}/batch/daftar`, data.data_batch, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
        })
          .then(response => {
            const list = response.data.data;
            if (data.jenis_batch == 1)
              commit('listBatchTopik', list)
            else if (data.jenis_batch == 2)
              commit('listBatchSidang', list)
            else
              commit('listBatch', list)
            resolve(response)
          })
          .catch(response => {
            reject(response)
          });
      })
    },
    listMahasiswa({ commit }) {
      return new Promise((resolve, reject) => {
        Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered?input_id_roles=3`)
          .then(response => {
            const list = response.data
            commit('listMahasiswa', list)
            resolve(response)
          })
          .catch(response => {
            reject(response)
          });
      })
    },
    listDosen({ commit }) {
      return new Promise((resolve, reject) => {
        Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered?input_id_roles=2`)
          .then(response => {
            const list = response.data
            commit('listDosen', list)
            resolve(response)
          })
          .catch(response => {
            reject(response)
          });
      })
    },
    topik({ commit }, data_topik) {
      return new Promise((resolve, reject) => {
        // Axios({ url: `${config.apiMahasiswaUrl}/topik/terbaru`, data: data_topik, method: 'POST' })
        Axios.post(`${config.apiMahasiswaUrl}/topik/terbaru`, data_topik, {
          // headers: { "Content-Type": "application/json" }
          headers: { "Content-Type": "multipart/form-data" }
        })
          .then(response => {
            if (response.data.message != "kosong") {
              const topik_response = response.data.data[0]
              commit('topik', topik_response)
            } else {
              commit('topik', null)
            }
            resolve(response)
          })
          .catch(err => {
            reject(err)
          });
      });
    },
  },
  getters: {
    isLoggedIn: state => state.pengguna,
    authStatus: state => state.status,
    pengguna: state => state.pengguna ? JSON.parse(state.pengguna) : null,
    jabatan: state => state.jabatan,
    batchTopik: state => state.batch_topik,
    batchSidang: state => state.batch_sidang,
    topik: state => state.topik,
    listBatch: state => state.list_batch,
    listBatchTopik: state => state.list_batch_topik,
    listBatchSidang: state => state.list_batch_sidang,
    listMahasiswa: state => state.list_mahasiswa,
    listDosen: state => state.list_dosen,
  }
})

export default store