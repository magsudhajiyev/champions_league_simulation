import Vue from 'vue'
import VueRouter, { RouteConfig } from 'vue-router'
import TemList from '@/views/teams/TeamsList.vue'
import TeamCreate from '@/views/teams/TeamsCreate.vue'
import Teams from '@/views/teams/Teams.vue'
import ChampionTeamResult from '@/views/champion/ChampionTeamResult.vue'

Vue.use(VueRouter)

const routes: Array<RouteConfig> = [
  {
    path: '/',
    name: 'Home',
    component: TemList
  },
  {
    path: '/teams',
    component: Teams,
    name: 'Team',
    children: [
      {
        path: '',
        component: TemList,
        name: 'TemList'
      },
      {
        path: 'create',
        component: TeamCreate,
        name: 'TeamCreate'
      }
    ]
  },
  {
    path: '/champion/:id/team-results',
    name: 'ChampionTeamResult',
    component: ChampionTeamResult
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
