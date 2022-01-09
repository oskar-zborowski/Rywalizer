import React, { ButtonHTMLAttributes } from 'react';
import styles from './Button.scss';

export interface IButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
    backgroundColor?: string;
    color?: string;
}

export const OrangeButton: React.FC<IButtonProps> = (props) => {
    const {className, type, ...rest} = props;

    return (
        <button type="button" className={styles.orangeButton + ' ' + className} {...rest}>
            {props.children}
        </button>
    );
};

export const GrayButton: React.FC<IButtonProps> = (props) => {
    const {className, type, ...rest} = props;

    return (
        <button type="button" className={styles.grayButton + ' ' + className} {...rest}>
            {props.children}
        </button>
    );
};

export const BlackButton: React.FC<IButtonProps> = (props) => {
    const {className, type, ...rest} = props;

    return (
        <button type="button" className={styles.blackButton + ' ' + className} {...rest}>
            {props.children}
        </button>
    );
};