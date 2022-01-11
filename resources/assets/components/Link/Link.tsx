import React from 'react';
import styles from './Link.scss';

export interface LinkProps {
    href?: string;
    onClick?: React.MouseEventHandler;
    fixedColor?: boolean;
}

const Link: React.FC<LinkProps> = ({ href, children, onClick, fixedColor = false }) => {
    const onClickWrapper: React.MouseEventHandler = (e) => {
        e.preventDefault();

        if (href) {
            window.open(href, '_blank');
        } else {
            onClick(e);
        }
    };

    return (
        <a
            className={styles.link + ' ' + (fixedColor ? styles.fixedColor : '')}
            onClick={onClickWrapper}
            href={href ? href : undefined}
        >
            {children}
        </a>
    );
};

export default Link;