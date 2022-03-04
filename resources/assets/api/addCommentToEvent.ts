import axios from 'axios';

const addCommentToEvent = async (args: IAddCommentArgs) => {
    await axios.post('/api/v1/announcement/comment', args);
};

export default addCommentToEvent;

export interface IAddCommentArgs {
    announcementId: number;
    comment: string;
    //TODO dodawanie do parenta
}