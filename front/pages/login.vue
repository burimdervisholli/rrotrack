<template>
    <div class="page-wrapper d-flex align-items-center">
        <b-container class="bv-example-row">
            <h1 class="title">rrotrack</h1>
            <b-row class="justify-content-center">
                <div class="col-sm-10 col-md-4">
                    <b-card
                        :header="cardTitle"
                        header-tag="header"
                        class="text-center"
                    >
                        <form @submit.prevent="login">
                            <div class="form-group">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <input type="email" class="form-control" v-model="email" placeholder="Enter email" />
                            </div>
                            <div class="form-group">
                                <i class="fa fa-key" aria-hidden="true"></i>
                                <input type="password" class="form-control" v-model="password" placeholder="Enter password" />
                            </div>
                            <div class="form-group text-left">
                                <label class="remember-me d-flex align-items-center">
                                    <input type="checkbox" name="remember" value="1"/>
                                    remember me
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </b-card>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
            </b-row>
        </b-container>
    </div>
</template>

<script>
export default {
    layout: 'login',
    middleware: ['auth'],
    data() {
        return {
            cardTitle: 'Login',
            email: '',
            password: '',
        }
    },
    methods: {
        async login() {
            if(this.email != '' && this.password != ''){
                try {
                    this.$toast.show('Logging in...', { position: 'bottom-center' })
                    await this.$auth.loginWith('local', {
                        data: {
                            email: this.email,
                            password: this.password
                        }
                    })
                    this.$toast.clear()
                    this.$router.push('/')
                } catch (e) {
                    this.$toast.clear()
                    this.$toast.show(e.response.data.message, { type: 'error', position: 'bottom-center', duration: 2000 })
                }
            } else {
                this.$toast.show('Email and password are required', { type: 'error', position: 'bottom-center', duration: 2000 })
            }
        }
    }
}
</script>

<style scoped>
.page-wrapper{
    position: relative;
    min-height: 100vh;
    background: rgb(95,114,228);
    background: -moz-linear-gradient(-45deg,  rgba(95,114,228,1) 0%, rgba(130,95,228,1) 100%);
    background: -webkit-linear-gradient(-45deg,  rgba(95,114,228,1) 0%,rgba(130,95,228,1) 100%);
    background: linear-gradient(135deg,  rgba(95,114,228,1) 0%,rgba(130,95,228,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#5f72e4', endColorstr='#825fe4',GradientType=1 );
}
.title{
    font-weight: 700;
    font-size: 2rem;
    color: #fff;
    text-align: center;
}
.card{
    border: 0;
    background-color: #f9f9f9;
    color: #8898aa;
    -webkit-box-shadow: 0px 4px 12px -4px rgba(0,0,0,0.25);
    -moz-box-shadow: 0px 4px 12px -4px rgba(0,0,0,0.25);
    box-shadow: 0px 4px 12px -4px rgba(0,0,0,0.25);
}
.card-header{
    background-color: #f9f9f9;
    border-color: #eaedef;
    font-size: .9rem;
}
.card-body{
    padding-top: 35px;
    padding-bottom: 35px;
}
.form-group{
    position: relative;
    margin-bottom: 25px;
}
.form-group .fa{
    position: absolute;
    top: 10px;
    left: 10px;
}
.form-control{
    padding-left: 35px;
    border: none;
    border-radius: 4px;
    background-color: #fff;
    font-weight: 500;
    font-size: .9rem;
    color: #8898aa;
    -webkit-box-shadow: 0px 4px 12px -4px rgba(0,0,0,0.15);
    -moz-box-shadow: 0px 4px 12px -4px rgba(0,0,0,0.15);
    box-shadow: 0px 4px 12px -4px rgba(0,0,0,0.15);
}
.remember-me{
    margin-top: 25px;
    font-size: .8rem;
}
.remember-me input{
    margin-right: 5px;
}
.btn-primary{
    background-color: #5e72e4;
    font-weight: 700;
    font-size: .8rem;
    color: #fff;
    -webkit-box-shadow: 0px 4px 12px -4px rgba(0,0,0,0.15);
    -moz-box-shadow: 0px 4px 12px -4px rgba(0,0,0,0.15);
    box-shadow: 0px 4px 12px -4px rgba(0,0,0,0.15);
}
.btn-primary:hover, .btn-primary:active, .btn-primary:focus{
    background-color: #5e72e4;
}
.forgot-password{
    font-size: .8rem;
    font-weight: 600;
    color: #fff;
}
</style>
