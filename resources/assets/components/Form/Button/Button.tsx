import React, { ButtonHTMLAttributes } from 'react';
import styles from './Button.scss';
import FacebookLogoSvg from '@/static/icons/facebook-icon.svg';
import GoogleLogoSvg from '@/static/icons/google.svg';

export interface IButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
    textAlign?: 'left' | 'center' | 'right';
    isFluid?: boolean;
    icon?: {
        svg: React.FC<React.SVGAttributes<SVGElement>>;
        size?: string | number;
    }
}

const textAlignMap = {
    'left': 'flex-start',
    'center': 'center',
    'right': 'flex-end'
};

const Button: React.FC<IButtonProps> = (props) => {
    const {
        textAlign,
        className,
        style,
        isFluid,
        icon,
        ...rest
    } = props;

    const mergedStyle: React.CSSProperties = Object.assign({
        justifyContent: textAlignMap[textAlign]
    }, style);

    if (isFluid) {
        mergedStyle.width = '100%';
    }

    let iconElement: JSX.Element = null;

    if (icon) {
        const { svg: SvgIcon, size = 15 } = icon;
        const svgContainerStyle = {
            height: size,
            minWidth: size
        } as React.CSSProperties;

        iconElement = <div className={styles.icon} style={svgContainerStyle}>
            <SvgIcon width="100%" height="100%" />
        </div>;
    }

    return (
        <button
            type="button"
            style={mergedStyle}
            className={styles.button + ' ' + className}
            {...rest}
        >
            {iconElement}
            <span className={styles.text}>{props.children}</span>
        </button>
    );
};

export default Button;

export const OrangeButton: React.FC<IButtonProps> = (props) => {
    const { className, ...rest } = props;

    return <Button className={styles.orangeButton + ' ' + className} {...rest} />;
};

export const GrayButton: React.FC<IButtonProps> = (props) => {
    const { className, ...rest } = props;

    return <Button className={styles.grayButton + ' ' + className} {...rest} />;
};

export const BlackButton: React.FC<IButtonProps> = (props) => {
    const { className, ...rest } = props;

    return <Button className={styles.blackButton + ' ' + className} {...rest} />;
};

export const FacebookButton: React.FC<IButtonProps> = (props) => {
    const {
        className,
        children,
        isFluid = true,
        textAlign = 'left',
        icon,
        ...rest
    } = props;

    return (
        <Button
            icon={{
                svg: FacebookLogoSvg,
                size: 14
            }}
            isFluid={isFluid}
            textAlign={textAlign}
            className={styles.facebookButton + ' ' + className}
            {...rest}
        >
            {children ?? 'Facebook'}
        </Button>
    );
};

export const GoogleButton: React.FC<IButtonProps> = (props) => {
    const {
        className,
        children,
        isFluid = true,
        textAlign = 'left',
        icon,
        ...rest
    } = props;

    return (
        <Button
            icon={{
                svg: GoogleLogoSvg,
                size: 14
            }}
            isFluid={isFluid}
            textAlign={textAlign}
            className={styles.googleButton + ' ' + className}
            {...rest}
        >
            {children ?? 'Google'}
        </Button>
    );
};
