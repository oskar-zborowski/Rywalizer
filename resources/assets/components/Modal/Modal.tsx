
import { useOnClickOutside } from '@/hooks/useOnClickOutside';
import { AnimatePresence, motion } from 'framer-motion';
import React, { useRef} from 'react';
import ReactDOM from 'react-dom';
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
    onClose?: () => void
}

const Modal: React.FC<ModalProps> = props => {
    const { children, isOpen, isLoading, placement, onClose } = props;
    const containerRef = useRef();

    useOnClickOutside(containerRef, () => onClose());

    return ReactDOM.createPortal((
        <AnimatePresence>
            {isOpen && <motion.div 
                className={styles.wrapper}
                {...wrapperAnimation}
            >
                <motion.div 
                    ref={containerRef}
                    className={styles.container + ' ' + styles[placement] || ''}
                    {...containerAnimation}
                >
                    { children }
                    {/* <AnimatePresence>
                        {isLoading && <motion.div 
                            className={styles.loadingOverlay}
                            {...overlayAnimation}
                        >
                            <LoadingCircle/>
                        </motion.div>}
                    </AnimatePresence> */}
                </motion.div>
            </motion.div>}
        </AnimatePresence>
    ), document.body);
};

export default Modal;