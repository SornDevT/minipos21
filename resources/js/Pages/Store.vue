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
            <div class="col-md-3 d-flex justify-content-center" style="position:relative;">
                <button type="button" v-if="FormStore.image" @click="RemoveImg()" class="btn rounded-pill btn-icon btn-danger img-x">
                    <i class='bx bx-x fs-3'></i>
               </button>
                <img :src="Image_preview" @click="$refs.img_store.click()" class="img-store cursor-pointer "  >
                <input type="file" ref="img_store" style=" display:none; " @change="onSelect($event)" >
            </div>
            <div class="col-md-9">
                <div class="mb-2">
                    <label  class="form-label fs-6">ຊື່ສິນຄ້າ:</label>
                    <input type="text" class="form-control" v-model="FormStore.name"  placeholder="..." >
                </div>
                <div class=" row ">
                    <div class="col-md-6 mb-2">
                        <label  class="form-label fs-6">ຈຳນວນ:</label>
                        <cleave :options="options" class="form-control" v-model="FormStore.qty"  placeholder="..." />
                    </div>
                    <div class="col-md-6 mb-2">
                        <label  class="form-label fs-6">ໝວດໝູ່ສິນຄ້າ:</label>
                        <select class="form-select" v-model="FormStore.category_id" >
                            <option v-for="item in CatData" :key="item.id" :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label  class="form-label fs-6">ລາຄາຊື້:</label>
                        <div class="input-group">
                            <cleave :options="options" class="form-control text-end" v-model="FormStore.price_buy" placeholder="..." />
                            <span class="input-group-text" >ກີບ</span>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <label  class="form-label fs-6">ລາຄາຂາຍ:</label>
                        <div class="input-group">
                            <cleave :options="options" class="form-control text-end" v-model="FormStore.price_sell" placeholder="..." />
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
                    
                    <i class='bx bx-sort-up fs-4 cursor-pointer me-2' v-if="Sort=='asc'" @click="Sort='desc'"></i>
                    <i class='bx bx-sort-down fs-4 cursor-pointer me-2' v-if="Sort=='desc'" @click="Sort='asc'"></i>
                    <div class="d-flex">
                        <select  class="form-select me-2" v-model="PerPage" @change="GetStore(1)">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                        </select>
                        <select  class="form-select" v-model="category"  @change="GetStore(1)">
                            <option value="all">ທັງໝົດ</option>
                            <option v-for="item in CatData" :key="item.id" :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class=" d-flex justify-content-end">
                    <div class="d-flex">
                            <div class="input-group">
                            <input type="text" class="form-control" placeholder="ຄົ້ນຫາ..." @keyup.enter="GetStore(1)" v-model="Search" >
                            <button class="btn btn-primary px-2 me-2" type="button" @click="GetStore(1)" ><i class='bx bx-search'></i></button>
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
                <th class="text-center" >ID</th>
                <th>ຮູບພາບ</th>
                <th>ຊື່ສິນຄ້າ</th>
                <th>ໝວດໝູ່</th>
                <th text-center>ຈຳນວນ</th>
                <th>ລາຄາຊື້</th>
                <th>ຈັດການ</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in StoreData.data" :key="item">
                <td class="text-center">{{ item.id }}</td>
                <td>
                    <img v-if="item.image" :src="url + '/assets/img/'+ item.image "  class="list-img border-1 p-1 rounded shadow-sm">
                    <img v-else :src="url + '/assets/img/no_img.jpg' " class="list-img border-1 p-1 rounded shadow-sm">
                
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.category_name }}</td>
                <td class="text-center">{{ formatPrice(item.qty) }}</td>
                <td class="text-end">{{ formatPrice(item.price_buy) }}</td>
                <td>
                <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);" @click="EditStore(item.id)"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                    <a class="dropdown-item" href="javascript:void(0);" @click="DelStore(item.id)" ><i class="bx bx-trash me-1"></i> ລຶບ</a>
                    </div>
                </div>
                </td>
            </tr>
            </tbody>
        </table>

        <button class="btn btn-primary" @click="showAlert">Hello world</button>


        <Pagination :pagination="StoreData" :offset="4" @paginate='GetStore($event)'  />

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
            category:'all',
            CatData:[],
            url: window.location.origin,
            Image_preview: window.location.origin + '/assets/img/upload_img.jpg',
            ShowForm:false,
            FormType:true,
            FormStore:{
                name:'',
                category_id:'',
                image:'',
                qty:'',
                price_buy:'',
                price_sell:''
            },
            StoreData:[],
            Sort:"desc",
            PerPage:5,
            Search:'',
            EditID:'',
            options: {
                  numeral: true,
                  numeralPositiveOnly: true,
                  noImmediatePrefix: true,
                  rawValueTrimPrefix: true,
                  numeralIntegerScale: 10,
                  numeralDecimalScale: 2,
                  numeralDecimalMark: '.',
                  delimiter: ','
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
        RemoveImg(){
            this.FormStore.image = '';
            this.Image_preview = window.location.origin + '/assets/img/upload_img.jpg';
        },
        showAlert() {
            // Use sweetalert2
            // sussecc
            // this.$swal({
            //     toast: true,
            //     position: "top-end",
            //     icon: "success",
            //     title: "Your work has been saved",
            //     showConfirmButton: false,
            //     timer: 1500
            //     });

                // error
                // this.$swal({
                //     icon: "error",
                //     title: "ຜິດຜາດ!",
                //     text: "That thing is still around?",
                //     showConfirmButton: false,
                //     timer: 3500
                // });
                this.$swal({
                        title: "ທ່ານແນ່ໃຊບໍ່?",
                        text: "ທີ່ຂະລຶຍຂໍ້ມູນນູນນີ້!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "ຕົກລົງ!",
                        cancelButtonText: "ຍົກເລີກ!",
                        }).then((result) => {
                        if (result.isConfirmed) {
                            this.$swal({
                            title: "ການລຶບຂໍ້ມູນ!",
                            text: "res.data.message",
                            icon: "success"
                            });
                        }
                        })
            },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        onSelect(event){
            // console.log(event);
                this.FormStore.image = event.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(this.FormStore.image);
                reader.addEventListener("load", function(){
                    this.Image_preview = reader.result
                }.bind(this,false));
        },
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
        EditStore(id){

            // ເກັບ id ໄວ້ອັບເດດ 
            this.EditID = id;
            // ປ່ຽນສະຖານະ ຟອມເປັນການອັບເດດ
            this.FormType = false;

            
            
            axios.get(`api/store/edit/${id}`,{ headers:{ Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{

                this.FormStore = res.data;

                if(this.FormStore.image){
                    this.Image_preview = this.url + '/assets/img/'+ this.FormStore.image;
                } else {
                    this.Image_preview = this.url + '/assets/img/upload_img.jpg';
                }

                // ເປັີດຟອມ
                this.ShowForm = true;

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
            })
        },
        DelStore(id){

            this.$swal({
                        title: "ທ່ານແນ່ໃຊບໍ່?",
                        text: "ທີ່ຂະລຶຍຂໍ້ມູນນູນນີ້!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "ຕົກລົງ!",
                        cancelButtonText: "ຍົກເລີກ!",
                        }).then((result) => {
                            if (result.isConfirmed) {
                            axios.delete(`api/store/delete/${id}`,{ headers:{ Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{

                                if(res.data.success){

                       

                                        this.$swal({
                                                title: "ການລຶບຂໍ້ມູນ!",
                                                text: res.data.message,
                                                icon: "success"
                                        });


                                                // get store
                                            this.GetStore();


                                        } else {

                                                this.$swal({
                                                icon: "error",
                                                title: "ຜິດຜາດ!",
                                                text: res.data.message,
                                                showConfirmButton: false,
                                                timer: 3500
                                                });
                                    }
                        
                            });
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

                    

        },
        SaveStore(){
            if(this.FormType){
                // ເພີ່ມຂໍ້ມູນໃໝ່
                axios.post('api/store/add',this.FormStore, { headers:{ "Content-Type":"multipart/form-data", Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{
                    if(res.data.success){
                        this.ShowForm = false;
                        this.GetStore();

                        this.$swal({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: res.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                    } else {

                        this.$swal({
                            icon: "error",
                            title: "ຜິດຜາດ!",
                            text: res.data.message,
                            showConfirmButton: false,
                            timer: 3500
                        });

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
                // update store
                axios.post(`api/store/update/${this.EditID}`,this.FormStore, { headers:{ "Content-Type":"multipart/form-data", Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{

                    if(res.data.success){
                        this.ShowForm = false;
                        this.GetStore();
                        this.$swal({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: res.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                    } else {
                        
                        this.$swal({
                            icon: "error",
                            title: "ຜິດຜາດ!",
                            text: res.data.message,
                            showConfirmButton: false,
                            timer: 3500
                        });
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
                })
            }
        },
        GetStore(page){
            // get store data by axios
            axios.get(`api/store?page=${page}&sort=${this.Sort}&perpage=${this.PerPage}&search=${this.Search}&category=${this.category}`,{ headers:{ Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{
                // console.log(res.data);
                this.StoreData = res.data;
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

        },
        GetCat(){
            axios.get('api/category',{ headers:{ Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{
                this.CatData = res.data;
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
    },
    created(){
        this.GetStore(1);
        this.GetCat();
    },
    watch:{
        Sort(){
            this.GetStore(1);
        },
        Search(){
            if(this.Search==''){
                this.GetStore(1);
            }
        }
    }
}
</script>
<style scoped>
    .img-store{
        width: 80%;
        height: auto;
        object-fit: contain;
    }
    .img-x{
        position: absolute;
        top: 0px;
        right: 5px;
    }
    .list-img{
        width: 80px;
        height: 80px;
        object-fit: cover;
        object-position: center;
    }
</style>