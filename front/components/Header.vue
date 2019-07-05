<template>
    <header class="d-flex align-items-center justify-content-between dashboard-header">
        <h4 class="title">{{ title }}</h4>
        <div class="right-part d-flex align-items-center">
            <form @submit.prevent="search">
                <div class="form-wrapper">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <input type="text" class="form-control" placeholder="search for project.."/>
                </div>
            </form>
            <nuxt-link :to="'/users/'+user.id" class="user-info">
                <img src="/images/user.svg" class="avatar"/> <span class="username">{{ user.name }}</span>
            </nuxt-link>
        </div>
    </header>
</template>

<script>
export default {
    props: ['user', 'title'],
    methods: {
        async search (){

        }
    }
}
</script>

<style scoped>
.dashboard-header{
  height: 80px;
}
.dashboard-header .title{
  font-size: 1.2rem;
  font-weight: 700;
  color: #fff;
}
.form-wrapper{
    position: relative;
    width: 270px;
    height: 45px;    
    border: 2px solid #fff;
    border-radius: 35px;
}
.form-wrapper .fa{
    position: absolute;
    top: 50%;
    left: 10px;
    margin-top: -8px;
    color: #fff;
}
.form-control{
    height: 100%;
    padding-left: 30px;
    background-color: transparent;
    border: none;
    font-size: .9rem;
    font-weight: 700;
    color: #fff;
}
.form-control::-webkit-input-placeholder {
  color: #fff;
}
.form-control:-ms-input-placeholder {
  color: #fff;
}
.form-control::placeholder {
  color: #fff;
}
.user-info{
    margin-left: 25px;
}
.user-info:hover{
    text-decoration: none;
}
.avatar{
    max-width: 25px;
    max-height: 25px;
    margin-right: 5px;
}
.username{
    color: #fff;
    font-size: 1rem;
}
</style>
