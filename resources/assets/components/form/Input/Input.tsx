import React, { ChangeEvent, useRef } from 'react';
import styles from './Input.scss';

export interface IInputProps<T = string> {
    value?: T;
    onChange?: (value: T, e: ChangeEvent<HTMLInputElement>) => void;
    onBlur?: () => void;
    onEnter?: () => void;
    spellCheck?: boolean;
    type?: 'text' | 'password' | 'date' | 'file' | 'datetime-local';
    label?: string;
    placeholder?: string;
    className?: string;
    style?: React.CSSProperties;
    ref?: React.RefObject<HTMLInputElement>
    min?: string;
    tip?: string;
    tipDelay?: number;
}

const Input = React.forwardRef<HTMLInputElement, IInputProps>((props, ref) => {
    const {
        value,
        onChange,
        onEnter,
        onBlur,
        label,
        placeholder,
        spellCheck = false,
        type = 'text',
        className = '',
        style = {},
        min,
        tip = undefined,
        tipDelay = 0,
    } = props;

    const isFile = type === 'file';
    const handleKeyDown: React.KeyboardEventHandler<HTMLInputElement> = (event) => {
        if (event.key === 'Enter') {
            onEnter?.();
        }
    };

    return (
        <div className={styles.input + ' ' + className} style={style}>
            {label && <label className={styles.label}>{label}</label>}
            <div className={styles.inputWrapper}>
                <input
                    min={min}
                    placeholder={placeholder}
                    style={{ opacity: isFile ? 0 : 1 }}
                    ref={ref}
                    type={type}
                    value={value}
                    onChange={(e) => onChange?.(e.target.value, e)}
                    onBlur={onBlur}
                    spellCheck={spellCheck}
                    onKeyDown={handleKeyDown}
                    data-tip={tip}
                    data-delay-show={tipDelay}
                />
            </div>
        </div>
    );
});

export default Input;