import ky from 'ky'

export interface IRegion {
  continentName: string
  regionName: string
  countryCount: number
  avgLifeExpectancy: number
  totalPopulation: number
  cityCount: number
  languageCount: number
}

export const fetchRegions = async (apiUrl: string, options = {}): Promise<IRegion[] | false> => {
  const response = await ky.get(apiUrl, {searchParams: options})
  if (response.status === 200) {
    return (await response.json()) as unknown as IRegion[]
  }
  return false
}
