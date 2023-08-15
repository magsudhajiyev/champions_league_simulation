<template>
  <b-row class="mt-4">
    <Loader v-if="isLoading"></Loader>
    <b-col  align-self="center" class="stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between ">
            <h4 class="card-title">Tournament Teams</h4>
            <b-link :to="{ path: '/teams/create'}" class="btn btn-outline-primary mx-1">
              Add
            </b-link>
          </div>

          <div class="pt-2">
            <table class="table">
              <thead class="thead-dark">
              <tr>
                <th>Team Name</th>
                <th>Strength Points</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="team in teams" :key="team.id">
                <td >{{ team.name }}</td>
                <td >{{ team.strength_points }}%</td>
                <td  class="text-end"><b-button @click="deleteTeam(team.id)" variant="danger">Delete</b-button>
                </td>
              </tr>
              </tbody>
            </table>
          </div>

          <b-button :disabled="teams.length < 4"  block variant="primary" @click="generateFixtures">Generate Fixtures</b-button>
          <span v-if="teams.length < 4"> You should have at least 4 teams to start the generation</span>
        </div>
      </div>
    </b-col>
    <router-view></router-view>
  </b-row>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { FootballTeam } from '@/models/FootballTeam'
import { championApiService, footballTeamApiService } from '@/services/AxiosClientService'
import Loader from '@/components/Loader.vue'
import router from '@/router'
@Component({
  components: { Loader }
})
export default class TeamsList extends Vue {
  /**
   * List Of Teams
   */
  private teams: FootballTeam[] = [];

  /**
   * Is page loading or waiting data from the server
   */
  private isLoading = false;

  /**
   * Functions
   */

  mounted (): void {
    this.getTeams()
  }

  private getTeams (): void{
    this.teams = []
    this.isLoading = true
    footballTeamApiService.getAll().then((response) => {
      this.teams = response
    }).finally(() => { this.isLoading = false })
  }

  private deleteTeam (teamId: number): void{
    this.isLoading = true
    footballTeamApiService.delete(teamId).then(() => {
      this.$notify({
        type: 'success',
        title: 'Success',
        text: 'deleted successfully !'
      })

      this.teams = this.teams.filter(p => p.id !== teamId)
    }).finally(() => { this.isLoading = false })
  }

  private generateFixtures (): void{
    this.isLoading = true
    championApiService.generate().then((champion) => {
      router.push('/champion/' + champion.id + '/team-results')
    }).finally(() => { this.isLoading = false })
  }
}
</script>

<style scoped lang="scss">

</style>
