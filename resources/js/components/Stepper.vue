<template>
  <div class="step" :class="{ 'step-active': isActive }">
    <div>
      <div class="circle">{{ icon }}</div>
      <div class="line"></div>
    </div>
    <div>
      <div class="title">{{ detail.judul }}</div>
      <div class="body">
        <div class="step-jarak">{{ detail.isi }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isActive: false,
    };
  },
  mounted() {
    this.checkActivity();
  },
  methods: {
    checkActivity() {
      if (this.detail.id_step == this.status) {
        this.isActive = true;
      }
    },
  },
  props: {
    detail: {
      type: Object,
      default: "",
    },
    status: {
      default: -3,
    },
    icon: {
      default: null,
    },
  },
};
</script>

<style scoped>
/* // * Step */
*,
*:before,
*:after {
  box-sizing: border-box;
}
.step {
  position: relative;
  min-height: 32px /* circle-size */;
}
.step > div:first-child {
  position: static;
  height: 0;
}
.step > div:last-child {
  margin-left: 32px;
  padding-left: 16px;
}
.step-jarak {
  padding: 5px 0px 10px 0px;
}

/* // * Circle */
/* .step.step-active {
  color: #4285f4;
} */

.step.step-active .circle {
  background-color: rgb(166, 215, 38);
}

.circle {
  background: rgb(224, 230, 210);
  width: 32px;
  height: 32px;
  line-height: 32px;
  border-radius: 16px;
  position: relative;
  color: white;
  text-align: center;
}
/* // * Line */
.line {
  position: absolute;
  border-left: 1px solid gainsboro;
  left: 16px;
  bottom: 10px;
  top: 42px;
}
.step:last-child .line {
  display: none;
}
/* // * Title */
.title {
  line-height: 32px;
  font-weight: bold;
}
/* // * Body */
.body {
  text-align: justify;
}
</style>