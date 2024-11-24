<template>
  <wysiwyg v-model="content" />
</template>
<script>
export default {
  data() {
    return {
      content: "",
      loading: false
    };
  },
  watch: {
    content: {
      handler: function() {
        this.$emit("newdata", [this.content]);
      },
      deep: true
    }
  }
};
</script>
<style scoped>
</style>