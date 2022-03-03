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
    beforeBar?: React.ReactNode;
    trigger?: React.ReactNode;
    triggerRef?: React.RefObject<any>;
    align?: 'left' | 'right';
    placeholder?: string;
    transparent?: boolean;
    horizontalOffset?: number;
    placeholderClassName?: string;
    className?: string;
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

const Dropdown: React.FC<IDropdownProps> = props => {
    const {
        children,
        placeholder,
        label,
        transparent,
        dark,
        isOpen,
        beforeBar = null,
        trigger = null,
        align = 'left',
        horizontalOffset = 0,
        className = '',
        placeholderClassName = '',
        handleIsOpenChange,
        minWidth,
    } = props;

    const barRef = useRef<HTMLDivElement>();
    const containerRef = useRef<HTMLDivElement>();
    const triggerRef = useRef<HTMLDivElement>();

    useOnClickOutside([barRef, containerRef, triggerRef], () => handleIsOpenChange(false));

    const containerStyle = {
        minWidth: minWidth + 'px'
    } as React.CSSProperties;

    if (align == 'left') {
        containerStyle.left = horizontalOffset + 'px';
    } else if (align == 'right') {
        containerStyle.right = horizontalOffset + 'px';
    }

    const ItemsContainer = (
        <AnimatePresence>
            {isOpen && (
                <motion.div
                    ref={containerRef}
                    className={styles.itemsContainer}
                    style={containerStyle}
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

    let content = null;
         
    if (trigger) {
        content = (
            <div
                onClick={() => handleIsOpenChange(!isOpen)}
                className={dropdownClass}
                ref={triggerRef}
            >
                {trigger}
            </div>
        );
    } else {
        content = (
            <Fragment>
                {label && <label className={styles.label}>{label}</label>}
                <div
                    onClick={() => handleIsOpenChange(!isOpen)}
                    className={dropdownClass}
                    ref={barRef}
                >
                    {beforeBar}
                    <span className={styles.placeholder + ' ' + placeholderClassName}>{placeholder}</span>
                    <BsChevronDown className={styles.chevronArrow} />
                </div>
            </Fragment>
        );
    }

    return (
        <div className={styles.dropdownWrapper + ' ' + (isOpen ? styles.open : '') + ' ' + className}>
            {content}
            {ItemsContainer}
        </div>
    );
};

export default Dropdown;

export interface IDropdownRowProps extends React.HTMLAttributes<HTMLDivElement> {

}

export const DropdownRow: React.FC<IDropdownRowProps> = ({ children, ...props }) => {
    return (
        <div className={styles.row} {...props}>{children}</div>
    );
};

export const DropdownSeparator: React.FC = () => {
    return (
        <div className={styles.separator}></div>
    );
};