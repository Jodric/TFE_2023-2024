<template>
  <transition name="fade">
    <div v-if="isVisible">
      <div >
        <slot></slot>
      </div>
    </div>
  </transition>
</template>

  
  <script>
  export default {
    name: 'Popupmodal',
    data: () => ({
      isVisible: false,
    }),
    methods: {
      open() {
        this.isVisible = true;
      },
      close() {
        this.isVisible = false;
      },
    },
  }
  </script>
  
  <style scoped>


  </style>
  