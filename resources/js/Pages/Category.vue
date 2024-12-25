<template lang="">
    <div class="row">
        <div class="col-md-4">
            <div class="card h-100">
      <div class="card-header d-flex justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="mb-1 me-2">ໝວດໝູ່ສິນຄ້າ</h5>
        </div>
        <button type="button" @click="AddCat()" class="btn rounded-pill btn-icon btn-primary">
            <i class='bx bx-plus fs-4'></i>
              </button>
      </div>
      <div class="card-body">

        <div class="mt-4">
          <ol class="list-group">
            <li class="list-group-item d-flex justify-content-between " v-for=" item in CatData" :key="item">
                <span>{{ item.name }}</span>
                <div>
                    <i class='bx bx-edit cursor-pointer text-warning' @click="EditCat(item.id)"></i> | <i class='bx bx-trash cursor-pointer text-danger' @click="DelCat(item.id)"></i>
                </div>
            </li>
          </ol>
        </div>

      </div>
    </div>
       </div>
    </div>


    <div class="modal fade" id="FormCat" tabindex="-1" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">ໝວດໝູ່ສິນຄ້າ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col">
                    <label for="nameSmall" class="form-label">ຊື່ໝວດໝູ່:</label>
                    <input type="text" v-model="CatName" id="nameSmall" class="form-control" placeholder="....">
                  </div>
                </div>
             
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary me-2" @click="SaveCat()">ບັນທຶກ</button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ປິດ</button>
              </div>
            </div>
          </div>
        </div>



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
            CatData:[],
            CatName:'',
            FormType:true,
            EditID:'',
        }
    },
    methods:{
        AddCat(){
            this.FormType = true;
            this.CatName = '';
            $('#FormCat').modal('show');
        },
        EditCat(id){
            this.EditID = id;
            // get category by id from backend
            axios.get(`api/category/edit/${id}`,{ headers:{ Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{
                this.CatName = res.data.name;
                this.FormType = false;
                $('#FormCat').modal('show');
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
        SaveCat(){
            // formtype = true => add, formtype = false => edit
            if(this.FormType){
                axios.post('api/category/add',{ name: this.CatName }, { headers:{ Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{
                    if(res.data.success){
                        $('#FormCat').modal('hide');
                        this.GetCat();

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
                // update
                axios.post(`api/category/update/${this.EditID}`,{ name: this.CatName }, { headers:{  Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{

                        if(res.data.success){
                            $('#FormCat').modal('hide');
                            this.GetCat();
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
        DelCat(id){

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

                                        axios.delete(`api/category/delete/${id}`, { headers:{ Authorization: 'Bearer '+this.store.get_token }}).then((res)=>{
                                if(res.data.success){
                                    this.GetCat();
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




        }
    },
    created(){
        this.GetCat();
    }
}
</script>
<style lang="">
    
</style>