<template>
    <div>
        <div class="container mt-5">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            Table
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <th scope="col">User</th>
                                        <th scope="col">P</th>
                                        <th scope="col">AP</th>
                                        <th scope="col">TP</th>
                                        <th scope="col"></th>
                                    </thead>
                                    <tbody>
                                        <tr v-for='(table, index) in tables' :key="table.id">
                                            <td>{{table.name}}</td>                                 
                                            <td>{{table.points}}</td>
                                            <td>{{table.additional_points}}</td>  
                                            <td>{{table.points + table.additional_points}}</td>  
                                            <td class="align-right"> 
                                                <div class="btn-group" role="group" >
                                                <button type="button" class="btn btn-danger btn-sm" @click="reduceAdditionalScore(index)">-</button>
                                                <button type="button" class="btn btn-success btn-sm" @click="addAdditionalScore(index)">+</button>
                                                </div>
                                            </td>                                    
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                                
                        </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            Teams
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <th scope="col">Player</th>
                                    <th scope="col">Team</th>
                                </thead>
                                <tbody>
                                    <tr v-for='(table_team, index) in table_teams' :key="table_team.id">
                                            <td>{{table_team.name}}</td>                                 
                                            <td>
                                                <span class="badge bg-primary m-2" v-for='(team, index) in table_team.teams' :key="team.id">
                                                    {{team.team.name}}
                                                </span>
                                            </td>                                             
                                                                               
                                        </tr>
                                </tbody>
                                </table>                              
                        </div>
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
            tables:{},
            table_teams:{},
            
        }
    },
    mounted(){
        this.getTable()
        this.getTableTeams()
    },
    methods: {
       
        getTable(){
            let self = this
            axios.post('/api/get-table',{
            }).then(function (response) {  
                self.tables = response.data
            }).catch(function (error) {
                console.log(error);
            });
        },
        getTableTeams(){
            let self = this
            axios.post('/api/get-table-teams',{
            }).then(function (response) {   
                self.table_teams = response.data
            }).catch(function (error) {
                console.log(error);
            });
        },
        addAdditionalScore(index){
            let self = this
            self.tables[index].additional_points ++
            axios.post('/api/add-additional-score',{
                mortal_id:self.tables[index].id,            
            }).catch(function (error) {
                console.log(error);
            });
        },
        reduceAdditionalScore(index){
            let self = this
            self.tables[index].additional_points --
            axios.post('/api/reduce-additional-score',{
                mortal_id:self.tables[index].id,            
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
                self.getResults();
            }).catch(function (error) {
                console.log(error);
            });
        },
    },

}
</script>

<style scoped>
.align-right{
 text-align: right;
}

</style>
