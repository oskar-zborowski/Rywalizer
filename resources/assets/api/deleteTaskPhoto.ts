import axios from 'axios';

const deleteTaskPhoto = async (announcementId: number, photoId: number) => {
    await axios.delete(`/api/v1/announcements/${announcementId}/photos/${photoId}`);
};

export default deleteTaskPhoto;