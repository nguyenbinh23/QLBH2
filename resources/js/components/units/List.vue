<template>
    <div>
        <div class="text-center">
           <h4><i class="fa fa-balance-scale" aria-hidden="true"></i> Danh sách đơn vị</h4>
       </div>
        <router-link  to="/admin/units/new" class="btn btn-outline-success btn-sm mb-2"><i class="fas fa-plus"></i> Thêm mới đơn vị</router-link>
        <button class="btn btn-outline-warning btn-sm mb-2" @click="refreshUnit()"><i class="fa fa-refresh"></i> Làm mới danh sách</button>
       <div class="input-group mb-3">
            <input @keydown.enter="findUnit()" type="text" class="form-control" placeholder="Nhập tên đơn vị cần tìm..." v-model="name_find">
            <div class="input-group-append">
                <button class="btn btn-success button-find" title="Tìm kiếm" @click="findUnit()"><i class="fa fa-search"></i></button>
            </div>
        </div>
        <template v-if="!units.length">
            <div class="row justify-content-center">
                <h3>Không có đơn vị</h3>
            </div>
        </template>

        <template v-else>
            <div class="row justify-content-center">
                <div class="col-6 col-md-4">
                    <table class="table table-bordered table-responsive text-white text-center">
                        <thead>
                            <th scope="col" width="10%">#</th>
                            <th scope="col" width="30%">Tên đơn vị</th>
                            <th scope="col" width="30%">Mã đơn vị</th>
                            <th scope="col" width="15%">Sửa</th>
                            <th scope="col" width="15%">Xóa</th>
                        </thead>
                        <tbody>
                            <tr v-for="(unit,index) in units" :key="unit.id">
                                <th>{{parseInt(index += 1)}}</th>
                                <td>{{unit.name}}</td>
                                <td>{{unit.code}}</td>
                                <td><router-link :to="{name: 'unit-edit' , params: {id: unit.id}}" class="btn btn-warning">Sửa</router-link></td>
                                <td><button class="btn btn-danger" @click="removeUnit(index,unit.id)">Xóa</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
        <nav aria-label="...">
            <ul class="pagination mt-2 justify-content-center">
                <li v-show="firstpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2"  @click="findUnit(firstpageURL)"> First </a>
                </li>
                <li v-show="current_page" class="page-item">
                    <a  class="btn btn-outline-primary ml-2">{{current_page}}</a>
                </li>
                <li v-show="nextpageURL" class="page-item">
                    <a  class="btn btn-outline-success ml-2" @click="findUnit(nextpageURL)"> Next </a>
                </li>
                <li v-show="prevpageURL" class="page-item">
                    <a  class="btn btn-outline-warning ml-2" @click="findUnit(prevpageURL)"> Back </a>
                </li>
                <li v-show="lastURL" class="page-item">
                    <a  class="btn btn-outline-danger ml-2" @click="findUnit(lastURL)"> Last </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: 'list',
    data(){
        return {
            units: [],
            next_page_url: null,
            prev_page_url: null,
            last_page_url: null,
            first_page_url: null,
            current_page: 1,
            name_find: null,
            unitsFind: []
        }
    },
    computed: {
        currentUser(){
            return this.$store.getters.currentUser
        },
        firstpageURL(){
            return this.first_page_url
        },
        nextpageURL(){
            return this.next_page_url
        },
        prevpageURL(){
            return this.prev_page_url
        },
        lastURL(){
            return this.last_page_url
        }
    },
    created(){
        this.findUnit()
    },
    methods: {
        fetchUnits(page_url){
            page_url = page_url || 'http://localhost:8000/api/units'
            axios.get(page_url,{
                headers: {
                    'Authorization': 'Bearer '+this.currentUser.token,
                }
            }).then((res) => {
                this.units = res.data.data
                this.next_page_url = res.data.next_page_url
                this.prev_page_url = res.data.prev_page_url
                this.last_page_url = res.data.last_page_url
                this.first_page_url = res.data.first_page_url
                this.current_page = res.data.current_page
            }).catch((err) => {
                console.log(err)
            })
        },
        removeUnit(index,id){
            if(confirm('Bạn có muốn xóa đơn vị này')){
                this.units.splice(index,1)
                axios.post('/api/units/remove',{
                    id: id
                },
                {
                    headers: {
                        "Authorization": 'Bearer '+this.currentUser.token
                    }
                })
                    .then((res) => {
                        alert('Xóa thành công')
                    })
                    .catch((error) => {
                        alert('Xóa thất bại')
                    })
            }
        },

        findUnit(){
            axios.post('/api/units/find',
                {
                    name: this.name_find,
                    code: this.name_find,
                },
                {
                    headers: {
                        "Authorization": 'Bearer '+this.currentUser.token
                    }
                })
                    .then((res) => {
                        this.units = res.data.data
                        this.next_page_url = res.data.next_page_url
                        this.prev_page_url = res.data.prev_page_url
                        this.last_page_url = res.data.last_page_url
                        this.first_page_url = res.data.first_page_url
                        this.current_page = res.data.current_page
                    })
                    .catch((error) => {
                        console.log(error)
                    })
        },
        refreshUnit(){
            this.name_find = ''
            this.findUnit()
        }
    }
}
</script>

<style scoped>
.button-find{
    width: 150px;
}
</style>
