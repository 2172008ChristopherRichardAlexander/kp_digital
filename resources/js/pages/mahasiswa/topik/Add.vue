<template>
  <b-container class="halaman-website">
    <div v-if="loading_page">
      <ring-loader
        class="loading-page"
        color="#bada55"
        :loading="loading_page"
        :size="150"
      />
    </div>
    <div v-else>
      <div v-if="!topik">
        <!-- Title Keterangan Halaman -->
        <div class="row">
          <div class="col">
            <h3 class="keterangan-website my-2">
              Pengajuan Topik Kerja Praktek
            </h3>
          </div>
        </div>

        <!-- Form Pengajuan Topik -->
        <b-form
          @submit.prevent="onSubmit"
          v-if="show"
          enctype="multipart/form-data"
          method="post"
        >
          <b-table-simple borderless responsive class="form-pengajuan">
            <b-thead>
              <b-tr class="form-pengajuan-title">
                <b-th class="space-kosong">&nbsp;</b-th>
                <b-th colspan="3">
                  <h4 class="my-2">Form Pengajuan Topik</h4>
                </b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <!--// ? Text -->
              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Judul Topik</b-td>
                <b-td class="input-form-text">
                  <b-form-input
                    class="input-form-text-item"
                    id="input-judul_topik"
                    v-model.trim="$v.judul_topik.$model"
                    placeholder="Judul Topik"
                    size="sm"
                  ></b-form-input>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.judul_topik.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>
              <!--// ? Textarea -->
              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Latar Belakang</b-td>
                <b-td class="input-form-textarea">
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-latar_belakang"
                    v-model.trim="$v.latar_belakang.$model"
                    placeholder="Latar Belakang"
                    rows="5"
                    max-rows="5"
                  ></b-form-textarea>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.latar_belakang.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Rumusan Masalah</b-td>
                <b-td class="input-form-textarea">
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-rumusan_masalah"
                    rumusan_masalah
                    v-model.trim="$v.rumusan_masalah.$model"
                    placeholder="Rumusan Masalah"
                    rows="5"
                    max-rows="5"
                  ></b-form-textarea>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.rumusan_masalah.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Tujuan Pembahasan</b-td>
                <b-td class="input-form-textarea">
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-tujuan_pembahasan"
                    placeholder="Tujuan Pembahasan"
                    v-model.trim="$v.tujuan_pembahasan.$model"
                    rows="5"
                    max-rows="5"
                  ></b-form-textarea>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.tujuan_pembahasan.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Ruang Lingkup</b-td>
                <b-td class="input-form-textarea">
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-ruang_lingkup"
                    v-model.trim="$v.ruang_lingkup.$model"
                    placeholder="Ruang Lingkup"
                    rows="5"
                    max-rows="5"
                  ></b-form-textarea>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.ruang_lingkup.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Sumber Data</b-td>
                <b-td class="input-form-textarea">
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-sumber_data"
                    v-model.trim="$v.sumber_data.$model"
                    placeholder="Sumber Data"
                    rows="5"
                    max-rows="5"
                  ></b-form-textarea>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.sumber_data.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Sistematika Penyajian</b-td>
                <b-td class="input-form-textarea">
                  <b-form-textarea
                    class="input-form-textarea-item"
                    id="input-sistematika_penyajian"
                    placeholder="Sistematika Penyajian"
                    v-model.trim="$v.sistematika_penyajian.$model"
                    rows="5"
                    max-rows="5"
                  ></b-form-textarea>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.sistematika_penyajian.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <!--// ? Pilihan -->
              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">KBK</b-td>
                <b-td class="input-form-select-box">
                  <b-form-select
                    class="input-form-select-box-item"
                    v-model.trim="$v.id_kbk.$model"
                    size="sm"
                  >
                    <template v-slot:first>
                      <option :value="null" disabled>Pilih KBK</option>
                    </template>
                    <template v-if="kumpulan_kbk">
                      <option
                        :value="kbk.id_kbk"
                        v-for="kbk in kumpulan_kbk"
                        :key="kbk.id_kbk"
                      >
                        {{ kbk.nama_kbk }}
                      </option>
                    </template>
                  </b-form-select>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.id_kbk.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Sumber Topik</b-td>
                <b-td
                  class="input-form-radio-button"
                  style="padding: 10px 0px 0px 0px; margin: 0px"
                >
                  <b-table-simple
                    borderless
                    responsive
                    style="width: 100%; padding: 0px; margin: 0px"
                  >
                    <b-tbody>
                      <b-tr>
                        <b-td class="row" style="padding: 0px; margin: 0px">
                          <div class="col" style="max-width: 150px">
                            <b-button-group size="sm">
                              <b-button
                                class="btn-radio"
                                v-if="sumber_topik == 'dosen'"
                                >Dosen</b-button
                              >
                              <b-button
                                class="btn-radio-disable"
                                @click="ubahSumberTopik('dosen')"
                                v-else
                                >Dosen</b-button
                              >
                              <b-button
                                class="btn-radio"
                                v-if="sumber_topik == 'instansi'"
                                >Instansi</b-button
                              >
                              <b-button
                                class="btn-radio-disable"
                                @click="ubahSumberTopik('instansi')"
                                v-else
                                >Instansi</b-button
                              >
                            </b-button-group>
                          </div>

                          <div class="col">
                            <div
                              v-if="sumber_topik === 'dosen'"
                              class="input-form-select-box"
                            >
                              <b-form-select
                                class="input-form-select-box-item"
                                v-model.trim="$v.id_pembimbing.$model"
                                size="sm"
                              >
                                <template v-slot:first>
                                  <option :value="null" disabled>
                                    Pilih Dosen
                                  </option>
                                </template>
                                <template v-if="kumpulan_dosen">
                                  <option
                                    :value="dosen.UserId"
                                    v-for="dosen in kumpulan_dosen"
                                    :key="dosen.UserId"
                                  >
                                    {{ dosen.Name }}
                                  </option>
                                </template>
                              </b-form-select>
                            </div>

                            <div
                              v-else-if="sumber_topik === 'instansi'"
                              class="input-form-file"
                              style="padding-bottom: 0px"
                            >
                              <b-row class="baris-input-file">
                                <b-col lg="10" class="kolom-input-file">
                                  <b-form-input
                                    type="text"
                                    class="input-form-file-item-text"
                                    v-if="form_persetujuan_instansi"
                                    :value="form_persetujuan_instansi.name"
                                    disabled
                                    size="sm"
                                  />
                                  <b-form-input
                                    type="text"
                                    class="input-form-file-item-text"
                                    v-else
                                    placeholder="Form Persetujuan Instansi"
                                    disabled
                                    size="sm"
                                  />
                                </b-col>
                                <b-col class="btn-kolom-input-file">
                                  <file-select
                                    class="input-form-file-item-button"
                                    v-model="form_persetujuan_instansi"
                                  ></file-select>
                                </b-col>
                              </b-row>

                              <!-- <b-table-simple>
                                <b-tbody>
                                  <b-tr>
                                    <b-td style="padding:0px; margin:0px;">
                                      <b-form-input
                                        type="text"
                                        class="input-form-file-item-text"
                                        v-if="form_persetujuan_instansi"
                                        :value="form_persetujuan_instansi.name"
                                        disabled
                                        size="sm"
                                      />
                                      <b-form-input
                                        type="text"
                                        class="input-form-file-item-text"
                                        v-else
                                        placeholder="Form Persetujuan Instansi"
                                        disabled
                                        size="sm"
                                      />
                                    </b-td>
                                    <b-td
                                      style="padding: 0px;margin: 0px;min-width: 39px;max-width: 39px;"
                                    >
                                      <file-select
                                        class="input-form-file-item-button"
                                        v-model="form_persetujuan_instansi"
                                      ></file-select>
                                    </b-td>
                                  </b-tr>
                                </b-tbody>
                              </b-table-simple>-->
                              <b-form-file
                                class="d-none"
                                type="file"
                                accept="image/*"
                                @change="
                                  addFile('form_persetujuan_instansi', $event)
                                "
                                placeholder="Form Persetujuan Instansi"
                                size="sm"
                              ></b-form-file>
                            </div>
                          </div>
                        </b-td>
                      </b-tr>
                    </b-tbody>
                  </b-table-simple>
                </b-td>
                <b-td class="feedback-validasi" v-if="sumber_topik === 'dosen'">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.id_pembimbing.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
                <b-td
                  class="feedback-validasi"
                  v-else-if="sumber_topik === 'instansi'"
                >
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.form_persetujuan_instansi.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <!--// ? File -->
              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">DKBS</b-td>
                <b-td class="input-form-file">
                  <b-row class="baris-input-file">
                    <b-col lg="10" class="kolom-input-file">
                      <b-form-input
                        type="text"
                        class="input-form-file-item-text"
                        v-if="dkbs"
                        :value="dkbs.name"
                        disabled
                        size="sm"
                      />
                      <b-form-input
                        type="text"
                        class="input-form-file-item-text"
                        v-else
                        placeholder="DKBS"
                        disabled
                        size="sm"
                      />
                    </b-col>
                    <b-col class="btn-kolom-input-file">
                      <file-select
                        class="input-form-file-item-button"
                        v-model="dkbs"
                      ></file-select>
                    </b-col>
                  </b-row>
                  <!-- <b-table-simple>
                    <b-tbody>
                      <b-tr>
                        <b-td style="padding:0px; margin:0px;">
                          <b-form-input
                            type="text"
                            class="input-form-file-item-text"
                            v-if="dkbs"
                            :value="dkbs.name"
                            disabled
                            size="sm"
                          />
                          <b-form-input
                            type="text"
                            class="input-form-file-item-text"
                            v-else
                            placeholder="DKBS"
                            disabled
                            size="sm"
                          />
                        </b-td>
                        <b-td class="btn-file-input">
                          <file-select class="input-form-file-item-button" v-model="dkbs"></file-select>
                        </b-td>
                      </b-tr>
                    </b-tbody>
                  </b-table-simple>-->
                  <b-form-file
                    class="d-none"
                    type="file"
                    accept="image/*"
                    @change="addFile('dkbs', $event)"
                    placeholder="DKBS"
                    size="sm"
                  ></b-form-file>
                </b-td>
                <b-td class="feedback-validasi">
                  <span class="feedback-validasi-false" v-if="!$v.dkbs.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Transkrip Nilai</b-td>
                <b-td class="input-form-file">
                  <b-row class="baris-input-file">
                    <b-col lg="10" class="kolom-input-file">
                      <b-form-input
                        type="text"
                        class="input-form-file-item-text"
                        v-if="transkrip_nilai"
                        :value="transkrip_nilai.name"
                        disabled
                        size="sm"
                      />
                      <b-form-input
                        type="text"
                        class="input-form-file-item-text"
                        v-else
                        placeholder="Form Transkrip Nilai"
                        disabled
                        size="sm"
                      />
                    </b-col>
                    <b-col class="btn-kolom-input-file">
                      <file-select
                        class="input-form-file-item-button"
                        v-model="transkrip_nilai"
                      ></file-select>
                    </b-col>
                  </b-row>
                  <!-- <b-table-simple>
                    <b-tbody>
                      <b-tr>
                        <b-td style="padding:0px; margin:0px;">
                          <b-form-input
                            type="text"
                            class="input-form-file-item-text"
                            v-if="transkrip_nilai"
                            :value="transkrip_nilai.name"
                            disabled
                            size="sm"
                          />
                          <b-form-input
                            type="text"
                            class="input-form-file-item-text"
                            v-else
                            placeholder="Form Transkrip Nilai"
                            disabled
                            size="sm"
                          />
                        </b-td>
                        <b-td class="btn-file-input">
                          <file-select
                            class="input-form-file-item-button"
                            v-model="transkrip_nilai"
                          ></file-select>
                        </b-td>
                      </b-tr>
                    </b-tbody>
                  </b-table-simple>-->
                  <b-form-file
                    class="d-none"
                    type="file"
                    accept="image/*"
                    @change="addFile('transkrip_nilai', $event)"
                    placeholder="Transkrip Nilai"
                    size="sm"
                  ></b-form-file>
                </b-td>
                <b-td class="feedback-validasi">
                  <span
                    class="feedback-validasi-false"
                    v-if="!$v.transkrip_nilai.required"
                    >&#10006;</span
                  >
                  <span class="feedback-validasi-true" v-else>&#10004;</span>
                </b-td>
              </b-tr>

              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">Lampiran Gambar</b-td>
                <b-td class="input-form-file">
                  <b-row class="baris-input-file">
                    <b-col lg="10" class="kolom-input-file">
                      <b-form-input
                        type="text"
                        class="input-form-file-item-text"
                        v-if="lampiran_gambar"
                        :value="lampiran_gambar.name"
                        disabled
                        size="sm"
                      />
                      <b-form-input
                        type="text"
                        class="input-form-file-item-text"
                        v-else
                        placeholder="Lampiran Gambar (Opsional)"
                        disabled
                        size="sm"
                      />
                    </b-col>
                    <b-col class="btn-kolom-input-file">
                      <file-select
                        class="input-form-file-item-button"
                        v-model="lampiran_gambar"
                      ></file-select>
                    </b-col>
                  </b-row>
                  <!-- <b-table-simple>
                    <b-tbody>
                      <b-tr>
                        <b-td style="padding:0px; margin:0px;">
                          <b-form-input
                            type="text"
                            class="input-form-file-item-text"
                            v-if="lampiran_gambar"
                            :value="lampiran_gambar.name"
                            disabled
                            size="sm"
                          />
                          <b-form-input
                            type="text"
                            class="input-form-file-item-text"
                            v-else
                            placeholder="Lampiran Gambar (Opsional)"
                            disabled
                            size="sm"
                          />
                        </b-td>
                        <b-td class="btn-file-input">
                          <file-select
                            class="input-form-file-item-button"
                            v-model="lampiran_gambar"
                          ></file-select>
                        </b-td>
                      </b-tr>
                    </b-tbody>
                  </b-table-simple>-->
                  <!-- <b-form-file
                    class="d-none"
                    type="file"
                    accept="image/*"
                    @change="addFile('lampiran_gambar', $event)"
                    placeholder="Lampiran Gambar"
                    size="sm"
                  ></b-form-file>-->
                </b-td>
                <b-td class="feedback-validasi">&nbsp;</b-td>
              </b-tr>
              <!-- // ? Button Pengajuan -->
              <b-tr class="form-pengajuan-item">
                <b-td class="space-kosong">&nbsp;</b-td>
                <b-td class="label-form">&nbsp;</b-td>
                <b-td class="input-form-submit text-right">
                  <b-button
                    type="submit"
                    class="btn-form"
                    :disabled="loading || batch.status_batch != 1"
                  >
                    {{ loading ? "" : "Ajukan Topik" }}
                    <ring-loader
                      class="loading-page"
                      color="white"
                      :size="25"
                      v-if="loading"
                    />
                  </b-button>
                </b-td>
                <b-td class="feedback-validasi">&nbsp;</b-td>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-form>
      </div>
      <div v-else>
        <!-- Title Keterangan Halaman -->
        <div class="row">
          <div class="col">
            <h3 class="keterangan-website my-2">Detail Pengajuan Topik</h3>
          </div>
        </div>
        <div class="detail-topik">
          <DetailPengajuan :topik="topik" />
        </div>
      </div>
    </div>
  </b-container>
</template>
<script>
import Axios from "axios";
import config from "../../../config";
import { required, requiredIf } from "vuelidate/lib/validators";
import FileSelect from "../../../components/FileSelect";
import DetailPengajuan from "./DetailPengajuan";

export default {
  components: {
    FileSelect,
    DetailPengajuan,
  },
  data() {
    return {
      is_mbkm: 0,
      judul_topik: "",
      latar_belakang: "",
      rumusan_masalah: "",
      tujuan_pembahasan: "",
      ruang_lingkup: "",
      sumber_data: "",
      sistematika_penyajian: "",
      transkrip_nilai: null,
      dkbs: null,
      form_persetujuan_instansi: null,
      lampiran_gambar: null,
      status_persetujuan_dosen: 3,
      status_topik: 3,
      batch: {},
      id_batch: null,
      id_kbk: null,
      id_pengaju: this.$store.getters.pengguna.UserId,
      id_pembimbing: null,
      kumpulan_kbk: {},
      sumber_topik: "dosen",
      kumpulan_dosen: {},
      tanggal_pengajuan_topik:
        new Date().getFullYear() +
        "-" +
        (new Date().getMonth() + 1) +
        "-" +
        new Date().getDate(),
      loading: false,
      loading_page: false,
      show: true,
      submit_status: null,
      topik: null,
    };
  },
  validations: {
    judul_topik: {
      required,
    },
    latar_belakang: {
      required,
    },
    rumusan_masalah: {
      required,
    },
    tujuan_pembahasan: {
      required,
    },
    ruang_lingkup: {
      required,
    },
    sumber_data: {
      required,
    },
    sistematika_penyajian: {
      required,
    },
    id_kbk: {
      required,
    },
    dkbs: {
      required,
    },
    transkrip_nilai: {
      required,
    },
    id_pembimbing: {
      required: requiredIf(function () {
        return this.sumber_topik === "dosen";
      }),
    },
    form_persetujuan_instansi: {
      required: requiredIf(function () {
        return this.sumber_topik === "instansi";
      }),
    },
  },
  mounted() {
    this.getAllDosen();
    this.getKbk();
    this.getBatchTopik();
  },
  methods: {
    getTopik() {
      const formData = new FormData();
      formData.append("id_batch", this.id_batch);
      formData.append("id_pengaju", this.id_pengaju);
      this.$store
        .dispatch("topik", formData)
        // .dispatch("topik", { data_cari_topik })
        .then((response) => {
          this.topik = this.$store.getters.topik;
          this.loading_page = false;
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getAllDosen() {
      this.loading_page = true;
      Axios.get(`${config.apiIbatsUrl}/Users/GetFiltered`)
        .then((response) => {
          this.kumpulan_dosen = response.data;
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getKbk() {
      this.loading_page = true;
      Axios.get(`${config.apiUrl}/kbk`)
        .then((response) => {
          this.kumpulan_kbk = response.data.data;
        })
        .catch((response) => {
          console.log(response);
        });
    },
    getBatchTopik() {
      this.loading_page = true;
      const formData = new FormData();
      formData.append("jenis_batch", 1);
      Axios.post(`${config.apiUrl}/batch/pengajuan`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          this.batch = response.data;
          if (!this.batch) {
            if (this.$store.getters.batchTopik) {
              this.id_batch = this.$store.getters.batchTopik.id_batch;
              this.batch = this.$store.getters.batchTopik;
              this.getTopik();
            } else {
              this.$store.dispatch("batchTopik").then((response) => {
                this.id_batch = this.$store.getters.batchTopik.id_batch;
                this.batch = this.$store.getters.batchTopik;
                this.getTopik();
              });
            }
          } else {
            this.id_batch = this.batch.id_batch;
            this.getTopik();
          }
        })
        .catch((response) => {
          console.log(response);
        });
    },
    addFile(fileKey, event) {
      this[fileKey] = event.target.files[0];
    },
    ubahSumberTopik(sumber) {
      this.sumber_topik = sumber;
    },
    checkForm() {
      if (this.sumber_topik === "dosen") {
        this.status_persetujuan_dosen = 2;
        this.status_topik = 3;
      } else if (this.sumber_topik === "instansi") {
        this.status_persetujuan_dosen = 4;
        this.status_topik = 2;
        this.id_pembimbing = "";
      }
    },
    onSubmit() {
      this.loading = true;
      this.$v.$touch();
      if (this.$v.$invalid) {
        this.submit_status = "GAGAL";
        this.loading = false;
      } else {
        this.submit_status = "BERHASIL";
        this.checkForm();
      }
      if (this.submit_status === "BERHASIL") {
        const formData = new FormData();
        formData.append("judul_topik", this.judul_topik);
        formData.append("latar_belakang", this.latar_belakang);
        formData.append("rumusan_masalah", this.rumusan_masalah);
        formData.append("tujuan_pembahasan", this.tujuan_pembahasan);
        formData.append("ruang_lingkup", this.ruang_lingkup);
        formData.append("sumber_data", this.sumber_data);
        formData.append("sistematika_penyajian", this.sistematika_penyajian);
        formData.append("transkrip_nilai", this.transkrip_nilai);
        formData.append("dkbs", this.dkbs);
        formData.append("is_mbkm", this.is_mbkm);
        formData.append(
          "tanggal_pengajuan_topik",
          this.tanggal_pengajuan_topik
        );
        // if (this.sumber_topik === "dosen") {
        formData.append(
          "form_persetujuan_instansi",
          this.form_persetujuan_instansi
        );
        formData.append("lampiran_gambar", this.lampiran_gambar);
        // } else if (this.sumber_topik === "instansi") {
        //   formData.append(
        //     "form_persetujuan_instansi",
        //     this.form_persetujuan_instansi,
        //     // this.form_persetujuan_instansi.name
        //   );
        // }
        formData.append(
          "status_persetujuan_dosen",
          this.status_persetujuan_dosen
        );
        formData.append("status_topik", this.status_topik);
        formData.append("id_batch", this.id_batch);
        formData.append("id_kbk", this.id_kbk);
        formData.append("id_pengaju", this.id_pengaju);
        formData.append("id_pembimbing", this.id_pembimbing);
        formData.append("id_pembimbing_lapangan", this.id_pembimbing);
        Axios.post(`${config.apiMahasiswaUrl}/topik`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
          .then(() => {
            this.loading = false;
            this.$router.push("/topik/histori");
          })
          .catch((response) => {
            this.loading = false;
            console.log(response);
          });
      }
    },
  },
};
</script>

<style scoped>
.keterangan-website {
  margin-left: 0px;
  padding-left: 0px;
}
.form-pengajuan {
  background-color: white;
  border-bottom: 3px solid rgb(202, 206, 147);
}
.form-pengajuan-title {
  background-color: rgb(202, 206, 147);
  /* color: rgb(243, 249, 215); */
  color: rgb(255, 255, 255);
}
.form-pengajuan-item:nth-child(even) {
  background-color: rgb(202, 206, 147);
  color: rgb(255, 255, 255);
}
.space-kosong {
  min-width: 1vw;
}

.baris-input-file,
.kolom-input-file,
.btn-kolom-input-file {
  padding: 0px;
  margin: 0px;
}

.input-form-file-item-button {
  width: 100%;
}

/* // ! CSS Loading Page */
.loading-page {
  margin: auto;
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

/* // * CSS Label Form */
.label-form {
  min-width: 100px;
  max-width: 100px;
}

/* // * CSS Input Text Item */
.input-form-text-item {
  max-width: 100%;
  min-width: 100%;
}

/* // * CSS Input Textarea Item */
.input-form-textarea-item {
  max-width: 100%;
  min-width: 100%;
}

/* // * CSS Input Select Box Item */
.input-form-select-box-item {
  max-width: 100%;
  min-width: 100%;
}

/* // * CSS Input File Item */
input[type="text"].input-form-file {
  background-color: rgb(228, 243, 188);
}
.input-form-file-item-text {
  max-width: 100%;
  min-width: 100%;
  border-radius: 5px 0px 0px 5px;
}

.btn-file-input {
  padding: 0px;
  margin: 0px;
  min-width: 30px;
  max-width: 30px;
}

/* // * CSS Button Radio */
.btn-radio {
  font-size: 15px;
  border-radius: 5px;
  color: rgb(255, 255, 255);
  background-color: rgb(17, 122, 139);
  border: none;
  text-align: center;
}

.btn-radio:focus {
  box-shadow: none;
}

.btn-radio:hover {
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}

.btn-radio-disable {
  font-size: 15px;
  border-radius: 5px;
  color: rgb(0, 0, 0);
  background-color: rgb(233, 236, 239);
  border: none;
  text-align: center;
}
.btn-radio-disable:hover {
  color: rgb(0, 0, 0);
  background-color: rgb(212, 216, 219);
  cursor: pointer;
}

/* // * Submit Button Item */
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

/* // ! CSS Detail Topik */
.detail-topik {
  border-top: 3px solid rgb(202, 206, 147);
  padding: 5px 15px 10px 15px;
  background-color: rgb(255, 255, 255);
}
</style>