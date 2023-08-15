<template>
  <b-row class="mt-4">
    <Loader v-if="isLoading"></Loader>
    <b-col  align-self="center" class="stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between ">
            <h4 class="card-title">Create Team</h4>
            <b-link :to="{ path: '/teams'}"  class="btn btn-outline-primary mx-1">Return To Teams List</b-link>
          </div>

          <div class="pt-2">
            <b-form @submit.stop.prevent="submit">
              <b-form-group id="team-name-group-" label="Name:" label-for="team-name-id">
                <b-form-input
                  id="team-name-id"
                  v-model="team.name"
                  placeholder="Team name"
                  required
                  :state='Object.keys(errors).length > 0 ? !errors.hasOwnProperty("name") : null '
                ></b-form-input>
                <b-form-invalid-feedback  v-for="(error, index) in errors.name"
                                          :key="'team-name-error'+index"
                >{{ error }}.</b-form-invalid-feedback>
              </b-form-group>

              <b-form-group class="m-2" id="power-strength-group-" label="Power Strength:" label-for="power-strength-id">
                <b-form-input
                  class="w-100"
                  id="power-strength-id"
                  type="range"
                  v-model="team.strength_points"
                  placeholder="Team strength"
                  required
                ></b-form-input>
                <p  >Power Strength {{ team.strength_points }} %</p>
              </b-form-group>

              <b-button type="submit" variant="primary">Submit</b-button>
            </b-form>
          </div>
        </div>
      </div>
    </b-col>
  </b-row>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import { FootballTeam } from '@/models/FootballTeam'
import { footballTeamApiService } from '@/services/AxiosClientService'
import Loader from '@/components/Loader.vue'

@Component({
  components: { Loader }
})
export default class TeamsCreate extends Vue {
  /**
   * New football team
   */
  private team : FootballTeam = {
    id: 0,
    name: '',
    strength_points: 0
  }

  /**
   * Error validate inputs
   */
  private errors = {}

  /**
   * Is page loading or waiting data from the server
   */
  private isLoading = false;

  /**
   * Functions
   */

  private submit ():void{
    this.errors = {}
    this.isLoading = true

    footballTeamApiService.store(this.team).then(() => {
      this.$notify({
        type: 'success',
        title: 'Success',
        text: 'added successfully !'
      })

      this.resetForm()
    }).catch(error => {
      this.errors = error.response.data.message
    }).finally(() => { this.isLoading = false })
  }

  private resetForm (): void{
    this.team = {
      id: 0,
      name: '',
      strength_points: 0
    }
  }
}
</script>

<style scoped lang="scss">
</style>
