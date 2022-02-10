import { AnimatePresence, motion } from 'framer-motion';
import React from 'react';
import styles from './LoaderOverlay.scss';

const transition = { duration: 0.25, type: 'tween', ease: [0.45, 0, 0.55, 1] };

const overlayAnimation = {
    initial: { opacity: 0 },
    animate: { opacity: 1 },
    exit: { opacity: 0 },
    transition
};

export interface ILoaderOverlayProps {
    isVisible: boolean;
}

const LoaderOverlay: React.FC<ILoaderOverlayProps> = ({ isVisible }) => {
    return (
        <AnimatePresence>
            {isVisible && (
                <motion.div
                    className={styles.loaderOverlay}
                    {...overlayAnimation}
                >
                </motion.div>
            )}
        </AnimatePresence>
    );
};

export default LoaderOverlay;