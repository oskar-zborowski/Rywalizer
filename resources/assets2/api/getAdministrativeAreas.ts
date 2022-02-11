import { getApiUrl } from '@/utils/api';
import axios from 'axios';

const getAdministrativeAreas = async (query: string, areaType?: AdministrativeAreaType) => {
    const response = await axios.get(getApiUrl('api/v1/areas'), {
        params: {
            in: 'name,' + query,
            like: areaType ? ('area_type_id,' + areaType) : undefined
        }
    });

    const areas = response?.data?.data;

    if (!areas) {
        return null;
    }

    const formattedAreas: IAdministrativeArea[] = [];

    areas.forEach(({ area: entry }) => {
        let type: AdministrativeAreaType;

        if (entry.areaType.name == 'CITY') {
            type = AdministrativeAreaType.CITY;
        } else if (entry.areaType.name == 'COMMUNE') {
            type = AdministrativeAreaType.COMMUNE;
        } else if (entry.areaType.name == 'POVIAT') {
            type = AdministrativeAreaType.DISTRICT;
        } else if (entry.areaType.name == 'VOIVODESHIP') {
            type = AdministrativeAreaType.VOIVODESHIP;
        } else if (entry.areaType.name == 'COUNTRY') {
            type = AdministrativeAreaType.COUNTRY;
        }

        formattedAreas.push({
            id: entry.id,
            name: entry.name,
            type
        });
    });

    return formattedAreas;
};

export default getAdministrativeAreas;

export interface IAdministrativeArea {
    id: number;
    name: string;
    type: AdministrativeAreaType;
}

export enum AdministrativeAreaType {
    CITY = 44,
    COMMUNE,
    DISTRICT,
    VOIVODESHIP,
    COUNTRY
}
