<template lang="">
    
    <div class="card">
  <h5 class="card-header">ລາຍການ ສະຕ໋ອກສິນຄ້າ</h5>
  <div class="card-body">

    <div v-if="ShowForm">
        {{ FormStore }}
        <div class="text-end">
            <button type="button" @click="SaveStore()" :disabled="CheckForm" class="btn rounded-pill btn-success me-2">ບັນທຶກຂໍ້ມູນ</button>
            <button type="button" @click="CancelStore()" class="btn rounded-pill btn-danger">ຍົກເລີກ</button>
        </div>
        <div class="row">
            <div class="col-md-3">Image</div>
            <div class="col-md-9">
                <div class="mb-2">
                    <label  class="form-label fs-6">ຊື່ສິນຄ້າ:</label>
                    <input type="text" class="form-control" v-model="FormStore.name"  placeholder="..." >
                </div>
                <div class="mb-2">
                    <label  class="form-label fs-6">ຈຳນວນ:</label>
                    <input type="text" class="form-control" v-model="FormStore.qty"  placeholder="..." >
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label  class="form-label fs-6">ລາຄາຊື້:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="FormStore.price_buy" placeholder="..." >
                            <span class="input-group-text" >ກີບ</span>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <label  class="form-label fs-6">ລາຄາຂາຍ:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="FormStore.price_sell" placeholder="..." >
                            <span class="input-group-text" >ກີບ</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- <hr class="m-4"> -->

    <div v-else >
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class=" d-flex align-items-center">
                    
                    <i class='bx bx-sort-up fs-4 cursor-pointer me-2'></i>
                    <i class='bx bx-sort-down fs-4 cursor-pointer me-2'></i>
                    <div>
                        <select  class="form-select">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class=" d-flex justify-content-end">
                    <div class="d-flex">
                            <div class="input-group">
                            <input type="text" class="form-control" placeholder="ຄົ້ນຫາ..." aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-primary px-2 me-2" type="button" id="button-addon2"><i class='bx bx-search'></i></button>
                            </div>
                    </div>
                    <button type="button" @click="AddStore()" class="btn rounded-pill btn-info">ເພີ່ມໃໝ່</button>
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>ຮູບພາບ</th>
                <th>ຊື່ສິນຄ້າ</th>
                <th>ຈຳນວນ</th>
                <th>ລາຄາຊື້</th>
                <th>ຈັດການ</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in 20" :key="item">
                <td>{{ item }}</td>
                <td>image</td>
                <td>product name</td>
                <td>120</td>
                <td>30,000 k</td>
                <td>
                <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                    </div>
                </div>
                </td>
            </tr>

            
            
            </tbody>
        </table>
        </div>
    </div>

  </div>
</div>

    <!-- <button @click="testjwt()">Test JWT</button> -->
</template>
<script>
import axios from 'axios';
import { useStore } from '../Store/Auth';
export default {
    setup(){
        const store = useStore();
        return { store }
    },
    data() {
        return {
            ShowForm:false,
            FormType:true,
            FormStore:{
                name:'',
                image:'',
                qty:'',
                price_buy:'',
                price_sell:''
            }
        }
    },
    computed:{
        CheckForm(){
            if(this.FormStore.name == '' || this.FormStore.qty == '' || this.FormStore.price_buy == '' || this.FormStore.price_sell == ''){
                return true;
            } else {
                return false;
            }
        }
    },
    methods:{
        AddStore(){
            this.ShowForm = true
            this.FormType = true

            // ເຄຼຍຟອມ
            this.FormStore.name = '';
            this.FormStore.image = '';
            this.FormStore.qty = '';
            this.FormStore.price_buy = '';
            this.FormStore.price_sell = '';
        },
        CancelStore(){
            this.ShowForm = false
        },
        SaveStore(){
            if(this.FormType){
                // ເພີ່ມຂໍ້ມູນໃໝ່
                axios.post('api/store/add',this.FormStore, { headers:{ Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{
                    if(res.data.success){
                        this.ShowForm = false;
                    } else {

                    }
                }).catch((error)=>{

                    if(error.response){
                        if(error.response.status==401){
                            // ເຄຼຍຂໍ້ມູນໃນ localstorage
                            localStorage.removeItem('web_token');
                            localStorage.removeItem('web_user');

                            // ເຄຼຍຂໍ້ມູນໃນ pinia
                            this.store.remove_token();
                            this.store.remove_user();

                            // go to login page
                            this.$router.push('/login');
                        }
                    }
                });

            } else {
                // ອັບເດດຂໍ້ມູນ

            }
        },
        testjwt(){
            axios.post('api/testjwt',{},{ headers:{ Authorization: 'Bearer '+this.store.get_token } }).then((res)=>{

            }).catch((error)=>{
                // console.log(error)
                if(error.response){
                    if(error.response.status!=200){
                        // console.log(error.response.status);
                        // ກໍລະນີ token ໝົດອາຍຸ ຫຼື ນຳໃຊ້ບໍ່ໄດ້
                        if(error.response.status==401){
                            // ເຄຼຍຂໍ້ມູນໃນ localstorage
                            localStorage.removeItem('web_token');
                            localStorage.removeItem('web_user');

                            // ເຄຼຍຂໍ້ມູນໃນ pinia
                            this.store.remove_token();
                            this.store.remove_user();

                            // go to login page
                            this.$router.push('/login');
                        }
                    }
                }
            })
        }
    }
}
</script>
<style lang="">
    
</style>