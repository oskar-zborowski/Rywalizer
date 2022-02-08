import React from 'react';
import styles from './View.scss';

export interface IViewProps {
    withBackground?: boolean;
    title?: string;
}

const View: React.FC<IViewProps> = ({ withBackground, children, title }) => {
    const content = !withBackground ? children : (
        <div className={styles.grayPane}>
            {title && <header className={styles.header}>
                <h1>{title}</h1>
            </header>}
            {children}
        </div>
    );

    return (
        <div className={styles.view}>{content}</div>
    );
};

export default View;