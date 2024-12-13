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
              <input type="text" v-model="email" class="form-control" id="email" name="email-username" placeholder="ປ້ອນອີເມວລ໌" autofocus="">
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">ລະຫັດຜ່ານ:</label>
              <div class="input-group input-group-merge">
                <input type="password" v-model="password" id="password" class="form-control" name="password" placeholder="············" aria-describedby="password">
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-8">
              <div class="d-flex justify-content-between mt-8">
                <div class="form-check mb-0 ms-2">
                  <input class="form-check-input" type="checkbox" id="remember-me">
                  <label class="form-check-label" for="remember-me">
                    ຈື່ງຂ້ອຍໄວ້ໃນລະບົບ
                  </label>
                </div>
                <!-- <a href="auth-forgot-password-basic.html">
                  <span>Forgot Password?</span>
                </a> -->
              </div>
            </div>
            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">ເຂົ້າສູ້ລະບົບ</button>
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

export default {
    data() {
        return {
            email:'',
            password:''
        }
    },
    mounted() {
        this.email = this.$route.query.email;
    },
}
</script>
<style lang="">
    
</style>