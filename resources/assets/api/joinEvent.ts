import { getApiUrl } from '@/utils/api';
import axios from 'axios';

const joinEvent = async (args: IJoinEventArgs) => {
    await axios.post(getApiUrl('api/v1/announcements/join'), args);
};

export interface IJoinEventArgs {
    announcementId: number;
    announcementSeatId: number;
}

export default joinEvent;