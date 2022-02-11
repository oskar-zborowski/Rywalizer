import React, { ChangeEvent, useRef } from 'react';
import styles from './Input.scss';

export interface IInputProps<T = string> {
    value?: T;
    onChange?: (value: T, e: ChangeEvent<HTMLInputElement>) => void;
    onBlur?: () => void;
    onEnter?: () => void;
    spellCheck?: boolean;
    type?: 'text' | 'password' | 'date' | 'file';
    label?: string;
    placeholder?: string;
    className?: string;
    style?: React.CSSProperties;
    ref?: React.RefObject<HTMLInputElement>
}

const Input = React.forwardRef<HTMLInputElement, IInputProps>((props, ref) => {
    const {
        value,
        onChange,
        onBlur,
        label,
        placeholder,
        spellCheck = false,
        type = 'text',
        className = '',
        style = {}
    } = props;

    const isFile = type === 'file';

    return (
        <div className={styles.input + ' ' + className} style={style}>
            {label && <label className={styles.label}>{label}</label>}
            <div className={styles.inputWrapper}>
                <input
                    style={{opacity: isFile ? 0 : 1}}
                    ref={ref}
                    type={type}
                    value={value}
                    onChange={(e) => onChange?.(e.target.value, e)}
                    onBlur={onBlur}
                    spellCheck={spellCheck}
                />
            </div>
        </div>
    );
});

export default Input;