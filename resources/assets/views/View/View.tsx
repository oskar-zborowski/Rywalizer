import LoaderOverlay from '@/components/LoaderOverlay/LoaderOverlay';
import React, { createContext, useContext, useState } from 'react';
import styles from './View.scss';

export interface IViewContext {
    showLoader?: () => void;
    hideLoader?: () => void;
}

export interface IViewProps {
    withBackground?: boolean;
    title?: string;
    isLoaderVisible?: boolean;
}

const ViewContext = createContext<IViewContext>({});

const View: React.FC<IViewProps> = ({ withBackground, children, title, isLoaderVisible }) => {
    const [isLoaderVisibleInner, setIsLoaderVisibleInner] = useState(false);

    const showLoader = () => setIsLoaderVisibleInner(true);
    const hideLoader = () => setIsLoaderVisibleInner(false);

    const content = !withBackground ? children : (
        <div className={styles.grayPane}>
            {title && <header className={styles.header}>
                <h1>{title}</h1>
            </header>}
            {children}
        </div>
    );

    return (
        <ViewContext.Provider value={{ showLoader, hideLoader }}>
            <div className={styles.view}>
                {content}
            </div>
            <LoaderOverlay isVisible={isLoaderVisibleInner || isLoaderVisible} />
        </ViewContext.Provider>
    );
};

export default View;

export const useView = () => {
    return useContext(ViewContext);
};