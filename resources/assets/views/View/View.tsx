import LoaderOverlay from '@/components/LoaderOverlay/LoaderOverlay';
import React, { createContext, useContext, useState } from 'react';
import styles from './View.scss';

export interface IViewContext {
    showLoader?: () => void;
    hideLoader?: () => void;
}

const ViewContext = createContext<IViewContext>({});

export interface IViewProps {
    withBackground?: boolean;
    title?: string;
    isLoaderVisible?: boolean;
    headerContent?: (title: string) => React.ReactNode; //TODO zmiana nazwy
}

const View: React.FC<IViewProps> = ({ withBackground, children, title, isLoaderVisible, headerContent: header }) => {
    const [isLoaderVisibleInner, setIsLoaderVisibleInner] = useState(false);

    const showLoader = () => setIsLoaderVisibleInner(true);
    const hideLoader = () => setIsLoaderVisibleInner(false);

    if (!header) {
        header = (title) => (
            <h1>{title}</h1>
        );
    }

    const content = !withBackground ? children : (
        <div className={styles.grayPane}>
            {title && <header className={styles.header}>
                {header(title)}
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