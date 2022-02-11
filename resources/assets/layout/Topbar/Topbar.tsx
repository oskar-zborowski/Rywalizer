import Dropdown, { DropdownRow } from '@/components/Form/Dropdown/Dropdown';
import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import styles from './Topbar.scss';

const Topbar = () => {
    const [eventsDropdownOpen, setEventsDropdownOpen] = useState(false);
    const [facilitiesDropdownOpen, setFacilitiesDropdownOpen] = useState(false);

    return (
        <div className={styles.topbar}>
            <div className={styles.logo}><Link to="/">LOGO</Link></div>
            <nav className={styles.links}>
                <span>Obiekty sportowe</span>
                <Dropdown
                    transparent
                    placeholder="Ogłoszenia"
                    isOpen={eventsDropdownOpen}
                    handleIsOpenChange={(isOpen) => setEventsDropdownOpen(isOpen)}
                >
                    <Link to="/"><DropdownRow><span>Lista ogłoszeń</span></DropdownRow></Link>
                    <Link to="/ogloszenia/dodaj"><DropdownRow><span>Dodaj ogłoszenie</span></DropdownRow></Link>
                </Dropdown>
                <Dropdown
                    transparent
                    placeholder="Współpraca"
                    isOpen={facilitiesDropdownOpen}
                    handleIsOpenChange={(isOpen) => setFacilitiesDropdownOpen(isOpen)}
                >
                    <Link to="/obiekty/1"><DropdownRow><span>TEST</span></DropdownRow></Link>
                </Dropdown>
            </nav>
        </div >
    );
};

export default Topbar;