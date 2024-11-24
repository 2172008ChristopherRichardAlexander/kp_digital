<template>
  <!--
    Everything is wrapped in a label, which acts as a clickable wrapper around a form element.
    In this case, the file input.
  -->
  <label class="file-select">
    <!-- We can't use a normal button element here, as it would become the target of the label. -->
    <div class="select-button">
      <!-- Display the filename if a file has been selected. -->
      <span>Browse</span>
    </div>
    <!-- Now, the file input that we hide. -->
    <input type="file" @change="handleFileChange"/>
  </label>
</template>

<script>
export default {
  props: {
    value: File
  },

  methods: {
    handleFileChange(e) {
      this.$emit("input", e.target.files[0]);
    }
  }
};
</script>

<style scoped>
.file-select > .select-button {
  padding: 4px 20px;
  font-size: 15px;
  color: white;
  border-radius: 0px 5px 5px 0px;
  background-color:rgb(17,122,139);

  text-align: center;
}

.file-select > .select-button:hover {
  font-size: 15px;
  color: rgb(255, 255, 255);
  background-color:rgb(20,145,163);
  cursor: pointer;
}

.file-select > input[type="file"] {
  display: none;
}
</style>