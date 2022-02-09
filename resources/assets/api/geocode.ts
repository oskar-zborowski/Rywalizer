import { IPoint } from '@/types/IPoint';
import axios from 'axios';

export interface IGeocodeResults {
    formattedAddress: string;
    location: IPoint,
    viewport: {
        sw: IPoint,
        ne: IPoint
    }
}

const geocode = async (address: string) => {
    const response = await axios.get('https://maps.googleapis.com/maps/api/geocode/json', {
        params: {
            key: 'AIzaSyCi0sAXQWFeT4T5E6jOLlD-V35S6nyrx5Y',
            language: 'pl',
            address
        }
    });

    const results = response?.data?.results[0];
    const geometry = results?.geometry;
    const formattedAddress = results?.formatted_address;

    console.log(results);

    if (geometry && formattedAddress) {
        return {
            formattedAddress,
            location: geometry.location,
            viewport: {
                sw: geometry.viewport.southwest,
                ne: geometry.viewport.northeast
            }
        } as IGeocodeResults;
    } else {
        return null;
    }
};

export default geocode;