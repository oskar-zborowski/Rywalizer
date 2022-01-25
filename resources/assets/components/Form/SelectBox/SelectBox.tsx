import useBoundingBox from '@/hooks/useBoundingBox';
import { useOnClickOutside } from '@/hooks/useOnClickOutside';
import { AnimatePresence, motion } from 'framer-motion';
import React, { CSSProperties, Fragment, useRef, useState } from 'react';
import ReactDOM from 'react-dom';
import styles from './SelectBox.scss';
import { BsChevronDown } from 'react-icons/bs';

export interface IOption<T = any> {
    value: T;
    text: string;
    isSelected?: boolean;
}

export interface SelectboxProps<T = any> {
    isOpen: boolean;
    onClose: () => void;
    onOpen: () => void;
    placeholder?: string;
    transparent?: boolean;
    label?: string;
    multiselect?: boolean;
    initialOptions?: IOption<T>[];
    onChange?: (selectedOptions: IOption<T>[]) => void;
    searchBar?: {
        getOptions: (searchString: string) => IOption<T>[] | Promise<IOption<T>[]>
        debounceTimeMs?: number;
    }
}

const transition = { duration: 0.25, type: 'tween', ease: [0.45, 0, 0.55, 1] };

const itemsContainerAnimation = {
    initial: { transform: 'translateY(-15px)', opacity: 0 },
    animate: { transform: 'translateY(0px)', opacity: 1 },
    exit: { transform: 'translateY(15px)', opacity: 0 },
    transition
};

function Selectbox<T = number>(props: SelectboxProps<T>) {
    const {
        isOpen,
        onClose,
        onOpen,
        placeholder,
        label,
        transparent,
        multiselect = false,
        initialOptions = [],
        onChange,
        searchBar
    } = props;

    const [options, setOptions] = useState<IOption<T>[]>(initialOptions);

    if (multiselect) {

    }

    const handleClickInside = (_e: MouseEvent) => {
        if (isOpen) {
            onClose();
        } else {
            onOpen();
        }
    };

    const ref = useRef<HTMLDivElement>();
    useOnClickOutside(ref, () => onClose(), handleClickInside);

    const ItemsContainer = (
        <AnimatePresence>
            {isOpen && <motion.div
                className={styles.itemsContainer}
                {...itemsContainerAnimation}
            >
                {options.map((op, i) => {
                    return (
                        <li key={i}>{op.text}</li>
                    );
                })}
            </motion.div>}
        </AnimatePresence>
    );

    return (
        <div className={styles.selectBoxWrapper + ' ' + (isOpen ? styles.open : '')}>
            {label && <label className={styles.label}>{label}</label>}
            <div
                className={styles.selectBox + ' ' + (transparent ? styles.transparent : '')}
                ref={ref}
            >
                {placeholder}
                <BsChevronDown className={styles.chevronArrow} />
            </div>
            {ItemsContainer}
        </div>
    );
}

export default Selectbox;