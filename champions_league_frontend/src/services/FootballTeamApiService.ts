import axios, { AxiosInstance } from 'axios'
import { FootballTeam } from '@/models/FootballTeam'

export class FootballTeamApiService {
  private httpClient;

  private $apiPrefix= 'api/v1/football-teams';

  public constructor (httpClient: AxiosInstance) {
    this.httpClient = httpClient
  }

  async getAll (queryParams = ''): Promise<FootballTeam[]> {
    const { data } = await this.httpClient.get(`${this.$apiPrefix}${queryParams}`)
    return data.data
  }

  async delete (teamId: number):Promise<void> {
    await this.httpClient.delete(`${this.$apiPrefix}/${teamId}`)
  }

  async store (team : FootballTeam): Promise<void> {
    return await this.httpClient.post(`${this.$apiPrefix}`, team)
  }
}
