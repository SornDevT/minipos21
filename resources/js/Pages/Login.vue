<template lang="">

    <div class="row justify-content-center">

    <div class="authentication-wrapper authentication-basic container-p-y col-md-4">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="index.html" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">

</span>
              <span class="app-brand-text demo text-heading fw-bold">sneat</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">ສະຍານດີ ຕ້ອງຮັບ! 👋</h4>
          <p class="mb-6">ກະລຸນາເຂົ້າສູ້ລະບົບ</p>

            <div class="mb-6">
              <label for="email" class="form-label">ອີເມວລ໌:</label>
              <input type="text" v-model="email" class="form-control" id="email" name="email-username" placeholder="ປ້ອນອີເມວລ໌" >
            </div>
            <div class="mb-6 form-password-toggle"> 
              <label class="form-label" for="password">ລະຫັດຜ່ານ:</label>
              <div class="input-group input-group-merge">
                <input type="password" v-model="password" id="password" class="form-control" name="password" placeholder="············" >
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-8">
              <div class="d-flex justify-content-between mt-8">
                <div class="form-check mb-0 ms-2"> 
                  <input class="form-check-input" type="checkbox" v-model="remember_me" id="remember-me">
                  <label class="form-check-label" for="remember-me">
                    ຈື່ງຂ້ອຍໄວ້ໃນລະບົບ
                  </label>
                </div>
                <!-- <a href="auth-forgot-password-basic.html">
                  <span>Forgot Password?</span>
                </a> -->
              </div>
            </div>
            <div class="alert alert-danger" v-if="check_email_text || check_password_text || text_error " role="alert">
              {{ text_error }} {{ check_email_text }} {{ check_password_text }}
          </div>

            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" :disabled="CheckBt" type="button" @click="Login()">ເຂົ້າສູ້ລະບົບ</button>
            </div>
       

          <p class="text-center">
            <span>ບໍ່ມີບັນຊີ ?</span>
            <router-link to="/register">
              <span> ລົງທະບຽນ </span>
            </router-link>
          </p>
        </div>
      </div>
      <!-- /Register -->
    </div>
  </div>
</div>
    
</template>
<script>

import axios from 'axios';

export default {
    data() {
        return {
            email:'',
            password:'',
            remember_me: false,
            check_email_text:'',
            check_password_text:'',
            text_error:'',
        }
    },
    computed:{
      CheckBt(){

        const EmailRehex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if(this.email){

          if(EmailRehex.test(this.email)){
            this.check_email_text = '';
          } else {
            this.check_email_text = 'ອີເມວບໍ່ຖຶກຕ້ອງ!';
          }
          
        }
        else {
          this.check_email_text = '';
        }

        //        check passeod   ------

        if(this.password){
              if(this.password.length <=5 ){
                this.check_password_text = 'ລະຫັດຜ່ານຕ້ອງຫລາຍກ່ວາ 4 ຕົວອັກສອນ'
              } else {
                this.check_password_text = '';
              }
        } else {
          this.check_password_text = '';
        }

        // console.log(this.password.length) ture = false

        if(!EmailRehex.test(this.email) || this.password.length <=5){
          return true;
        } else {
          return false;
        }


      }
    },
    mounted() {
        this.email = this.$route.query.email;
    },
    methods:{
      Login(){

        axios.post('api/login',{
          login_email: this.email,
          login_password: this.password,
          login_remember_me: this.remember_me
        }).then((res)=>{
          console.log(res);
          if(res.data.success){
            this.text_error = '';

            // ເກັບຂໍ້ມູນລົງໃນ localstorage
            localStorage.setItem('web_token', res.data.token);
            localStorage.setItem('web_user',JSON.stringify(res.data.user_data));

            // ເຄຍຟອມ
             this.email = '';
             this.password = '';

             // go to root path
             this.$router.push('/');


          } else {
            this.text_error = res.data.message;
          }

        }).catch((error)=>{
          console.log(error);

          if(error.response){
            if(error.response.status!=200){
              this.text_error = error.response.data.message;
            }
          }

        })
      }
    }
}
</script>
<style lang="">
    
</style>