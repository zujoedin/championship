<template>
   <div class="container mt-5">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                          Results
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <select type="text" class="form-control" style="width:20%" v-model='team_id_1'>
                                    <option value="">Select...</option>
                                    <option v-for='(team, index) in teams' :key="team.id" :value="team.id">
                                    {{team.name}}
                                    </option>
                                </select>
                                <select type="text" class="form-control" style="width:20%" v-model='team_id_2'>
                                    <option value="">Select...</option>
                                    <option v-for='(team, index) in teams' :key="team.id" :value="team.id">
                                    {{team.name}}
                                </option>
                                </select>
                                <input type="text" class="form-control" v-model="team_score_1">
                                <input type="text" class="form-control" v-model="team_score_2">
                                <div class="input-group-append">
                                  <button class="btn btn-success" type="button" @click="addResult()">Add</button>
                                </div>
                              </div>
                              <table class="table">
                              
                                <tbody>
                                    <tr v-for='(result, index) in results' :key="result.id">
                                        <td>{{result.team_1.name}} </br> <span v-if="result.team_1.pair" >({{result.team_1.pair.mortal.name}})</span> </td>
                                        <td><div class="bg-dark text-white">{{result.score_1}}</div></td>
                                        <td><div class="bg-dark text-white">{{result.score_2}}</div></td>
                                        <td>{{result.team_2.name}} </br><span v-if="result.team_2.pair" >({{result.team_2.pair.mortal.name}})</span></td>                                   
                                    
                                        <td>
                                            <button type="button" @click="deleteResult(index)" class="btn btn-danger btn-sm"> <i class="fa-solid fa-trash fa-sm"></i> </button>
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
            results:{},
            teams:{},
            team_id_1:'',
            team_id_2:'',
            team_score_1:'',
            team_score_2:'',
            
        }
    },
    mounted(){
        this.getResults()
        this.getTeams()
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
        getResults(){
            let self = this
            axios.post('/api/get-results',{
            }).then(function (response) {        
                self.results = response.data
            }).catch(function (error) {
                console.log(error);
            });
        },
        deleteResult(index){
            let self = this
            axios.post('/api/delete-result',{
                id:self.results[index].id,            
            }).then(function (response) {
                self.getResults()
            }).catch(function (error) {
                console.log(error);
            });
        },
        addResult(){
            let self = this
            axios.post('/api/post-result',{
                team_id_1:self.team_id_1,
                team_id_2:self.team_id_2,   
                team_score_1:self.team_score_1, 
                team_score_2:self.team_score_2,             
            }).then(function (response) {
                self.team_id_2 = ''
                self.team_id_1 = ''
                self.team_score_2 = ''
                self.team_score_1 = ''
                self.getResults();
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

 
}
</script>
<style scoped>

.text-white{
    padding: 5px 10px;
    margin-top: 8px;
}
select,input{
    font-size: 12px;
}
</style>
