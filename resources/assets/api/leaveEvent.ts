import axios from 'axios';

const leaveEvent = async (args: ILeaveEventArgs) => {
    await axios.delete('/api/v1/announcements/leave', {
        data: args
    });
};

export interface ILeaveEventArgs {
    userId: number;
    announcementId: number;
    announcementSeatId: number;
}

export default leaveEvent;