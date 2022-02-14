import { getApiUrl } from '@/utils/api';
import axios from 'axios';

const savePartner = async (args: ISavePartnerArgs, edit = false) => {
    const body = {
        ...args,
        visibleNameId: 62,
        visibleImageId: 62,
        visibleEmailId: 62,
        visibleTelephoneId: 62,
        visibleFacebookId: 62,
        visibleInstagramId: 62,
        visibleWebsiteId: 62
    };

    if (edit) {
        await axios.patch(getApiUrl('api/v1/partners'), body);
    } else {
        await axios.post(getApiUrl('api/v1/partners'), body);
    }
};

export interface ISavePartnerArgs {
    businessName: string;
    contactEmail: string;
    telephone: string;
    facebookProfile?: string;
    instagramProfile?: string;
    website?: string;
}

export default savePartner;