import React from 'react';
import styles from './Avatar.scss';

export interface IAvatarProps {
    src: string;
    alt?: string;
    size?: number;
    className?: string;
    radius?: string;
}

const Avatar: React.FC<IAvatarProps> = ({ src, alt = '', size = 100, className = '', radius = '8px' }) => {
    const style: React.CSSProperties = {
        width: size + 'px',
        height: size + 'px',
        borderRadius: radius
    };

    return (
        <img src={src} alt={alt} style={style} className={styles.avatar + ' ' + className} />
    );
};

export default Avatar;