<template>
    <b-container>
        <div class="form-kirim-email">
            <!-- Title -->
            <div class="row">
                <div class="col">
                    <h3 class="keterangan-website my-2">Kirim Email Sidang</h3>
                </div>
                <!-- Pilih Batch -->
                <div class="col-3 pilihan-batch">
                    <b-form-select v-model="id_batch" size="sm" @change="getSidangDetails">
                        <template v-slot:first>
                            <option :value="null" disabled>-- Pilih Batch --</option>
                        </template>
                        <option v-for="batch in pilihan_batch" :key="batch.id_batch" :value="batch.id_batch">
                            {{ batch.nama_batch }}
                        </option>
                    </b-form-select>
                </div>
            </div>

            <b-row v-if="sidangDetails.length > 0">
                <b-col>
                    <b-table :items="sidangDetails" :fields="fields" hover striped responsive="sm">
                        <template v-slot:cell(tanggal_sidang)="data">{{ data.item.tanggal_sidang }}</template>
                        <template v-slot:cell(waktu_sidang)="data">
                            {{ data.item.waktu_mulai_sidang }} - {{ data.item.waktu_akhir_sidang }}
                        </template>
                        <template v-slot:cell(nilai_pembimbing)="data">{{ data.item.nilai_pembimbing }}</template>
                        <template v-slot:cell(aksi)="row">
                            <b-button @click="openEmailModal(row.item)" size="sm" variant="primary">
                                Kirim Email
                            </b-button>
                        </template>
                    </b-table>
                </b-col>
            </b-row>

            <!-- Modal Kirim Email -->
            <b-modal v-model="showEmailModal" title="Kirim Email Sidang" @ok="sendEmail">
                <b-form @submit.prevent="sendEmail">
                    <b-form-group label="Penerima" label-for="recipients">
                        <b-form-input id="recipients" v-model="emailData.recipients"
                            placeholder="Masukkan email penerima" required />
                    </b-form-group>
                    <b-form-group label="Subjek" label-for="subject">
                        <b-form-input id="subject" v-model="emailData.subject" placeholder="Masukkan subjek email"
                            required />
                    </b-form-group>
                    <b-form-group label="Pesan" label-for="message">
                        <b-form-textarea id="message" v-model="emailData.message" rows="4" placeholder="Masukkan pesan"
                            required />
                    </b-form-group>
                    <b-button type="submit" variant="primary" :disabled="loading">Kirim Email</b-button>
                </b-form>
            </b-modal>
        </div>
    </b-container>
</template>
<script>
import Axios from 'axios';
import config from '../../../config';

export default {
    data() {
        return {
            id_batch: null,
            pilihan_batch: [],
            sidangDetails: [],
            fields: [
                { key: 'tanggal_sidang', label: 'Tanggal Sidang', sortable: true },
                { key: 'waktu_sidang', label: 'Waktu Sidang', sortable: true },
                { key: 'nilai_pembimbing', label: 'Nilai Pembimbing', sortable: true },
                { key: 'aksi', label: 'Aksi', sortable: false },
            ],
            showEmailModal: false,
            emailData: {
                recipients: '',
                subject: '',
                message: ''
            },
            loading: false,
        };
    },
    mounted() {
        this.getListBatch();
    },
    methods: {
        // Ambil daftar batch yang tersedia
        getListBatch() {
            Axios.get(`${config.apiUrl}/batch`)
                .then(response => {
                    this.pilihan_batch = response.data;
                })
                .catch(error => {
                    console.error('Error fetching batches:', error);
                });
        },
        // Ambil detail sidang berdasarkan batch yang dipilih
        getSidangDetails() {
            if (!this.id_batch) return;
            Axios.get(`${config.apiUrl}/sidang/details/${this.id_batch}`)
                .then(response => {
                    this.sidangDetails = response.data;
                })
                .catch(error => {
                    console.error('Error fetching sidang details:', error);
                });
        },
        // Buka modal untuk mengirimkan email
        openEmailModal(sidang) {
            const { tanggal_sidang, waktu_mulai_sidang, waktu_akhir_sidang, nilai_pembimbing } = sidang;
            this.emailData.recipients = ''; // Kosongkan input penerima
            this.emailData.subject = `Penjadwalan Sidang: ${tanggal_sidang}`;
            this.emailData.message = `
          Detail Sidang:
          
          Tanggal: ${tanggal_sidang}
          Waktu: ${waktu_mulai_sidang} - ${waktu_akhir_sidang}
          Nilai Pembimbing: ${nilai_pembimbing}
          
          Harap hadir tepat waktu.
        `;
            this.showEmailModal = true;
        },
        // Kirim email
        sendEmail() {
            this.loading = true;
            const formData = new FormData();
            formData.append('recipients', this.emailData.recipients);
            formData.append('subject', this.emailData.subject);
            formData.append('message', this.emailData.message);

            Axios.post(`${config.apiUrl}/send-email`, formData)
                .then(response => {
                    this.$bvToast.toast('Email berhasil dikirim!', {
                        variant: 'success',
                        solid: true,
                    });
                    this.showEmailModal = false;
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error sending email:', error);
                    this.$bvToast.toast('Gagal mengirim email. Coba lagi.', {
                        variant: 'danger',
                        solid: true,
                    });
                    this.loading = false;
                });
        }
    },
};
</script>
<style scoped>
.form-kirim-email {
    margin-top: 20px;
}

.btn-primary {
    background-color: #5bc0de;
    border-color: #46b8da;
}

.btn-primary:hover {
    background-color: #31b0d5;
}

.b-table th {
    background-color: #f1f1f1;
}

.b-table td {
    font-size: 14px;
}

.b-modal .modal-header {
    background-color: #5bc0de;
    color: white;
}

.b-modal .modal-body {
    background-color: #f9f9f9;
}

.b-modal .modal-footer {
    background-color: #f1f1f1;
}
</style>