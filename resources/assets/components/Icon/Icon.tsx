import React from 'react';
import styles from './Icon.scss';

export interface IIconProps {
    svg: React.FC<React.SVGAttributes<SVGElement>>;
    /**
     * default `15px`
     */
    size?: string | number;
    /**
     * default `left`
     */
    textPosition?: 'top' | 'right' | 'bottom' | 'left';
    className?: string;
}

const Icon: React.FC<IIconProps> = ({ svg: SvgIcon, children, className, size = '15px', textPosition = 'right' }) => {
    let flexDirection = null;

    if (textPosition == 'top') {
        flexDirection = 'column-reverse';
    } else if (textPosition == 'right') {
        flexDirection = 'row';
    } else if (textPosition == 'bottom') {
        flexDirection = 'column';
    } else if (textPosition == 'left') {
        flexDirection = 'row-reverse';
    }

    const iconStyle = {
        flexDirection: flexDirection
    } as React.CSSProperties;

    size = typeof size === 'number' ? size + 'px' : size;

    const svgContainerStyle = {
        height: size,
        minWidth: size
    } as React.CSSProperties;

    return (
        <div className={styles.icon + ' ' + className} style={iconStyle}>
            <div className={styles.svgContainer} style={svgContainerStyle}>
                <SvgIcon width="100%" height="100%" fill="#fff" />
            </div>
            {children && <span className={styles.text}>{children}</span>}
        </div>
    );
};

export default Icon;