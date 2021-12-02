import React from 'react';
import { Link } from 'react-router-dom';
import styles from './Topbar.scss';

const Topbar = () => {
    return (
        <div className={styles.topbar}>
            <div className={styles.logo}><Link to="/">LOGO</Link></div>
            <nav className={styles.links}>
                <span>Rezerwacje</span>
                <span>Wydarzenia</span>
                <span>Współpraca</span>
            </nav>
        </div>
    );
};

export default Topbar;