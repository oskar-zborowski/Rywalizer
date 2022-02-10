import { IPoint } from '@/types/IPoint';
import axios from 'axios';

export interface IGeocodeResults {
    administrativeAreas: string[]
    formattedAddress: string;
    location: IPoint,
    viewport: {
        sw: IPoint,
        ne: IPoint
    }
}

export default async function geocode(address: string): Promise<IGeocodeResults>;
export default async function geocode(location: IPoint): Promise<IGeocodeResults>;
export default async function geocode(arg: string | IPoint) {
    let address = undefined;
    let location = undefined;

    if (typeof arg === 'string') {
        address = arg;
    } else {
        location = arg.lat + ',' + arg.lng;
    }

    const response = await axios.get('https://maps.googleapis.com/maps/api/geocode/json', {
        params: {
            key: 'AIzaSyCi0sAXQWFeT4T5E6jOLlD-V35S6nyrx5Y',
            language: 'pl',
            address,
            latlng: location
        }
    });

    if (response?.data?.status != 'OK') {
        return null;
    }

    const results = response.data.results[0];
    const geometry = results.geometry;
    const administrativeAreas: string[] = [null, null, null, null];

    results.address_components.forEach((component: any) => {
        if (component.types.includes('administrative_area_level_1')) {
            // Województwo
            administrativeAreas[0] = (component.long_name as string).toLowerCase();
        } else if (component.types.includes('administrative_area_level_2')) {
            // Gmina / Powiat
            const areaName = (component.long_name as string).toLowerCase();

            if (areaName.startsWith('powiat')
                || areaName.endsWith('ski')
                || areaName.endsWith('cki')
                || areaName.endsWith('dzki')
            ) {
                administrativeAreas[1] = areaName.replace('powiat', '').trim();
            } else {
                administrativeAreas[2] = areaName;
            }
        } else if (component.types.includes('locality')) {
            // Miasto
            administrativeAreas[3] = (component.long_name as string).toLowerCase();
        }
    });

    if (administrativeAreas[1] === administrativeAreas[3]) {
        administrativeAreas[2] = administrativeAreas[1];
        administrativeAreas[1] = null;
    }

    const geocodeResults: IGeocodeResults = {
        administrativeAreas,
        formattedAddress: results.formatted_address,
        location: geometry.location,
        viewport: {
            sw: geometry.viewport.southwest,
            ne: geometry.viewport.northeast
        }
    };

    return geocodeResults;
}