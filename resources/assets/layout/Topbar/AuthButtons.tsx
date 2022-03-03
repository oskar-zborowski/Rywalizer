import { GrayButton, OrangeButton } from '@/components/Form/Button/Button';
import Dropdown, { DropdownRow, DropdownSeparator } from '@/components/Form/Dropdown/Dropdown';
import noProfile from '@/static/images/noProfile.png';
import modalsStore from '@/store/ModalsStore';
import userStore from '@/store/UserStore';
import { observer } from 'mobx-react';
import React, { useRef, useState } from 'react';
import { Link } from 'react-router-dom';
import styles from './AuthButtons.scss';
import { FiMenu } from 'react-icons/fi';

const MenuButton: React.FC = () => {
    return (
        <div className={styles.menuButton}>
            <FiMenu fill='white'/>
        </div> 
    );
};

const AuthButtons: React.FC = observer(() => {
    const user = userStore.user;
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const [eventsDropdownOpen, setEventsDropdownOpen] = useState(false);
    const [menuDropdownOpen, setMenuDropdownOpen] = useState(false);

    if (user) {
        return (
            <div className={styles.userButton} id="js-auth-buttons">
                <Dropdown
                    beforeBar={<img src={user.avatarUrl ?? noProfile} alt="" className={styles.avatar} />}
                    className={styles.userDropdown}
                    transparent
                    placeholder={user.firstName + ' ' + user.lastName}
                    isOpen={dropdownOpen}
                    handleIsOpenChange={(isOpen) => setDropdownOpen(isOpen)}
                >
                    <Link to="/konto"><DropdownRow>Konto</DropdownRow></Link>
                    {user.isPartner ? (
                        <Link to="/partnerstwo"><DropdownRow>Partnerstwo</DropdownRow></Link>
                    ) : (
                        <DropdownRow onClick={() => modalsStore.setIsPartnerModalEnabled(true)}>Partnerstwo</DropdownRow>
                    )}
                    <DropdownRow onClick={() => userStore.logout()}>Wyloguj</DropdownRow>
                </Dropdown>
                <Dropdown
                    className={styles.eventsDropdown}
                    transparent
                    placeholder="Ogłoszenia"
                    align="right"
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
                <Dropdown
                    className={styles.menuDropdown}
                    transparent
                    minWidth={180}
                    align="right"
                    horizontalOffset={-7.5}
                    trigger={<MenuButton/>}
                    isOpen={menuDropdownOpen}
                    handleIsOpenChange={(isOpen) => setMenuDropdownOpen(isOpen)}
                >
                    <Link to="/konto"><DropdownRow>Konto</DropdownRow></Link>
                    {user.isPartner ? (
                        <Link to="/partnerstwo"><DropdownRow>Partnerstwo</DropdownRow></Link>
                    ) : (
                        <DropdownRow onClick={() => modalsStore.setIsPartnerModalEnabled(true)}>Partnerstwo</DropdownRow>
                    )}
                    <DropdownRow onClick={() => userStore.logout()}>Wyloguj</DropdownRow>
                    <DropdownSeparator/>
                    <Link to="/"><DropdownRow><span>Lista ogłoszeń</span></DropdownRow></Link>
                    {user ? (
                        <Link to="/ogloszenia/dodaj"><DropdownRow><span>Dodaj ogłoszenie</span></DropdownRow></Link>
                    ) : (
                        <DropdownRow onClick={() => modalsStore.setIsLoginEnabled(true)}><span>Dodaj ogłoszenie</span></DropdownRow>
                    )}
                </Dropdown>
            </div>
        );
    } else {
        return (
            <div className={styles.authButtons} id="js-auth-buttons">
                <OrangeButton onClick={() => modalsStore.setIsLoginEnabled(true)}>
                    Zaloguj się
                </OrangeButton>
                <GrayButton onClick={() => modalsStore.setIsRegisterEnabled(true)}>
                    Zarejestruj się
                </GrayButton>
            </div>
        );
    }
});

export default AuthButtons;