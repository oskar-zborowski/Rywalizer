import React from 'react';
import styles from './Content.scss';

const Content: React.FC = (props) => {
    return (
        <div className={styles.content}>{props.children}</div>
    );
};

export default Content;