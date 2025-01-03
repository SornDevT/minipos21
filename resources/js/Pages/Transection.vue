<template lang="">
    <div class="card">
  <h5 class="card-header">ເຄື່ອນໄຫວ ທຸລະກຳ</h5>
  <div class="card-body">
    <div class="row">
            <div class="col-md-6 mb-2">
                <div class=" d-flex align-items-center">
                    
                    <i class='bx bx-sort-up fs-4 cursor-pointer me-2' v-if="Sort=='asc'" @click="Sort='desc'"></i>
                    <i class='bx bx-sort-down fs-4 cursor-pointer me-2' v-if="Sort=='desc'" @click="Sort='asc'"></i>
                    <div class="d-flex">
                        <select  class="form-select me-2" v-model="PerPage" @change="GetTran(1)">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class=" d-flex justify-content-end">
                    
                  

                    <div class="btn-group" role="group" aria-label="Basic example">
                   
                        <button type="button" class="btn btn-secondary" @click="month_type='m'"> <i class='bx bx-chevron-right' v-if="month_type == 'm'" ></i>  ເດືອນ</button>
                        <button type="button" class="btn btn-secondary" @click="month_type='y'"> <i class='bx bx-chevron-right' v-if="month_type == 'y'"></i>  ປີ </button>
                    </div>

                    <input type="date" class="form-control ms-2 w-auto" v-model="dmy" >


                </div>
            </div>
        </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ວັນທີ</th>
            <th>ເລກທີທຸລະກຳ</th>
            <th>ປະເພດທຸລະກຳ</th>
            <th>ລາຍລະອຽດ</th>
            <th class="text-center">ມູນຄ່າ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in Transection.data" :key="item.id">
            <td>{{ date(item.created_at) }}</td>
            <td>{{ item.tran_id }}</td>
            <td>{{ item.tran_type }}</td>
            <td>{{ item.detail }}</td>
            <td class="text-end">{{ formatPrice(item.price) }}</td>
          </tr>
        </tbody>
      </table>
      <Pagination :pagination="Transection" :offset="4" @paginate='GetTran($event)'  />
    </div>
  </div>
</div>
</template>
<script>
import axios from 'axios';
import { useStore } from '../Store/Auth';
import moment from 'moment'
export default {
    setup() {
        const store = useStore();
        return { store };
    },
    data() {
        return {
            PerPage: 10,
            Sort: 'asc',
            month_type: 'm',
            Transection : [],
            dmy: new Date().toISOString().slice(0,10),
        }
    },
    components:{
        moment
    },
    methods:{
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        date(value){
            return moment(value).format("DD/MM/YYYY");
        },

        GetTran(page){
            axios.get(`api/transection?page=${page}&sort=${this.Sort}&per_page=${this.PerPage}&month_type=${this.month_type}&dmy=${this.dmy}`,{headers: {Authorization: 'Bearer ' + this.store.get_token}}).then((res)=>{
                this.Transection = res.data;
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
    created(){
        this.GetTran(1);
    },
    watch:{
        month_type(){
            this.GetTran(1);
        },
        dmy(){
            this.GetTran(1);
        },
        Sort(){
            this.GetTran(1);
        }
    }
}
</script>
<style lang="">
    
</style>