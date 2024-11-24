<template>
  <div>
    <div class="image-preview" v-if="status_gambar == 'true'">
      <img class="preview gambar" :src="link" alt="gagal" />
    </div>
    <div class="image-preview" v-else>
      <div v-if="imageData.length > 0">
        <img class="preview gambar" id="blah" :src="imageData" />
      </div>
      <div v-else>
        <b-img
          blank
          blank-color="#abc"
          id="blah"
          width="200"
          height="200"
          alt="placeholder"
          class="gambar"
        ></b-img>
      </div>
    </div>
    <div class="gambar-caption">
      <label class="file-select text-center">
        <div class="select-button">
          <!-- Display the filename if a file has been selected. -->
          <span>{{title}}</span>
        </div>
        <input type="file" @change="previewImage" />
      </label>
      <span>{{sub_title}}</span>
    </div>
    <!-- <div class="file-upload-form">Upload an image file:</div> -->
  </div>
</template>
<script>
export default {
  // props: {
  //   value: File,
  //   title: String,
  //   sub_title: String,
  //   status: String,
  //   link: String
  // },
  props: ["value", "title", "sub_title", "status", "link"],
  data() {
    return {
      imageData: "", // we will store base64 format of image in this string
      status_gambar: ""
    };
  },
  mounted() {
    this.status_gambar = this.status;
  },
  methods: {
    previewImage(event) {
      this.status_gambar = "false";
      // let files = e.target.files || e.dataTransfer.files;
      // if (!files.length) return;
      // this.createImage(files[0]);
      // Reference to the DOM input element
      var input = event.target;
      // Ensure that you have a file before attempting to read it
      if (input.files && input.files[0]) {
        // create a new FileReader to read this image and convert to base64 format
        var reader = new FileReader();
        // Define a callback function to run, when FileReader finishes its job
        reader.onload = e => {
          // Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
          // Read image as base64 and set to imageData
          this.imageData = e.target.result;
        };
        // reader.onload = function(e) {
        //   $("#blah").attr("src", e.target.result);
        // };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input.files[0]);
      }
      this.$emit("input", event.target.files[0]);
    }
    // createImage(file) {
    //   let reader = new FileReader();
    //   reader.onload = e => {
    //     this.person.image = e.target.result;
    //   };
    //   reader.readAsDataURL(file);
    //   this.$emit("input", event.target.files[0]);
    // }
  }
};
</script>
<style scoped>
/* .file-upload-form,
.image-preview {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  padding: 20px;
} */

/* img.preview {
  width: 200px;
  background-color: white;
  border: 1px solid #ddd;
  padding: 5px;
} */
/* // * Gambar */
.gambar {
  /* width: 100%;
  height: 100%; */
  display: block;
  max-width: 250px;
  max-height: 250px;
  /* width: 200px; */
  margin: auto;
  height: auto;
}
.gambar:hover {
  cursor: pointer;
}
.gambar-caption {
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
  background: rgba(65, 72, 2, 0.5);
  color: rgb(255, 255, 255);
  text-align: center;
  font-size: 15px;
  padding: 5px;
}
.sub-caption {
  font-size: 5px;
}

.file-select > .select-button {
  padding: 4px 20px;
  font-size: 15px;
  color: white;
  /* border-radius: 0px 5px 5px 0px; */
  background-color: rgb(17, 122, 139);

  text-align: center;
}

.file-select > .select-button:hover {
  font-size: 15px;
  color: rgb(255, 255, 255);
  background-color: rgb(20, 145, 163);
  cursor: pointer;
}
.file-select > input[type="file"] {
  display: none;
}
</style>