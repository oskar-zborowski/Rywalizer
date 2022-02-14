import { getApiUrl } from '@/utils/api';
import axios from 'axios';

const getPartner = async (): Promise<IPartner> => {
    const response = await axios.get(getApiUrl('api/v1/partners'));
    const data = response?.data?.data?.partner;

    return {
        id: data.id,
        businessName: data.businessName,
        alias: data.alias,
        imageId: +data.logos?.[0]?.id,
        imageUrl: data.logos?.[0]?.filename,
        contactEmail: data.contactEmail,
        telephone: data.telephone,
        facebook: data.facebook,
        instagram: data.instagram,
        website: data.website,
        verified: !!data.verified
    };
};

export default getPartner;

export interface IPartner {
    id: number,
    businessName: string,
    alias: string,
    imageId: number;
    imageUrl: string,
    contactEmail: string,
    telephone: string,
    facebook: string,
    instagram: string,
    website: string,
    verified: boolean
}