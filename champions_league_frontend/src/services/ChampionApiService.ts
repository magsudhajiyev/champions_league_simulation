import { AxiosInstance } from 'axios'
import { Champion } from '@/models/Champion'
import { TeamResult } from '@/models/TeamResult'
import { ChampionPredictionResult } from '@/models/ChampionPredictionResult'
import { Fixture } from '@/models/Fixture'

export class ChampionApiService {
  private httpClient;

  private $apiPrefix= 'api/v1/champions';

  public constructor (httpClient: AxiosInstance) {
    this.httpClient = httpClient
  }

  async generate (queryParams = ''): Promise<Champion> {
    const { data } = await this.httpClient.get(`${this.$apiPrefix}/generate${queryParams}`)
    return data.data
  }

  async getTeamResult (championId: number, queryParams = ''): Promise<TeamResult[]> {
    const { data } = await this.httpClient.get(`${this.$apiPrefix}/${championId}/team-results${queryParams}`)
    return data.data
  }

  async getNextWeekFixtures (championId: number, queryParams = ''): Promise<Fixture[]> {
    const { data } = await this.httpClient.get(`${this.$apiPrefix}/${championId}/fixtures/next-week${queryParams}`)
    return data.data
  }

  async getPredictionsResults (championId: number, queryParams = ''): Promise<ChampionPredictionResult[]> {
    const { data } = await this.httpClient.get(`${this.$apiPrefix}/${championId}/predictions-results${queryParams}`)
    return data.data
  }

  async playWeek (championId: number, queryParams = ''): Promise<Champion> {
    const { data } = await this.httpClient.get(`${this.$apiPrefix}/${championId}/play-week${queryParams}`)
    return data.data
  }
}
