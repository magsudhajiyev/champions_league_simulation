import { FootballTeam } from '@/models/FootballTeam'

export interface Fixture {
  round_no: string;
  match_no: number;
  home_team: FootballTeam;
  away_team: FootballTeam;
  home_team_goals: number;
  away_team_goals: number;
}
