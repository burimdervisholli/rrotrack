<template>
  <div class="page-wrapper container-fluid">
    <div class="row container-row">
      <div class="col-sm-3 col-lg-2 aside">
        <Aside/>
      </div>
      <nuxt />      
    </div>
  </div>
</template>

<script>
import Aside from '@/components/Aside.vue'

export default {
  data() {
    return {
      
    }
  },
  components: {
    Aside,
  }
}
</script>

<style>
body{
  font-family: 'Quicksand', sans-serif;
  font-weight: 500;
}
.container-row{
  height: 100vh;
}
.aside{
  background-color: #fff;
  -webkit-box-shadow: 2px 0px 10px -3px rgba(0,0,0,0.15);
  -moz-box-shadow: 2px 0px 10px -3px rgba(0,0,0,0.15);
  box-shadow: 2px 0px 10px -3px rgba(0,0,0,0.15);
}
.content{
  padding-left: 30px;
  padding-right: 30px;
}
.content .color{
  position: absolute;
  top: 0;
  right: 0;
  left: 0;
  width: 100%;
  z-index: -1;
}
.alert{
  font-size: .9rem;
}
</style>
