import { getApiUrl } from '@/utils/api';
import axios from 'axios';

const getAdministrativeAreas = async (query: string) => {
    const response = await axios.get(getApiUrl('api/v1/announcements'), {
        params: {
            in: 'name,' + query,
            like: 'area_type_id,44'
        }
    });

    console.log(response.data);
};

export default getAdministrativeAreas;

export interface IAdministrativeArea {
    id: number;
    name: string;
    type: AdministrativeAreaType;

    // 'id': 4,
    // 'name': 'Poznań',
    // 'boundary': null,
    // 'areaType': {
    //     'id': 43,
    //     'name': 'COMMUNE',
    //     'description': 'Gmina'
    // }
    // 'area': {
    //     'id': 4,
    //     'name': 'Poznań',
    //     'boundary': null,
    //     'areaType': {
    //         'id': 43,
    //         'name': 'COMMUNE',
    //         'description': 'Gmina'
    //     }
    // }
}

export enum AdministrativeAreaType {
    TOWN,
    COMMUNE,
    DISTRICT
}
