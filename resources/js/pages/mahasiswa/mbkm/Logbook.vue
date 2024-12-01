<template>
    <div class="logbook">
      <table>
        <thead>
          <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Durasi Kegiatan</th>
            <th>Deskripsi Pekerjaan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in logbookData" :key="item.id">
            <td>{{ index + 1 }}</td>
            <td>{{ item.tanggal }}</td>
            <td>{{ item.durasi }}</td>
            <td>{{ item.deskripsi }}</td>
            <td>
              <button @click="editLogbook(item.id)">Edit</button>
              <button @click="deleteLogbook(item.id)">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        logbookData: []
      }
    },
    mounted() {
      axios.get('https://api-anda/logbook')
        .then(response => {
          this.logbookData = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    methods: {
      editLogbook(id) {
        // Implementasi logika untuk mengedit data logbook
      },
      deleteLogbook(id) {
        // Implementasi logika untuk menghapus data logbook
      }
    }
  }
  </script>