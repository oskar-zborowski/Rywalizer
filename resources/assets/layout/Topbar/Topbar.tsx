import Dropdown, { DropdownRow } from '@/components/Form/Dropdown/Dropdown';
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
                    <Link to="/"><DropdownRow><span>Lista ogłoszeń</span></DropdownRow></Link>
                    <Link to="/ogloszenia/dodaj"><DropdownRow><span>Dodaj ogłoszenie</span></DropdownRow></Link>
                </Dropdown>
                <Dropdown transparent placeholder="Współpraca">
                    <Link to="/obiekty/1"><DropdownRow><span>TEST</span></DropdownRow></Link>
                </Dropdown>
            </nav>
        </div >
    );
};

export default Topbar;