import React, { ChangeEvent, useRef } from 'react';
import styles from './Textarea.scss';

export interface IInputProps<T = string> {
    value?: T;
    onChange?: (value: T, e: ChangeEvent<HTMLTextAreaElement>) => void;
    onBlur?: () => void;
    onEnter?: () => void;
    spellCheck?: boolean;
    label?: string;
    className?: string;
    style?: React.CSSProperties;
    ref?: React.RefObject<HTMLTextAreaElement>;
    height?: number;
    placeholder?: string;
}

const Textarea = React.forwardRef<HTMLTextAreaElement, IInputProps>((props, ref) => {
    const {
        value,
        onChange,
        onBlur,
        label,
        spellCheck = false,
        className = '',
        style = {},
        height,
        placeholder
    } = props;

    return (
        <div className={styles.textArea + ' ' + className} style={style}>
            {label && <label className={styles.label}>{label}</label>}
            <div className={styles.wrapper}>
                <textarea
                    placeholder={placeholder}
                    style={{height: height + 'px'}}
                    ref={ref}
                    value={value}
                    onChange={(e) => onChange?.(e.target.value, e)}
                    onBlur={onBlur}
                    spellCheck={spellCheck}
                />
            </div>
        </div>
    );
});

export default Textarea;