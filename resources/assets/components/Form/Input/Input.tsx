import React, { ChangeEvent } from 'react';
import styles from './Input.scss';

export interface IInputProps<T = string> {
    value?: T;
    onChange?: (value: T, e: ChangeEvent<HTMLInputElement>) => void;
    onEnter?: () => void;
    spellCheck?: boolean;
    type?: 'text' | 'password' | 'date';
    label?: string;
    placeholder?: string;
    className?: string;
    style?: React.CSSProperties;
}

const Input: React.FC<IInputProps> = (props) => {
    const {
        value,
        onChange,
        label,
        placeholder,
        spellCheck = false,
        type = 'text',
        className = '',
        style = {}
    } = props;

    return (
        <div className={styles.input + ' ' + className} style={style}>
            {label && <label className={styles.label}>{label}</label>}
            <div className={styles.inputWrapper}>
                <input
                    type={type}
                    value={value}
                    onChange={(e) => onChange(e.target.value, e)}
                    spellCheck={spellCheck}
                />
            </div>
        </div>
    );
};

export default Input;