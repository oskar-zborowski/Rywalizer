import { useOnClickOutside } from '@/hooks/useOnClickOutside';
import { AnimatePresence, motion } from 'framer-motion';
import React, { useRef, useState } from 'react';
import { BsChevronDown } from 'react-icons/bs';
import styles from './Dropdown.scss';

export interface IDropdownProps {
    placeholder?: string;
    transparent?: boolean;
    label?: string;
    dark?: boolean;
}

const transition = { duration: 0.25, type: 'tween', ease: [0.45, 0, 0.55, 1] };

const itemsContainerAnimation = {
    initial: { transform: 'translateY(-15px)', opacity: 0 },
    animate: { transform: 'translateY(0px)', opacity: 1 },
    exit: { transform: 'translateY(15px)', opacity: 0 },
    transition
};

const Selectbox: React.FC<IDropdownProps> = props => {
    const {
        children,
        placeholder,
        label,
        transparent,
        dark
    } = props;

    const [isOpen, setIsOpen] = useState(false);

    const handleClickInside = (_e: MouseEvent) => {
        setIsOpen(!isOpen);
    };

    const barRef = useRef<HTMLDivElement>();
    const containerRef = useRef<HTMLDivElement>();
    useOnClickOutside([barRef, containerRef], () => setIsOpen(false));

    const ItemsContainer = (
        <AnimatePresence>
            {isOpen && <motion.div
                ref={containerRef}
                className={styles.itemsContainer}
                {...itemsContainerAnimation}
            >
                {children}
            </motion.div>}
        </AnimatePresence>
    );

    let dropdownClass = styles.dropdown;
    if (transparent) dropdownClass += ' ' + styles.transparent;
    if (dark) dropdownClass += ' ' + styles.dark;

    return (
        <div className={styles.dropdownWrapper + ' ' + (isOpen ? styles.open : '')}>
            {label && <label className={styles.label}>{label}</label>}
            <div
                onClick={() => setIsOpen(isOpen => !isOpen)}
                className={dropdownClass}
                ref={barRef}
            >
                <span className={styles.placeholder}>{placeholder}</span>
                <BsChevronDown className={styles.chevronArrow} />
            </div>
            {ItemsContainer}
        </div>
    );
};

export default Selectbox;

export const DropdownRow: React.FC = ({ children }) => {
    return (
        <div className={styles.row}>{children}</div>
    );
};