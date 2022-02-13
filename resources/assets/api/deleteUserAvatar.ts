import { getApiUrl } from '@/utils/api';
import axios from 'axios';

const deleteUserAvatar = async (avatarId: number) => {
    await axios.delete(getApiUrl(`/api/v1/user/avatar/${avatarId}`));
};

export default deleteUserAvatar;