<template>
    <div>
        <div class="container mt-5">
        
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        Teams
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Team name" v-model="team">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="button"  @click="addTeam()">Add Team</button>
                            </div>
                            
                            </div>
                        <table class="table">
                            <thead>
                                <th scope="col" class="col-10">Name</th>
                                <th scope="col" class="col-2">Options</th>
                            </thead>
                            <tbody>
                                <tr v-for='(team, index) in teams' :key="team.id">
                                    <td>{{team.name}}</td>
                                    <td>
                                        <button type="button" @click="deleteTeam(index)" class="btn btn-danger"> <i class="fa-solid fa-trash fa-sm"></i> </button>
                                    </td>
                                </tr>                              
                            </tbody>
                            </table>
                            
                    </div>
                    </div>
            </div>
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        Users
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Players name" v-model="mortal">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="button"  @click="addMortal()">Add Player</button>
                            </div>
                            
                            </div>
                        <table class="table">
                            <thead>
                                <th scope="col" class="col-10">Ime</th>
                                <th scope="col" class="col-2">Options</th>
                            </thead>
                            <tbody>
                                <tr v-for='(mortal, index) in mortals' :key="mortal.id">
                                    <td>{{mortal.name}}</td>
                                    <td>
                                        <button type="button" @click="deleteMortal(index)" class="btn btn-danger"> <i class="fa-solid fa-trash fa-sm"></i> </button>
                                    </td>
                                </tr>                              
                            </tbody>
                            </table>
                            
                    </div>
                    </div>
            </div>
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        Pairs
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <select type="text" class="form-control" style="width:30%" v-model='mortal_id'>
                                <option value="">Select User</option>
                                <option v-for='(mortal, index) in mortals' :key="mortal.id" :value="mortal.id">
                                    {{mortal.name}}
                                </option>
                            </select>
                            <select type="text" class="form-control" style="width:30%" v-model='team_id'>
                                <option value="">Select Team</option>
                                <option v-for='(team, index) in teams' :key="team.id" :value="team.id">
                                    {{team.name}}
                                </option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-success" type="button"  @click="addPair()">Add Pair</button>
                            </div>
                            
                            </div>
                        <table class="table">
                            <thead>
                                <th scope="col" class="col-3">Name</th>
                                <th scope="col" class="col-7">Team</th>
                                <th scope="col" class="col-2">Options</th>
                            </thead>
                            <tbody>
                                <tr v-for='(pair, index) in pairs' :key="pair.id">
                                    <td>{{pair.mortal.name}}</td>
                                    <td>{{pair.team.name}}</td>
                                    <td>
                                        <button type="button" @click="deletePair(index)" class="btn btn-danger"> <i class="fa-solid fa-trash fa-sm"></i> </button>
                                    </td>
                                </tr>                              
                            </tbody>
                        </table>
                            
                    </div>
                    </div>
            </div>
            
        </div>
    </div>

</template>

<script>
import axios from 'axios'
export default {
  middleware: 'auth',
  data(){
        return {    
            teams:{},
            mortals:{},
            pairs:{},
            team:'',
            mortal:'',
            mortal_id:'',
            team_id:'',
            mortal:'',
            team:''
        }
    },
    mounted(){
        this.getTeams()
        this.getMortals()
        this.getPairs()
    },
    methods: {
        getTeams(){
            let self = this
            axios.post('/api/get-teams',{
            }).then(function (response) {
                self.teams = response.data
            }).catch(function (error) {
                console.log(error);
            });
        },
        getMortals(){
            let self = this
            axios.post('/api/get-mortals',{
            }).then(function (response) {
                self.mortals = response.data
            }).catch(function (error) {
                console.log(error);
            });
        },
        getPairs(){
            let self = this
            axios.post('/api/get-pairs',{
            }).then(function (response) {
                self.pairs = response.data
            }).catch(function (error) {
                console.log(error);
            });
        },
        deleteTeam(index){
           
            let self = this
            axios.post('/api/delete-team',{
                id:self.teams[index].id,            
            }).then(function (response) {
                self.getTeams()
            }).catch(function (error) {
                console.log(error);
            });
     
        },
        deleteMortal(index){
            let self = this
            axios.post('/api/delete-mortal',{
                id:self.mortals[index].id,            
            }).then(function (response) {
                self.getMortals()
                self.getPairs()
            }).catch(function (error) {
                console.log(error);
            });
        },
        deletePair(index){
            let self = this
            axios.post('/api/delete-pair',{
                id:self.pairs[index].id,            
            }).then(function (response) {
                self.getPairs()
            }).catch(function (error) {
                console.log(error);
            });
        },
        addPair(){
            let self = this
            axios.post('/api/post-pair',{
                team_id:self.team_id,
                mortal_id:self.mortal_id,               
            }).then(function (response) {
                self.mortal_id = ''
                self.team_id = ''
                self.getPairs();
            }).catch(function (error) {
                console.log(error);
            });
        },
        addMortal(){
            let self = this
            axios.post('/api/post-mortal',{
                mortal:self.mortal,              
            }).then(function (response) {
                self.mortal = ''
                self.getMortals();
            }).catch(function (error) {
                console.log(error);
            });
        },
        addTeam(){
            let self = this
            axios.post('/api/post-team',{
                team:self.team,              
            }).then(function (response) {
                self.team = ''
                self.getTeams();
            }).catch(function (error) {
                console.log(error);
            });
        },
    },

  // async asyncData () {
  //   const { data: projects } = await axios.get('/api/projects')

  //   return {
  //     projects
  //   }
  // },

  metaInfo () {
    return { title: this.$t('home') }
  }
}
</script>
