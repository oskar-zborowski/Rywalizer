import { getApiUrl } from '@/utils/api';
import axios from 'axios';
import chroma from 'chroma-js';

const getSports = async () => {
    const response = await axios.get(getApiUrl('api/v1/sports'));

    return response?.data?.data?.sport.map((entry: any) => {
        const sport: ISport = {
            id: +entry.id,
            name: entry.descriptionSimple,
            iconUrl: entry.icon,
            color: chroma(chroma.valid(entry.color) ? entry.color : '#000')
        };

        return sport;
    }) as ISport[];
};

export default getSports;

export interface ISport {
    id: number;
    name: string;
    iconUrl: string;
    color: chroma.Color;
}