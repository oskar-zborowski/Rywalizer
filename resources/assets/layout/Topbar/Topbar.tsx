import Dropdown from '@/components/Form/Dropdown/Dropdown';
import React from 'react';
import { Link } from 'react-router-dom';
import styles from './Topbar.scss';

const Topbar = () => {
    return (
        <div className={styles.topbar}>
            <div className={styles.logo}><Link to="/">LOGO</Link></div>
            <nav className={styles.links}>
                <span>Obiekty sportowe</span>
                <Dropdown transparent placeholder="Ogłoszenia">
                    <Link to="/"><span>Lista ogłoszeń</span></Link><br/>
                    <Link to="/ogloszenia/dodaj"><span>Dodaj ogłoszenie</span></Link><br/>
                    <Link to="/ogloszenia/1"><span>TEST</span></Link>
                </Dropdown>
                <Dropdown transparent placeholder="Współpraca">
                    <Link to="/obiekty/1"><span>TEST</span></Link>
                </Dropdown>
            </nav>
        </div>
    );
};

export default Topbar;