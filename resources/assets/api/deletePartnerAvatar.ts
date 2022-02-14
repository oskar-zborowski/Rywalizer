import { getApiUrl } from '@/utils/api';
import axios from 'axios';

const deletePartnerAvatar = async (avatarId: number) => {
    await axios.delete(getApiUrl(`/api/v1/partners/logo/${avatarId}`));
};

export default deletePartnerAvatar;