<template>
  <div>
    <b-form-group
      :id="'input-group-textarea-' + id"
      :label="label"
      :label-for="'input-' + id"
      :description="description"
    >
      <b-form-textarea
        :id="'input-textarea-' + id"
        :value="model"
        @input="input"
        :placeholder="placeholder"
        :rows="rows"
        :max-rows="maxRows"
      ></b-form-textarea>
    </b-form-group>
  </div>
</template>
<script>
export default {
  props: [
    "value",
    "id",
    "label",
    "placeholder",
    "description",
    "rows",
    "maxRows",
  ],
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