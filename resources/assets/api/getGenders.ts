import axios from 'axios';

const getGenders = async () => {
    const response = await axios.get('/api/v1/genders');

    return response?.data?.data?.gender.map((entry: any) => {
        const gender: IGender = {
            id: entry.id,
            name: entry.descriptionSimple,
            iconUrl: entry.icon
        };

        return gender;
    }) as IGender[];
};

export default getGenders;

export interface IGender {
    id: number;
    name: string;
    iconUrl: string;
}