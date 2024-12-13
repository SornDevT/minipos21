<template lang="">
    <div class="row">
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
        </a>
      </div>
      <!-- /Logo -->
      <p class="mb-6">ກະລຸນາລົງທະບຽນ</p>

      <div class="mb-6"> 
          <label for="email" class="form-label">ຊື່ຜູ້ໃຊ້:</label>
          <input type="text" class="form-control" id="email" v-model="user_name" name="email-username" placeholder="ປ້ອນຊື່" autofocus="">
        </div>
        <div class="mb-6">
          <label for="email" class="form-label">ອີເມວລ໌:</label>
          <input type="text" class="form-control" v-model="user_email" id="email" name="email-username" placeholder="ປ້ອນອີເມວລ໌" autofocus="">
        </div>
        <div class="mb-6 form-password-toggle">
          <label class="form-label" for="password">ລະຫັດຜ່ານ:</label>
          <div class="input-group input-group-merge">
            <input type="password" id="password" class="form-control" v-model="password" name="password" placeholder="············" aria-describedby="password">
            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
          </div>
        </div>
        <div class="mb-6 form-password-toggle">
          <label class="form-label" for="password">ຍືນຍັນລະຫັດຜ່ານ:</label>
          <div class="input-group input-group-merge">
            <input type="password" id="password" class="form-control" v-model="password2" name="password" placeholder="············" aria-describedby="password">
            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
          </div>
        </div>
        <div class="alert alert-warning" role="alert" v-if="text_error" >
          {{text_error}}
        </div>
        <div class="mb-6">
          <button class="btn btn-primary d-grid w-100" @click="Register()" type="submit">ລົງທະບຽນ</button>
        </div>
   

      <p class="text-center">
        <span>ມີບັນຊີແລ້ວ</span>
        <router-link to="/login">
          <span> ເຂົ້າສູ່ລະບົບ </span>
        </router-link>
      </p>
    </div>
  </div>
  <!-- /Register -->
</div>
</div>
</div>
    </div>
</template>
<script>

import axios from 'axios';

export default {
    data() {
        return {
            user_name:'',
            user_email:'',
            password:'',
            password2:'',
            text_error:''
        }
    },
    methods:{
        Register(){
            if( this.user_name == '' || this.user_email =='' || this.password == '' ){
                this.text_error = 'ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ!';
            } else {
                if(this.password != this.password2){
                   this.text_error = 'ລະຫັດຜ່ານບໍ່ກົງກັນ!';
                } else {
                    this.text_error = '';

                    axios.post('api/register',{
                        form_user_name: this.user_name,
                        form_user_email: this.user_email,
                        form_password: this.password
                    }).then((res)=>{

                        if(res.data.success){
                            this.text_error = '';

                             // ໄປໜ້າ login ພ້ອມກັບ ສົ່ງ email ໄປພ້ອມ
                             this.$router.push({ path:'/login', query: { email: this.user_email } });

                             
                            // ເຄີຍຂໍ້ມູນໃນຟອມ
                            this.user_name = '';
                            this.user_email = '';
                            this.password = '';
                            this.password2 = '';

                           
                            

                        } else {
                            this.text_error = res.data.message;
                        }

                        console.log(res)
                    }).catch((err)=>{
                        console.log(err)
                    })

                }
                
            }

      

        }
    }
}
</script>
<style lang="">
    
</style>