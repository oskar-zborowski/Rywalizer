import axios from 'axios';

const deletePartnerAvatar = async (avatarId: number) => {
    await axios.delete(`/api/v1/partners/logo/${avatarId}`);
};

export default deletePartnerAvatar;