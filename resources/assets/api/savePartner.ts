import { getApiUrl } from '@/utils/api';
import axios from 'axios';

const savePartner = async (args: ISavePartnerArgs, edit = false, imageFile?: File) => {
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

        if (imageFile) {
            const formData = new FormData();
            formData.append('logo', imageFile);

            const result = await axios.post(getApiUrl('api/v1/partners/logo'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            return {
                imageUrl: result?.data?.data?.partner?.logos?.[0]?.filename,
                imageId: +result?.data?.data?.partner?.logos?.[0]?.id
            };
        }
    } else {
        await axios.post(getApiUrl('api/v1/partners'), body);
    }

    return {};
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