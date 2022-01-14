import React from 'react';
import { Link } from 'react-router-dom';
import styles from './Topbar.scss';

const Topbar = () => {
    return (
        <div className={styles.topbar}>
            <div className={styles.logo}><Link to="/">LOGO</Link></div>
            <nav className={styles.links}>
                <span>Obiekty sportowe</span>
                <Link to="/ogloszenia/1"><span>Ogłoszenia</span></Link>
                <Link to="/obiekty/1"><span>Współpraca</span></Link>
            </nav>
        </div>
    );
};

export default Topbar;