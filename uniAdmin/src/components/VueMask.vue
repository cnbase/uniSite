<template>
  <div id="mask" @click="closeMask" v-if="visible" :style="{zIndex:z_index,opacity:opacity}">
    <slot name="content"></slot>
  </div>
</template>

<script>
export default {
  name: "VueMask",
  props: {
    z_index: {
      type: Number,
      default: () => {
        return 998
      }
    },
    opacity: {
      type: Number,
      default: () => {
        return 0.3
      }
    }
  },
  data () {
    return {
      visible: false
    }
  },
  methods:{
    showMask() {
      this.visible = true;
    },
    closeMask() {
      this.visible = false;
    }
  }
}
</script>

<style scoped>
#mask{
  width: 100%;
  height: 100vh;
  background-color: black;
  bottom: 0;
  left: 0;
  position: fixed;
}
</style>