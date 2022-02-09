
import { useOnClickOutside } from '@/hooks/useOnClickOutside';
import Loader from '@/static/loader.svg';
import { AnimatePresence, motion } from 'framer-motion';
import React, { useEffect, useRef } from 'react';
import ReactDOM from 'react-dom';
import { AiOutlineClose } from 'react-icons/ai';
import styles from './Modal.scss';

const transition = { duration: 0.25, type: 'tween', ease: [0.45, 0, 0.55, 1] };

const wrapperAnimation = {
    initial: { opacity: 0 },
    animate: { opacity: 1 },
    exit: { opacity: 0 },
    transition
};

const containerAnimation = {
    initial: { transform: 'translateY(-30px)' },
    animate: { transform: 'translateY(0px)' },
    exit: { transform: 'translateY(30px)' },
    transition
};

const overlayAnimation = {
    initial: { opacity: 0 },
    animate: { opacity: 1 },
    exit: { opacity: 0 },
    transition
};

export interface ModalProps {
    isOpen?: boolean;
    isLoading?: boolean;
    placement?: 'top' | 'middle' | 'bottom'
    onClose?: () => void,
    onEnter?: (e: KeyboardEvent) => void,
    title?: string;
    closeOnClickOutside?: boolean;
    closeButton?: boolean;
    closeOnEsc?: boolean;
    footerItems?: React.ReactNode[];
    width?: React.CSSProperties['width'];
}

const Modal: React.FC<ModalProps> = props => {
    const {
        children,
        isOpen,
        isLoading,
        placement,
        onClose,
        onEnter,
        title,
        width,
        closeOnClickOutside = false,
        closeButton = true,
        closeOnEsc = false,
        footerItems = [],
    } = props;

    const containerRef = useRef();

    if (closeOnClickOutside) {
        useOnClickOutside([containerRef], () => onClose());
    }

    if (closeOnEsc) {
        useEffect(() => {
            const listener = (e: KeyboardEvent) => {
                if (e.key == 'Escape') {
                    onClose();
                }
            };

            document.addEventListener('keydown', listener);

            return () => {
                document.removeEventListener('keydown', listener);
            };
        }, []);
    }

    if (onEnter) {
        useEffect(() => {
            const listener = (e: KeyboardEvent) => {
                if (e.key == 'Enter') {
                    onEnter && onEnter(e);
                }
            };

            document.addEventListener('keydown', listener);

            return () => {
                document.removeEventListener('keydown', listener);
            };
        }, []);
    }

    return ReactDOM.createPortal((
        <AnimatePresence>
            {isOpen && <motion.div
                className={styles.wrapper}
                {...wrapperAnimation}
            >
                <div className={styles.containerWrapper}>
                    <motion.div
                        ref={containerRef}
                        className={styles.container}
                        {...containerAnimation}
                        style={{ width }}
                    >
                        <header className={styles.header}>
                            {title && <span className={styles.title}>{title}</span>}
                            {closeButton && <div
                                className={styles.closeButton}
                                onClick={onClose}
                            >
                                <AiOutlineClose />
                            </div>
                            }
                        </header>
                        <main className={styles.body}>{children}</main>
                        <footer className={styles.footer}>
                            {...footerItems}
                        </footer>
                        <AnimatePresence>
                            {isLoading && <motion.div
                                className={styles.loadingOverlay}
                                {...overlayAnimation}
                            >
                                <Loader />
                            </motion.div>}
                        </AnimatePresence>
                    </motion.div>
                </div>
            </motion.div>}
        </AnimatePresence>
    ), document.body);
};

export default Modal;