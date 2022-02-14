import { GrayButton, OrangeButton } from '@/components/Form/Button/Button';
import Dropdown, { DropdownRow } from '@/components/Form/Dropdown/Dropdown';
import noProfile from '@/static/images/noProfile.png';
import prof from '@/static/images/prof.png';
import modalsStore from '@/store/ModalsStore';
import userStore from '@/store/UserStore';
import { observer } from 'mobx-react';
import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import styles from './AuthButtons.scss';

const AuthButtons: React.FC = observer(() => {
    const user = userStore.user;
    const [dropdownOpen, setDropdownOpen] = useState(false);

    if (user) {
        return (
            <div className={styles.userButton} id="js-auth-buttons">
                <img src={user.avatarUrl ?? noProfile} alt="" className={styles.avatar} />
                <Dropdown
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