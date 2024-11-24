<template>
  <div>
    <b-form-group
      :id="'input-group-' + id"
      :label="label"
      :label-for="'input-' + id"
      :description="description"
    >
      <b-form-input
        :id="'input-' + id"
        type="email"
        :value="model"
        @input="input"
        :placeholder="placeholder"
      ></b-form-input>
    </b-form-group>
  </div>
</template>
<script>
export default {
  props: ["value", "id", "label", "placeholder", "description"],
  data() {
    return {
      model: this.value,
    };
  },
  methods: {
    input(val) {
      this.model = val;
      this.$emit("input", val);
    },
  },
};
</script>