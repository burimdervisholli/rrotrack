<template>
    <div class="col-sm-9 col-lg-10 content">
        <span class="color" v-show="showCoverPreview" :style="'background-image: url('+ coverPreview +');'"></span>
        <span class="color" v-show="!showCoverPreview" :style="'background-image: url('+ user.cover +');'"></span>
        <Header :user="user" title="User profile"/>
        <div class="d-flex justify-content-end" :class="{ 'justify-content-between' : showCoverPreview }">
            <button type="button" v-show="showCoverPreview" class="save-cover-image" @click="submitCover" title="Save cover image">Save</button>
            <button type="button" class="cover-image-upload" title="Change profile picture">
                Change cover image
                <input type="file" class="hidden-file-upload" @change="previewCover"/>
            </button>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-3">
                <div class="box user-info-box">
                    <div class="preview-uploaded-avatar" :style="'background-image: url('+ imagePreview +');'" v-show="showPreview"></div>
                    <div class="img-container" v-show="!showPreview" :style="'background-image: url('+ user.avatar +');'"></div>
                    <h3 class="f-bold f-lg">{{ user.name }}</h3>
                    <h3 class="f-bold f-sm">{{ user.email }}, {{ user.role[0] }}</h3>
                    <hr/>
                    <p class="f-light f-sm">{{ user.bio }}</p>
                    <button type="button" class="file-upload" title="Change profile picture">
                        Change
                        <input type="file" class="hidden-file-upload" @change="previewAvatar"/>
                    </button>
                    <button type="button" v-show="showPreview" @click="submitAvatar" class="save-profile-picture" title="Save profile picture">Save</button>
                </div>
            </div>
            <div class="col-md-6 col-lg-9" v-if="user.id == $route.params.id">
                <div class="box user-edit-box text-left">
                    <h3 class="box-title">Edit profile</h3>
                    <hr/>
                    <h5 class="sub-title">User information</h5>
                    <form @submit.prevent="editProfile">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" v-model="formUser.name" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" v-model="formUser.email" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label>Bio</label>
                            <textarea rows="5" name="bio" v-model="formUser.bio" class="form-control"></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="c-submit-btn">Update</button>
                        </div>
                    </form>
                    <hr/>
                    <h5 class="sub-title">Password</h5>
                    <form @submit.prevent="changePassword">
                        <div class="form-group">
                            <label>Old password</label>
                            <input type="password" v-model="oldPassword" class="form-control" placeholder="old password" />
                        </div>
                        <div class="form-group">
                            <label>New password</label>
                            <input type="password" v-model="newPassword" class="form-control" placeholder="new password" />
                        </div>
                        <div class="text-center">
                            <button type="submit" class="c-submit-btn">Change password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>        
    </div>
</template>

<script>
import Header from '@/components/Header.vue'
import { mapGetters } from 'vuex'

export default {
    middleware: ['auth'],
    data() {
        return {
            oldPassword: '',
            newPassword: '',
            errors: [],
            uploadedAvatar: '',
            showPreview: false,
            imagePreview: '',
            uploadedCover: '',
            showCoverPreview: false,
            coverPreview: ''
        }
    },
    async asyncData({ $axios }) {
        const data = await $axios.$get('/me')
        return { 
            formUser: data,
        }
    },
    validate ({ params }) {
        return /^\d+$/.test(params.id)
    },
    computed: {
        ...mapGetters(['user'])
    },
    components: {
        Header
    },
    methods: {
        async editProfile (){
            if(this.formUser.name != '' && this.formUser.email != ''){
                const formData = {
                    'name': this.formUser.name,
                    'email': this.formUser.email,
                    'bio': this.formUser.bio,
                }
                await this.$axios.$post('/user-update/'+this.user.id, formData)
                    .then(response => {
                        if(response.success){
                            this.$auth.fetchUser()
                            this.$toast.show(response.message, { type: 'success', position: 'bottom-center', duration: 2000 })
                        }
                    })
                    .catch(err => {
                        this.$toast.show(err.response.data.errors, { type: 'error', position: 'bottom-center', duration: 2000 })
                    })        
            } else {
                this.$toast.show('Name and email are required!', { type: 'error', position: 'bottom-center', duration: 2000 })
            }
        },
        async changePassword (){
            if(this.oldPassword != '' && this.newPassword != ''){
                const formData = {
                    'old_password': this.oldPassword,
                    'new_password': this.newPassword,
                }
                await this.$axios.$post('/user-update-password/'+this.user.id, formData)
                    .then(response => {
                        this.$toast.show(response.message, { type: 'success', position: 'bottom-center', duration: 2000 })
                    })
                    .catch(err => {
                        this.$toast.show(err.response.data.errors, { type: 'error', position: 'bottom-center', duration: 2000 })
                    })
            } else {
                this.$toast.show('All password fields are required!', { type: 'error', position: 'bottom-center', duration: 2000 })
            }
        },
        previewAvatar (event){
            this.uploadedAvatar = event.target.files[0];
            let reader = new FileReader();

            reader.addEventListener("load", function () {
                this.showPreview = true
                this.imagePreview = reader.result
            }.bind(this), false)

            reader.readAsDataURL(this.uploadedAvatar)
        },        
        async submitAvatar (){
            let formData = new FormData()
            formData.append('avatar', this.uploadedAvatar)

            await this.$axios.$post('/user-update-avatar/'+this.user.id, 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
            .then(response => {
                if(response.success){
                    this.$store.dispatch('setUserAvatar', response.avatar)
                    this.showPreview = false
                    this.imagePreview = ''
                    this.$toast.show(response.message, { type: 'success', position: 'bottom-center', duration: 2000 })
                }
            })
            .catch(err => {
                this.$toast.show(err.response.data.errors, { type: 'error', position: 'bottom-center', duration: 2000 })
            })
        },
        previewCover (event){
            this.uploadedCover = event.target.files[0];
            let reader = new FileReader();

            reader.addEventListener("load", function () {
                this.showCoverPreview = true
                this.coverPreview = reader.result
            }.bind(this), false)

            reader.readAsDataURL(this.uploadedCover)
        },
        async submitCover (){
            let formData = new FormData()
            formData.append('cover', this.uploadedCover)

            await this.$axios.$post('/user-update-cover/'+this.user.id, 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
            .then(response => {
                this.$store.dispatch('setUserCover', response.cover)
                if(response.success){
                    this.showCoverPreview = false
                    this.coverPreview = ''
                    this.$toast.show(response.message, { type: 'success', position: 'bottom-center', duration: 2000 })
                }
            })
            .catch(err => {
                this.$toast.show(err.response.data.errors, { type: 'error', position: 'bottom-center', duration: 2000 })
            })
        },
    }
}
</script>

<style scoped>
.cover-image-upload,
.save-cover-image{
    position: relative;
    margin-bottom: 25px;
    border: none;
    border-radius: 3px;
    background-color: #2dce89;
    font-size: .9rem;
    font-weight: 700;
    color: #fff;
    -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    transition: all .2s ease;
}
.cover-image-upload:hover,
.save-cover-image:hover{
    background-color: #29b97b;
    -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
    -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
}
.box{
    margin-bottom: 35px;
    padding: 25px;
    border-radius: 4px;
    border: 1px solid #eae8e8;
    background-color: #f8faff;
    text-align: center;
}
.box .img-container,
.box .preview-uploaded-avatar{
    width: 150px;
    height: 150px;
    margin: -65px auto 25px;
    border-radius: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    overflow: hidden;
}
.user-info-box{
    position: relative;
}
.user-info-box .file-upload{
    position: absolute;
    top: 5px;
    right: 5px;
    border: none;
    border-radius: 3px;
    background-color: #2dce89;
    font-size: .9rem;
    font-weight: 700;
    color: #fff;
    -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    transition: all .2s ease;
}
.user-info-box .file-upload:hover{
    background-color: #29b97b;
    -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
    -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
}
.hidden-file-upload{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
}
.save-profile-picture{
    position: absolute;
    top: 5px;
    left: 5px;
    padding: 6px 8px;
    border: none;
    border-radius: 4px;
    background-color: #2dce89;
    font-size: .9rem;
    font-weight: 700;
    color: #fff;
    -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    transition: all .2s ease;
}
.save-profile-picture:hover{
    background-color: #29b97b;
    -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
    -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
}
.f-bold{
    font-weight: 600;
    color: #191d4d;
}
.f-bold .fa{
    font-size: 2.6rem;
}
.f-lg{
    font-size: 1rem;
}
.f-md{
    font-size: .9rem;
}
.f-sm{
    font-size: .8rem;
}
.f-light{
    font-size: .8rem;
    color: #a4a4a4;
}
.box-title{
    font-size: 1rem;
    color: #191d4d;
}
.sub-title{
    font-size: .8rem;
    font-weight: 700;
    color: #a4a4a4;
}
.form-group label{
    font-size: .8rem;
    font-weight: 700;
}
.form-control{
    border: none;
    background-color: #fff;
    font-weight: 500;
    font-size: .9rem;
    color: #8898aa;
    -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
}
.c-submit-btn{
    margin-top: 25px;
    padding: 6px 8px;
    border: none;
    border-radius: 4px;
    background-color: #2dce89;
    font-size: .9rem;
    font-weight: 700;
    color: #fff;
    -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.15);
    transition: all .2s ease;
}
.c-submit-btn:hover{
    background-color: #29b97b;
    -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
    -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
    box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.35);
}
</style>
