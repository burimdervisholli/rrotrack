<template>
    <div class="col-sm-9 col-lg-10 content">
        <span class="color" :style="'background-image: url('+ user.cover +');'"></span>
        <Header :user="user" title="Project"/>
    </div>
</template>

<script>
import Header from '@/components/Header.vue'
import { mapGetters } from 'vuex';
export default {
    data() {
        return {

        }
    },
    validate ({ params }) {
        return /^\d+$/.test(params.id)
    },
    async asyncData({ $axios, params }) {
        const data = await $axios.$get('/project/'+params.id)
        return { 
            project: data.project,
        }
    },
    computed: {
        ...mapGetters(['user'])
    },
    components: {
        Header
    }
}
</script>

<style scoped>

</style>
