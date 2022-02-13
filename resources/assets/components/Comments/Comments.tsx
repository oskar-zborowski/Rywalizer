import React, { useRef } from 'react';
import Avatar from '../Avatar/Avatar';
import styles from './Comments.scss';
import prof from '@/static/images/prof.png';
import ReactHtmlParser from 'html-react-parser';
import { OrangeButton } from '../Form/Button/Button';
import Input from '../Form/Input/Input';
import userStore from '@/store/UserStore';
import noProfile from '@/static/images/noProfile.png';
import { observer } from 'mobx-react';

export interface IComment {
    username: string;
    userAvatarUrl?: string;
    createdAt: string;
    comment: string;
    comments?: IComment[];
}

export interface ICommentsProps {
    comments: IComment[];
    onAddComment?: (comment: string) => void;
}

const Comment: React.FC<IComment> = ({ username, createdAt, comment, userAvatarUrl, comments }) => {
    return (
        <div className={styles.comment}>
            <div className={styles.avatarContainer}>
                <Avatar src={userAvatarUrl ?? noProfile} size={40} />
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

const Comments: React.FC<ICommentsProps> = ({ comments, onAddComment }) => {
    const user = userStore.user;
    const commentRef = useRef<HTMLInputElement>();

    return (
        <section className={styles.comments} style={{marginTop: '20px'}}>
            {user && <div className={styles.comment} style={{ marginBottom: '20px' }}>
                <div className={styles.avatarContainer}>
                    <Avatar src={user.avatarUrl ?? noProfile} size={40} />
                </div>
                <div className={styles.contentContainer} style={{ flexDirection: 'row', gap: '15px' }}>
                    <Input
                        ref={commentRef}
                        placeholder="Napisz komentarz"
                    />
                    <OrangeButton 
                        style={{ flex: 'none' }}
                        onClick={() => {
                            const comment = commentRef.current.value;
                            commentRef.current.value = null;

                            onAddComment?.(comment);
                        }}
                    >
                        Dodaj komentarz
                    </OrangeButton>
                </div>
            </div>}
            {comments.length ? comments.map((comment, i) => <Comment key={i} {...comment} />) : 'Brak komentarzy'}
        </section>
    );
};

export default observer(Comments);