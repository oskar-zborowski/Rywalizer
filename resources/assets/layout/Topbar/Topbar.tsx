import Dropdown, { DropdownRow } from '@/components/Form/Dropdown/Dropdown';
import modalsStore from '@/store/ModalsStore';
import userStore from '@/store/UserStore';
import { observer } from 'mobx-react';
import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import styles from './Topbar.scss';

const Topbar = () => {
    const [eventsDropdownOpen, setEventsDropdownOpen] = useState(false);
    const [facilitiesDropdownOpen, setFacilitiesDropdownOpen] = useState(false);
    const user = userStore.user;

    return (
        <div className={styles.topbar}>
            <div className={styles.logo}><Link to="/">LOGO</Link></div>
            <nav className={styles.links}>
                <Dropdown
                    transparent
                    placeholder="Ogłoszenia"
                    align="right"
                    horizontalOffset={-10}
                    isOpen={eventsDropdownOpen}
                    handleIsOpenChange={(isOpen) => setEventsDropdownOpen(isOpen)}
                >
                    <Link to="/"><DropdownRow><span>Lista ogłoszeń</span></DropdownRow></Link>
                    {user ? (
                        <Link to="/ogloszenia/dodaj"><DropdownRow><span>Dodaj ogłoszenie</span></DropdownRow></Link>
                    ) : (
                        <DropdownRow onClick={() => modalsStore.setIsLoginEnabled(true)}><span>Dodaj ogłoszenie</span></DropdownRow>
                    )}
                </Dropdown>
                {/* <Dropdown
                    transparent
                    placeholder="Obiekty sportowe"
                    isOpen={facilitiesDropdownOpen}
                    handleIsOpenChange={(isOpen) => setFacilitiesDropdownOpen(isOpen)}
                >
                    <Link to="/obiekty/1"><DropdownRow><span>Obiekt 1</span></DropdownRow></Link>
                </Dropdown> */}
            </nav>
        </div >
    );
};

export default observer(Topbar);