import React from 'react';
import styles from './Icon.scss';

export interface IIconProps {
    icon: React.FC<React.SVGAttributes<SVGElement>>;
}

const Icon: React.FC<IIconProps> = ({icon: SvgIcon, children}) => {
    return (
        <span className={styles.icon}>
            <div className={styles.svgContainer}>
                <SvgIcon width="100%" height="100%" fill="#fff"/>
            </div>
            {children && <span className={styles.text}>{children}</span>}
        </span>
    );
};

export default Icon;