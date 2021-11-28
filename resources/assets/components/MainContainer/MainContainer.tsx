import { GrayButton, OrangeButton } from '@/components/form/Button/Button';
import React from 'react';
import { Scrollbar, ScrollbarProvider } from './Scrollbar/Scrollbar';
import styles from './MainContainer.scss';

const MainContainer: React.FC = (props) => {
    return (
        <ScrollbarProvider>
            <main className={styles.mainContainer}>
                <div className={styles.authButtons}>
                    <OrangeButton>Zaloguj się</OrangeButton>
                    <GrayButton>Zarejestruj się</GrayButton>
                </div>
                {props.children}
            </main>
            <Scrollbar />
        </ScrollbarProvider>
    );
};

export default MainContainer;