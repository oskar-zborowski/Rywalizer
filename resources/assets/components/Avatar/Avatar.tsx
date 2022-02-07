import React from 'react';
import styles from './Avatar.scss';

export interface IAvatarProps {
    src: string;
    alt?: string;
    size?: number;
    className?: string;
}

const Avatar: React.FC<IAvatarProps> = ({ src, alt = '', size = 100, className = ''}) => {
    const style: React.CSSProperties = {
        width: size + 'px',
        height: size + 'px'
    };

    return (
        <img src={src} alt={alt} style={style} className={styles.avatar + ' ' + className} />
    );
};

export default Avatar;