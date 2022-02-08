import { GrayButton, OrangeButton } from '@/components/Form/Button/Button';
import userStore, { UserStore } from '@/store/UserStore';
import EventDetails from '@/views/Events/EventDetails/EventDetails';
import SportFacilityDetails from '@/views/SportFacilities/SportFacilityDetails/SportFacilityDetails';
import UserView from '@/views/User/UserView';
import { observer } from 'mobx-react';
import React, { Fragment, useState } from 'react';
import {
    Link as RouterLink, Route, Routes, Navigate
} from 'react-router-dom';
import { Scrollbar, ScrollbarProvider } from '../Scrollbar/Scrollbar';
import LoginModal from './LoginModal';
import styles from './MainContainer.scss';
import RegisterModal from './RegisterModal';
import RemindPasswordModal from './RemindPasswordModal';
import ResetPasswordModal from './ResetPasswordModal';
import prof from '@/static/images/prof.png';
import CreateEventView from '@/views/Events/CreateEventView';
import Dropdown from '@/components/Form/Dropdown/Dropdown';
import EventsView from '@/views/Events/EventsView/EventsView';
import Link from '@/components/Link/Link';

const MainContainer: React.FC = (props) => {
    const [isLoginModalActive, setIsLoginModalActive] = useState(false);
    const [isRegisterModalActive, setIsRegisterModalActive] = useState(false);
    const [isResetPasswordModalActive, setIsResetPasswordModalActive] = useState(true);
    const [isRemindPasswordModalActive, setIsRemindPasswordModalActive] = useState(false);

    const user = userStore.user;
    let Buttons = null;

    if (user) { //TODO user
        Buttons = (
            <div className={styles.userButton}>
                <img src={prof} alt="" className={styles.avatar} />
                <Dropdown transparent placeholder={user.firstName + ' ' + user.lastName}>
                    <RouterLink to="/konto">Konto</RouterLink>
                    <Link onClick={() => userStore.logout()}>Wyloguj</Link>
                </Dropdown>
            </div>
        );
    } else {
        Buttons = (
            <div className={styles.authButtons}>
                <OrangeButton onClick={() => setIsLoginModalActive(true)}>
                    Zaloguj się
                </OrangeButton>
                <GrayButton onClick={() => setIsRegisterModalActive(true)}>
                    Zarejestruj się
                </GrayButton>
            </div>
        );
    }

    return (
        <ScrollbarProvider>
            <main className={styles.mainContainer}>
                {Buttons}
                <Routes>
                    <Route path="/" element={<EventsView />} />
                    <Route path="/reset-hasla" element={
                        <Fragment>
                            {user ? <Navigate to="/" /> : null}
                            <EventsView />
                            <ResetPasswordModal
                                isOpen={isResetPasswordModalActive}
                                onClose={() => setIsResetPasswordModalActive(false)}
                            />
                        </Fragment>
                    } />
                    <Route path="/konto" element={<UserView/>} />
                    <Route path="/obiekty/1" element={<SportFacilityDetails />} />
                    <Route path="/ogloszenia/1" element={<EventDetails />} />
                    <Route path="/ogloszenia/dodaj" element={<CreateEventView />} />
                    <Route path="/test" element={<EventDetails />} />
                </Routes>
            </main>
            <Scrollbar />

            <LoginModal
                isOpen={isLoginModalActive}
                onClose={() => setIsLoginModalActive(false)}
                onClickRegisterButton={() => {
                    setIsLoginModalActive(false);
                    setIsRegisterModalActive(true);
                }}
                onClickRemindPassword={() => {
                    setIsLoginModalActive(false);
                    setIsRemindPasswordModalActive(true);
                }}
            />
            <RemindPasswordModal
                isOpen={isRemindPasswordModalActive}
                onClose={() => setIsRemindPasswordModalActive(false)}
            />
            <RegisterModal
                isOpen={isRegisterModalActive}
                onClose={() => setIsRegisterModalActive(false)}
                onClickLoginButton={() => {
                    setIsLoginModalActive(true);
                    setIsRegisterModalActive(false);
                }}
            />
        </ScrollbarProvider>
    );
};

export default observer(MainContainer);