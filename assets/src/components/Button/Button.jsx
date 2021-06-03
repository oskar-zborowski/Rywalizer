import React from 'react';
import styles from './Button.scss?module';

export const Button = props => {
    const {children, ...restProps} = props;

    return (
        <button className={styles.button} {...restProps}>
            {children}
        </button>
    );
};

export const ButtonLight = props => {
    const {children, ...restProps} = props;

    return (
        <button className={styles.buttonLight} {...restProps}>
            {children}
        </button>
    );
};