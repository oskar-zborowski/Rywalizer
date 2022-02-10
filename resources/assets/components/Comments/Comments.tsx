import React from 'react';
import Avatar from '../Avatar/Avatar';
import styles from './Comments.scss';
import prof from '@/static/images/prof.png';
import ReactHtmlParser from 'html-react-parser';

export interface IComment {
    username: string;
    userAvatarUrl?: string;
    createdAt: string;
    comment: string;
    comments?: IComment[];
}

export interface ICommentsProps {
    comments: IComment[];
}

const Comment: React.FC<IComment> = ({ username, createdAt, comment, userAvatarUrl, comments }) => {
    return (
        <div className={styles.comment}>
            <div className={styles.avatarContainer}>
                <Avatar src={prof} size={40} />
            </div>
            <div className={styles.contentContainer}>
                <header className={styles.contentHeader}>
                    <span className={styles.username}>{username}</span>
                    <span className={styles.createdAt}>{createdAt}</span>
                </header>
                <div className={styles.content}>
                    {ReactHtmlParser(comment.replace(/\n/g, '<br/>'))}
                </div>
                {comments?.map((comment, i) => <Comment key={i} {...comment} />)}
            </div>
        </div>
    );
};

const Comments: React.FC<ICommentsProps> = ({ comments }) => {
    return (
        <section className={styles.comments}>
            <div className={styles.comment}>
                <div className={styles.avatarContainer}>
                    <Avatar src={prof} size={40} />
                </div>
                <div className={styles.contentContainer}>
                    inputy i buttony do dodawania komciów
                </div>
            </div>
            {comments.map((comment, i) => <Comment key={i} {...comment} />)}
        </section>
    );
};

export default Comments;