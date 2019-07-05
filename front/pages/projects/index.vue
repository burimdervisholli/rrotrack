<template>
    <div class="col-sm-9 col-lg-10 content">
        <span class="color" :style="'background-image: url('+ user.cover +');'"></span>
        <Header :user="user" title="Projects"/>
        <div class="box-table">
            <div class="box-header">
                <h2 class="box-title">Projects</h2>
            </div>
            <div class="box-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 65px;">#ID</th>
                            <th>Title</th>
                            <th>Created at</th>
                            <th>Deadline</th>
                            <th>Team members</th>
                            <th>Sprints</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="project in projects" :key="project.id">
                            <td><nuxt-link :to="'/projects/'+project.id">{{ project.id }}</nuxt-link></td>
                            <td><nuxt-link :to="'/projects/'+project.id">{{ project.title }}</nuxt-link></td>
                            <td><nuxt-link :to="'/projects/'+project.id">{{ project.created_at }}</nuxt-link></td>
                            <td><nuxt-link :to="'/projects/'+project.id">{{ project.deadline }}</nuxt-link></td>
                            <td><nuxt-link :to="'/projects/'+project.id">{{ project.users }}</nuxt-link></td>
                            <td><nuxt-link :to="'/projects/'+project.id">{{ project.sprints }}</nuxt-link></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import Header from '@/components/Header.vue'
import { mapGetters } from 'vuex'

export default {
    async asyncData({ $axios }) {
        const data = await $axios.$get('/projects')
        return { 
            projects: data.projects
        }
    },
    components: {
        Header
    },
    computed: {
        ...mapGetters(['user'])
    }
}
</script>

<style scoped>
.box-table{
    background-color: #fff;
    border-radius: 4px;
    -webkit-box-shadow: 0px 0 15px 0px rgba(0,0,0,0.15);
    -moz-box-shadow: 0px 0 15px 0px rgba(0,0,0,0.15);
    box-shadow: 0px 0 15px 0px rgba(0,0,0,0.15);
}
.box-table .box-header{
    padding: 20px 15px 10px;
    border-bottom: 1px solid #dee2e6;
}
.box-table .box-body{
}
.box-header .box-title{
    font-size: .9rem;
    font-weight: 600;
    color: #191d4d;
}
.table th, .table td{
    padding: 1rem;
    font-size: .8rem;
}
.table td{
    padding: 0;
}
.table td a{
    display: block;
    padding: 1rem;
    font-weight: 600;
    color: #191d4d;
}
.table td a:hover{
    text-decoration: none;
}
.table tbody tr:hover{
    background-color: #f8f8f8;
}
.table thead{
    background-color: #f6f9fc;
}
.table th{
    border: none;
    font-weight: 700;
    color: #9fadbb;
}
</style>
