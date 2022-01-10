import { GrayButton, OrangeButton } from '@/components/form/Button/Button';
import userStore, { UserStore } from '@/store/UserStore';
import EventDetails from '@/views/Events/EventDetails/EventDetails';
import EventsView from '@/views/Events/EventsView';
import SportFacilityDetails from '@/views/SportFacilities/SportFacilityDetails/SportFacilityDetails';
import UserView from '@/views/User/UserView';
import { observer } from 'mobx-react';
import React, { useState } from 'react';
import {
    Link, Route, Routes
} from 'react-router-dom';
import { Scrollbar, ScrollbarProvider } from '../Scrollbar/Scrollbar';
import LoginModal from './LoginModal';
import styles from './MainContainer.scss';
import RegisterModal from './RegisterModal';
import ResetPasswordModal from './ResetPasswordModal';

const MainContainer: React.FC<{ store: UserStore }> = (props) => {
    const [isLoginModalActive, setIsLoginModalActive] = useState(false);
    const [isRegisterModalActive, setIsRegisterModalActive] = useState(false);
    const [isResetPasswordModalActive, setIsResetPasswordModalActive] = useState(false);

    const user = props.store.user;

    return (
        <ScrollbarProvider>
            <main className={styles.mainContainer}>
                {!user && <div className={styles.authButtons}>
                    <OrangeButton onClick={() => setIsLoginModalActive(true)}>
                        Zaloguj się
                    </OrangeButton>
                    <GrayButton onClick={() => setIsRegisterModalActive(true)}>
                        Zarejestruj się
                    </GrayButton>
                </div>}
                <Routes>
                    <Route path="/" element={<EventsView />} />
                    <Route path="/konto" element={<UserView store={props.store} />} />
                    <Route path="/obiekty/1" element={<SportFacilityDetails />} />
                    <Route path="/wydarzenia/1" element={<EventDetails />} />
                </Routes>
            </main>
            <Scrollbar />

            <LoginModal
                isOpen={isLoginModalActive}
                onClose={() => setIsLoginModalActive(false)}
            />
            <RegisterModal
                isOpen={isRegisterModalActive}
                onClose={() => setIsRegisterModalActive(false)}
            />
            <ResetPasswordModal
                isOpen={isResetPasswordModalActive}
                onClose={() => setIsResetPasswordModalActive(false)}
            />
        </ScrollbarProvider>
    );
};

export default observer(MainContainer);