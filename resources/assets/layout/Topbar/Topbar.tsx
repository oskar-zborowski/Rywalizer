import React from 'react';
import styles from './Topbar.scss';

const Topbar = () => {
    return (
        <div className={styles.topbar}>
            <div className={styles.logo}>LOGO</div>
            <nav className={styles.links}>
                <span>Rezerwacje</span>
                <span>Wydarzenia</span>
                <span>Współpraca</span>
            </nav>
        </div>
    );
};

export default Topbar;