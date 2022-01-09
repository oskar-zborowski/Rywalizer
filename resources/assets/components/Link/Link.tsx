import React from 'react';
import styles from './Link.scss';

export interface LinkProps {
    url?: string;
    onClick?: React.MouseEventHandler;
}

const Link: React.FC<LinkProps> = ({ url, children }) => {
    const onClick: React.MouseEventHandler = (e) => {
        e.preventDefault();

        if (url) {
            window.open(url, '_blank');
        }
    };

    return (
        <a
            className={styles.link}
            onClick={onClick}
            href={url ? url : ''}
        >
            {children}
        </a>
    );
};

export default Link;