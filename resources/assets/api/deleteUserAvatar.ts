
import axios from 'axios';

const deleteUserAvatar = async (avatarId: number) => {
    await axios.delete(`/api/v1/user/avatar/${avatarId}`);
};

export default deleteUserAvatar;