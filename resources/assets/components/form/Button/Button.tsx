import React from 'react';
import styles from './Button.scss';

export interface IButtonProps {
    backgroundColor?: string;
    color?: string;
}

export const OrangeButton: React.FC = (props) => {
    return (
        <button type="button" className={styles.orangeButton}>
            {props.children}
        </button>
    );
};

export const GrayButton: React.FC = (props) => {
    return (
        <button type="button" className={styles.grayButton}>
            {props.children}
        </button>
    );
};

export const BlackButton: React.FC = (props) => {
    return (
        <button type="button" className={styles.blackButton}>
            {props.children}
        </button>
    );
};