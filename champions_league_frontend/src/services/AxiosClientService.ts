
import { FootballTeamApiService } from '@/services/FootballTeamApiService'
import axios from 'axios'
import { ChampionApiService } from '@/services/ChampionApiService'

const axiosInstance = axios.create({ baseURL: process.env.VUE_APP_BACKEND_BASE_URL })

export const footballTeamApiService = new FootballTeamApiService(axiosInstance)

export const championApiService = new ChampionApiService(axiosInstance)
