import { GrayButton, OrangeButton } from '@/components/form/Button/Button';
import React from 'react';
import styles from './Topbar.scss';

const Topbar = () => {
    return (
        <div className={styles.topbar}>
            <div className={styles.brandbar}>
                <div className={styles.logo}>LOGO</div>
                <div className={styles.authButtons}>
                    <OrangeButton>Zaloguj się</OrangeButton>
                    <GrayButton>Zarejestruj się</GrayButton>
                </div>
            </div>
            <nav className={styles.links}>
                <span>Rezerwacje</span>
                <span>Wydarzenia</span>
                <span>Współpraca</span>
            </nav>
        </div>
    );
};

export default Topbar;