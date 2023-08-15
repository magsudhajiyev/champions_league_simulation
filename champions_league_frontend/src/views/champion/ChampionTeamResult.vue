<template>
  <div>
    <Loader v-if="isLoading"></Loader>
    <b-row class="mt-4">
      <b-col  align-self="center">
        <h4 class="text-center">Simulation</h4>
      </b-col>
    </b-row>

    <b-row class="mt-4">
      <b-col md="6">
        <table class="table">
          <thead class="thead-dark">
          <tr>
            <th>Team Name</th>
            <th>P</th>
            <th>W</th>
            <th>D</th>
            <th>L</th>
            <th>GD</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="teamResult in teamResults" :key="teamResult.id">
            <td>{{ teamResult.team_name }}</td>
            <td>{{ teamResult.play }}</td>
            <td>{{ teamResult.win }}</td>
            <td>{{ teamResult.draw }}</td>
            <td>{{ teamResult.loss }}</td>
            <td>{{ teamResult.goals_for - teamResult.goals_against }}</td>
          </tr>
          </tbody>
        </table>
      </b-col>

      <b-col md="3" >
        <table class="table">
          <thead class="thead-dark">
          <tr>
            <th>Week{{ nextWeekNo }}</th>
            <th></th>
            <th></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="nextWeekFixture in nextWeekFixtures" :key="nextWeekFixture.id">
            <td>{{ nextWeekFixture.home_team.name }} <strong>(H)</strong></td>
            <td> -</td>
            <td>{{ nextWeekFixture.away_team.name }} <strong>(A)</strong></td>
          </tr>
          </tbody>
        </table>
      </b-col>

      <b-col md="3"  >
        <table class="table">
          <thead class="thead-dark">
          <tr>
            <th>Championship Prediction</th>
            <th>%</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="predictionResult in predictionResults" :key="predictionResult.id">
            <td>{{ predictionResult.team_name }}</td>
            <td>{{ predictionResult.predication_percentage }}%</td>
          </tr>
          </tbody>
        </table>
      </b-col>
    </b-row>
    <b-row class="mt-4">
      <div class="d-flex justify-content-around">
        <b-button :disabled="nextWeekNo === null" @click="playWeek('ALL_WEEKS')" size="lg" block variant="primary">Play all week</b-button>

        <b-button :disabled="nextWeekNo === null" @click="playWeek('NEXT_WEEK')" size="lg" block variant="primary">Play next week</b-button>

        <b-button @click="resetData" size="lg" block variant="danger">Reset Data</b-button>
      </div>

    </b-row>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { championApiService } from '@/services/AxiosClientService'
import { Fixture } from '@/models/Fixture'
import { ChampionPredictionResult } from '@/models/ChampionPredictionResult'
import router from '@/router'
import { TeamResult } from '@/models/TeamResult'
import Loader from '@/components/Loader.vue'

@Component({
  components: { Loader }
})
export default class ChampionTeamResult extends Vue {
  /**
   * Champion id
   */
  private championId:number = parseInt(this.$route.params.id)

  /**
   * List of recent team result
   */
  private teamResults:TeamResult[] = []

  /**
   * List of next week fixtures
   */
  private nextWeekFixtures:Fixture[] = []

  /**
   * List of prediction results
   */
  private predictionResults:ChampionPredictionResult[] = []

  /**
   * Next week no
   */
  private nextWeekNo : string | null = null

  /**
   * Is page loading or waiting data from the server
   */
  private isLoading = false;

  /**
   * Functions
   */

  mounted (): void {
    this.isLoading = true

    this.getTeamResult()

    this.getNextWeekFixtures()

    this.getPredictionResult()
  }

  private getTeamResult (): void{
    this.teamResults = []
    championApiService.getTeamResult(this.championId).then((response) => {
      this.teamResults = response
    }).finally(() => { this.isLoading = false })
  }

  private getNextWeekFixtures (): void{
    this.nextWeekFixtures = []
    this.nextWeekNo = null
    championApiService.getNextWeekFixtures(this.championId).then((response) => {
      this.nextWeekFixtures = response
      if (response.length > 0) {
        this.nextWeekNo = this.nextWeekFixtures[0].round_no
      }
    }).finally(() => { this.isLoading = false })
  }

  private getPredictionResult (): void{
    this.predictionResults = []
    championApiService.getPredictionsResults(this.championId).then((response) => {
      this.predictionResults = response
    }).finally(() => { this.isLoading = false })
  }

  private resetData (): void{
    router.push('/')
  }

  private playWeek (type: string): void{
    this.isLoading = true

    championApiService.playWeek(this.championId, '?type=' + type).then((response) => {
      if (response.status === 'DONE') {
        this.nextWeekNo = null
      }
      this.getTeamResult()
      this.getPredictionResult()
      this.getNextWeekFixtures()
    })
  }
}
</script>

<style scoped lang="scss">

</style>
