<template>
    <aside>
        <h1 class="title">rrotrack</h1>
        <div class="navigation">
            <nuxt-link to="/">
                <i class="fa fa-television" aria-hidden="true"></i>
                <span>Dashboard</span>
            </nuxt-link>
            <nuxt-link to="/projects">
                <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                <span>Projects</span>
            </nuxt-link>
            <nuxt-link :to="'/users/'+user.id">
                <i class="fa fa-user-circle" aria-hidden="true"></i>
                <span>Profile</span>
            </nuxt-link>
            <nuxt-link to="/settings">
                <i class="fa fa-cog" aria-hidden="true"></i>
                <span>Settings</span>
            </nuxt-link>
            <a href="#" @click.prevent="logout">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data () {
        return {

        }
    },
    computed: {
        ...mapGetters([
            'authenticated',
            'user'
        ])
    },
    methods: {
        async logout() {
            this.$axios.$get('/logout')
            await this.$auth.logout()
            this.$router.push('/login')
        },
    }
}
</script>

<style scoped>
aside{
    padding: 25px 10px;
}
.title{
    font-size: 1.5rem;
    text-align: center;
}
.navigation{
    margin-top: 30px;
}
.navigation a{
    position: relative;
    display: block;
    margin-bottom: 15px;
    padding: 7px 7px 7px 35px;
    border-radius: 4px;
    color: #a4a4a4;
    transition: all .2s ease;
}
.navigation .fa{
    position: absolute;
    top: 50%;
    left: 7px;
    margin-top: -8px;
    transition: all .2s ease;
}
.navigation a span{
    display: block;
    transition: all .2s ease;
}
.navigation a:hover{
    text-decoration: none;
    -webkit-box-shadow: 0px 2px 5px 1px rgba(0,0,0,0.1);
    -moz-box-shadow: 0px 2px 5px 1px rgba(0,0,0,0.1);
    box-shadow: 0px 2px 5px 1px rgba(0,0,0,0.1);
}
.navigation a:hover .fa{
    transform: translateX(5px);
}
.navigation a:hover span{
    transform: translateX(10px);
    transition-delay: .0.5s;
}
.navigation .nuxt-link-exact-active.nuxt-link-active{
    background: rgb(95,114,228);
    background: -moz-linear-gradient(-45deg,  rgba(95,114,228,1) 0%, rgba(130,95,228,1) 100%);
    background: -webkit-linear-gradient(-45deg,  rgba(95,114,228,1) 0%,rgba(130,95,228,1) 100%);
    background: linear-gradient(135deg,  rgba(95,114,228,1) 0%,rgba(130,95,228,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#5f72e4', endColorstr='#825fe4',GradientType=1 );
    color: #fff;
    -webkit-box-shadow: 0px 2px 5px -5px rgba(0,0,0,0.45);
    -moz-box-shadow: 0px 2px 5px -5px rgba(0,0,0,0.45);
    box-shadow: 0px 2px 5px -5px rgba(0,0,0,0.45);
}
</style>
