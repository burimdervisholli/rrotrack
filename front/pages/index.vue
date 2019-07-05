<template>
  <div class="col-sm-9 col-lg-10 content">
    <span class="color" :style="'background-image: url('+ user.cover +');'"></span>
    <Header :user="user" title="Dashboard"/>
    <section class="statistics">
      <div class="row">
        <div class="col-sm-3">
          <nuxt-link to="/members" class="item">
            <h5 class="title">Members</h5>
            <span class="nr">{{ users }}</span>
            <i class="fa fa-users" aria-hidden="true"></i>
          </nuxt-link>
        </div>
        <div class="col-sm-3">
          <nuxt-link to="/projects" class="item">
            <h5 class="title">Projects</h5>
            <span class="nr">{{ projects }}</span>
            <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
          </nuxt-link>
        </div>
        <div class="col-sm-3">
          <nuxt-link to="/issues" class="item">
            <h5 class="title">Issues</h5>
            <span class="nr">{{ issues }}</span>
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
          </nuxt-link>
        </div>
        <div class="col-sm-3">
          <nuxt-link to="/tasks" class="item">
            <h5 class="title">Ongoing tasks</h5>
            <span class="nr">{{ tasks }}</span>
            <i class="fa fa-area-chart" aria-hidden="true"></i>
          </nuxt-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import Header from '@/components/Header.vue'
import { mapGetters } from 'vuex';

export default {
  middleware: ['auth'],
  async asyncData({ $axios }) {
    const data = await $axios.$get('/home-statistics')
    return { 
      users: data.users,
      projects: data.projects,
      issues: data.issues,
      tasks: data.tasks
    }
  },
  components: {
    Header
  },
  computed: {
    ...mapGetters([
      'user'
    ])
  },
  methods: {

  },
  mounted() {
    
  }
}
</script>

<style scoped>
.dashboard-header{
  margin-bottom: 55px;
}
.statistics{
  margin-top: 40px;
}
.item{
  display: block;
  position: relative;
  width: 100%;
  height: 115px;
  padding: 20px;
  border-radius: 4px;
  background-color: #fff;
  transition: all .2s ease;
  -webkit-box-shadow: 0px 0px 10px -5px rgba(0,0,0,0.45);
  -moz-box-shadow: 0px 0px 10px -5px rgba(0,0,0,0.45);
  box-shadow: 0px 0px 10px -5px rgba(0,0,0,0.45);
}
.item:hover{
  background-color: #f9f9f9;
  text-decoration: none;
  -webkit-box-shadow: 0px 0px 10px -5px rgba(0,0,0,0.9);
  -moz-box-shadow: 0px 0px 10px -5px rgba(0,0,0,0.9);
  box-shadow: 0px 0px 10px -5px rgba(0,0,0,0.9);
}
.item:hover .nr{  
  transform: translateX(10px);
}
.item .title{
  font-size: .8rem;
  color: #a1adbc;
}
.item .nr{
  display: block;
  font-size: 2.4rem;
  color: #18284d;
  transition: all .2s ease;
}
.item .fa{
  position: absolute;
  top: 15px;
  right: 15px;
  font-size: 3rem;
}
.row .col-sm-3:first-of-type .item .fa{
  color: #f5365c;
}
.row .col-sm-3:nth-child(2) .item .fa{
  color: #ffd600;
}
.row .col-sm-3:nth-child(3) .item .fa{
  color: #fb6340;
}
.row .col-sm-3:last-of-type .item .fa{
  color: #11cdef;
}
</style>
