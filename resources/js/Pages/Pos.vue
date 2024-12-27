<template lang="">
    <div class="row">
        <div class="col-md-8"> 

            <!-- card input search -->
            <div class="card">
                <div class="card-body d-flex ">
                    <select class="form-select me-2" style="width: 80px;" v-model="category" @change="GetStore(1)" >
                            <option value="all">ທັງໝົດ</option>
                            <option v-for="item in CatData" :key="item.id" :value="item.id">{{ item.name }}</option>
                        </select>
                    <div class="input-group ">
                        <!-- category -->
                        
                        <input type="text" class="form-control " v-model="Search" placeholder="ຄົ້ນຫາ...">
                        <button class="btn btn-primary px-3" @click="GetStore(1)"><i class='bx bx-search'></i></button>
                        </div>
                        
                    </div>
             </div>  
             
             
        
             <PerfectScrollbar>
             <div class="row" style="height: 65vh;">
                <div class="col-md-3 mb-4 mt-4" v-for="item in StoreData.data" :key="item">
                    <div class="card cursor-pointer relative" @click="AddOrder(item.id)">
                        <span  v-for=" i in ListOrder " >
                            <span class="bx-num" v-if="i.id==item.id">{{ i.qty }}</span>
                        </span>
                        <span v-if="item.qty==0" class="no-store">ສິນຄ້າໝົດ</span>
                    <img v-if="item.image" class="card-img-top pos-img" :src="url + '/assets/img/'+ item.image" :alt="item.name">
                    <img v-else class="card-img-top pos-img" :src="url + '/assets/img/no_img.jpg'" :alt="item.name">

                    <div class="card-body p-1 text-center">
                        <h5 class="card-title">{{ item.name }}</h5>
                        <p class="card-text">
                        {{ formatPrice(item.price_sell) }} ກີບ
                        </p>
                        
                    </div>
                    </div>
                </div>
             </div>

            </PerfectScrollbar>
        

        </div>
        <div class="col-md-4"> 
            <div class="card">
                <div class="card-body p-0">
                    <div class="p-3">
                        <label  class="form-label">ຊື່ລູກຄ້າ:</label>
                        <input type="text" v-model="customer_name" class="form-control mb-2"  placeholder="...">
                        <label  class="form-label">ເບີໂທ:</label>
                        <input type="text" v-model="customer_tel" class="form-control"  placeholder="...">
                    </div>
                    <div class=" p-2 bg-info text-white" >ລາຍການສັ່ງຊື້</div>
                        <PerfectScrollbar>
                                <div style=" height:40vh;">
                                    
                                <table class=" table table-bordered">
                                    <tbody>
                                        <tr v-for="item in ListOrder" :key="item">
                                        <td class="p-1 d-flex">
                                            <img v-if="item.image" :src="url + '/assets/img/'+item.image" alt="" class="ls-img-list ">   
                                            <img v-else :src="url + '/assets/img/no_img.jpg'" alt="" class="ls-img-list ">   
                                            <div class=" px-2 d-flex flex-column w-100 ">
                                                <span>{{ item.name }}</span>
                                                <div class="text-end fs-6"> {{ item.qty }} x {{formatPrice(item.price)}} ກີບ</div>
                                                <div class="d-flex justify-content-between">
                                                    <span><i class='bx bxs-plus-circle text-success cursor-pointer' @click="AddOrder(item.id)"></i> | <i @click="RemoveOrder(item.id)" class='bx bxs-minus-circle text-warning cursor-pointer'></i> | <i class='bx bxs-trash-alt text-danger cursor-pointer' @click="DelOrder(item.id)"></i></span>
                                                    <span>{{ formatPrice(item.qty*item.price) }} ກີບ</span>
                                                </div>
                                            </div> 
                                        </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </PerfectScrollbar>
                    </div>
                    <div class="p-2 bg-info text-white  d-flex justify-content-between">
                        <span>ລວມຍອດເງິນ:</span>
                        <span> {{ formatPrice(TotalAllPrice) }} ກີບ</span>
                    </div>
                    <div class="p-2">
                        <button class="btn btn-primary w-100" @click="Pay()" :disabled="!TotalAllPrice">ຊຳລະເງິນ</button>
                    </div>

            </div>    
        </div>

    </div>

    <div class="modal fade" id="dialog_pay" data-bs-backdrop="static" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
              <form class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="backDropModalTitle">ການຊຳລະເງິນ</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                        <div class="col-md-6">
                            <div class=" d-flex justify-content-between">
                                <span>ລວມຍອດເງິນ:</span>
                                <span>{{ formatPrice(TotalAllPrice) }} ກີບ</span>
                            </div>
                            <div class=" d-flex justify-content-between text-danger" v-if="(CashAmount-TotalAllPrice)>0">
                                <span>ເງິນທອນ:</span>
                                <span>{{ formatPrice(CashAmount-TotalAllPrice) }} ກີບ</span>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <label>ຮັບເງິນນຳລູກຄ້າ</label>
                            <cleave :options="options" v-model="CashAmount" class=" form-control text-end" />
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100" @click="AddNum(500)" >500</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(1000)">1,000</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(2000)" >2,000</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(5000)">5,000</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(10000)">10,000</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(20000)" >20,000</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(50000)">50,000</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class=" btn btn-primary w-100"  @click="AddNum(100000)" >100,000</button>
                                </div>
                            </div>
                        </div>
                  </div>
                </div>
                <div class="modal-footer">
                  
                  <button type="button" class="btn btn-primary" :disabled="CheckBTPay" @click="Confirm_Pay()" >ບັນທຶກ</button>
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ປິດ</button>
                </div>
              </form>
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
            customer_name:'',
            customer_tel:'',
            url: window.location.origin,
            category:'all',
            CatData:[],
            ListOrder:[],
            Sort:"desc",
            PerPage:30,
            Search:'',
            StoreData:[],
            CashAmount:0,
            options: {
                  numeral: true,
                  numeralPositiveOnly: true,
                  noImmediatePrefix: true,
                  rawValueTrimPrefix: true,
                  numeralIntegerScale: 10,
                  numeralDecimalScale: 2,
                  numeralDecimalMark: '.',
                  delimiter: ',',
            }
        }
    },
    computed:{

        CheckBTPay(){
                if(this.CashAmount>=this.TotalAllPrice){
                    return false 
                } else {
                    return true
                }
            }, CheckBTPay(){
                if(this.CashAmount>=this.TotalAllPrice){
                    return false 
                } else {
                    return true
                }
            },
            

        TotalAllPrice(){
            let total = 0;
            this.ListOrder.forEach((item)=>{
                total += item.qty*item.price;
            });
            return total;
        }
    },
    methods: {
        async openLink(link){
            const response = await fetch(`${link}`,{ headers:{ Authorization: 'Bearer '+ this.store.get_token}});
            const html = await response.text();
            const blob = new Blob([html],{ type: "text/html"});
            const blobUrl = URL.createObjectURL(blob);
            window.open(blobUrl, "_blank");
        },

        Confirm_Pay(){

                axios.post(`api/transection/add`,{
                customer_name: this.customer_name,
                customer_tel: this.customer_tel,
                listorder: this.ListOrder,
                }, { headers :{ Authorization: 'Bearer '+this.store.get_token } }).then((res)=>{

                if(res.data.success){
                    // ເຄຼີຍຂໍ້ມູນ
                    this.ListOrder = [];
                    this.CashAmount = 0;
                    this.customer_name = '';
                    this.customer_tel = '';
                    $('#dialog_pay').modal('hide');

                    this.$swal({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: res.data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // ເປີດແທບ 
                        if(res.data.bill_id){

                            // window.open(window.location.origin+"/api/bills/print/"+res.data.bill_id, "_blank");
                            this.openLink(window.location.origin+"/api/bills/print/"+res.data.bill_id);

                        }

                    this.GetStore(1);

                    } else {
                        this.$swal({
                            title:"ບໍ່ສາມາດສັ່ງຊື້ໄດ້!",
                            text:"ການສັ່ງຊື້ບໍ່ສຳເລັດ!",
                            showConfirmButton: false,
                            icon:"error",
                            timer:3500
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
        AddNum(num){
            this.CashAmount = parseInt(this.CashAmount?this.CashAmount:0) + parseInt(num);
        },
        Pay(){
            $('#dialog_pay').modal('show');
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        AddOrder(id){

            let item = this.StoreData.data.find((i)=>i.id==id);
            let old_item = this.ListOrder.find((i)=>i.id==id);

            if(item.qty>0){
                if(old_item){

                        if(item.qty-old_item.qty>0){
                            old_item.qty += 1;
                        } else {
                            this.$swal({
                                        title:"ບໍ່ສາມາດຂາຍສິນຄ້າໄດ້!",
                                        text:"ສິນຄ້າດັ່ງກ່າວໄດ້ໝົດແລ້ວ!",
                                        showConfirmButton: false,
                                        icon:"error",
                                        timer:3500
                                    });
                        }

                    }else{

                        this.ListOrder.push({
                            id:item.id,
                            image:item.image,
                            name:item.name,
                            price:item.price_sell,
                            qty:1
                        });
                    }
            } else {
                this.$swal({
                                        title:"ບໍ່ສາມາດຂາຍສິນຄ້າໄດ້!",
                                        text:"ສິນຄ້າດັ່ງກ່າວໄດ້ໝົດແລ້ວ!",
                                        showConfirmButton: false,
                                        icon:"error",
                                        timer:3500
                                    });
            }
                    

        },
        RemoveOrder(id){
            let index = this.ListOrder.findIndex((i)=>i.id==id);
            if(this.ListOrder[index].qty>1){
                this.ListOrder[index].qty -= 1;
            }else{
                this.ListOrder.splice(index,1);
            }
        },
        DelOrder(id){
            let index = this.ListOrder.findIndex((i)=>i.id==id);
            this.ListOrder.splice(index,1);
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
    },
    created() {
        this.GetStore(1);
        this.GetCat();
    },
    watch: {
        Search(){
            if(this.Search.length==''){
                this.GetStore(1);
            }
        },
    }
}
</script>
<style  scoped >
    .ls-img-list{
        border-radius: 5px;
    width: 80px;
    height: 80px;
    border: 1px solid #cacaca;
    padding: 2px;
    object-fit: cover;
    object-position: center;
    }
    .pos-img{
        height: 120px;
    width: auto;
    object-fit: cover;
    object-position: center;
    border-bottom: 1px solid #ababab;
    }
    .bx-num{
        position: absolute;
    background: #3ae53a;
    color: white;
    right: 0px;
    top: 8px;
    padding: 2px 8px 2px 8px;
    border-radius: 10px 0px 0px 10px;
    }
    .no-store{
        position: absolute;
    top: 90px;
    right: 0px;
    background: #f71010a3;
    color: white;
    padding: 2px;
    border-radius: 5px 0px 0px 5px;
    font-size: 12px;
    }
</style>