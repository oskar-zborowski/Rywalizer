import useBounds from '@/hooks/useBounds';
import { useOnClickOutside } from '@/hooks/useOnClickOutside';
import { AnimatePresence, motion } from 'framer-motion';
import React, { Fragment, useRef, useState } from 'react';
import ReactDOM from 'react-dom';
import { BsChevronDown } from 'react-icons/bs';
import styles from './Dropdown.scss';

export interface IDropdownProps {
    isOpen: boolean;
    handleIsOpenChange: (isOpen: boolean) => void;
    placeholder?: string;
    transparent?: boolean;
    label?: string;
    dark?: boolean;
    minWidth?: number;
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
        dark,
        isOpen,
        handleIsOpenChange,
        minWidth,
    } = props;

    const barRef = useRef<HTMLDivElement>();
    const containerRef = useRef<HTMLDivElement>();

    useOnClickOutside([barRef, containerRef], () => handleIsOpenChange(false));

    const ItemsContainer = (
        <AnimatePresence>
            {isOpen && (
                <motion.div
                    ref={containerRef}
                    className={styles.itemsContainer}
                    style={{
                        minWidth: minWidth + 'px'
                    }}
                    {...itemsContainerAnimation}
                >
                    {children}
                </motion.div>
            )}
        </AnimatePresence>
    );

    let dropdownClass = styles.dropdown;
    if (transparent) dropdownClass += ' ' + styles.transparent;
    if (dark) dropdownClass += ' ' + styles.dark;

    return (
        <Fragment>
            <div className={styles.dropdownWrapper + ' ' + (isOpen ? styles.open : '')}>
                {label && <label className={styles.label}>{label}</label>}
                <div
                    onClick={() => handleIsOpenChange(!isOpen)}
                    className={dropdownClass}
                    ref={barRef}
                >
                    <span className={styles.placeholder}>{placeholder}</span>
                    <BsChevronDown className={styles.chevronArrow} />
                </div>
                {ItemsContainer}
            </div>
            {/* {ReactDOM.createPortal(ItemsContainer, document.body)} */}
        </Fragment>
    );
};

export default Selectbox;

export interface IDropdownRowProps extends React.HTMLAttributes<HTMLDivElement> {

}

export const DropdownRow: React.FC<IDropdownRowProps> = ({ children, ...props }) => {
    return (
        <div className={styles.row} {...props}>{children}</div>
    );
};