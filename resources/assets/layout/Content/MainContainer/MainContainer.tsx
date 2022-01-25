import { GrayButton, OrangeButton } from '@/components/Form/Button/Button';
import userStore, { UserStore } from '@/store/UserStore';
import EventDetails from '@/views/Events/EventDetails/EventDetails';
import EventsView from '@/views/Events/EventsView';
import SportFacilityDetails from '@/views/SportFacilities/SportFacilityDetails/SportFacilityDetails';
import UserView from '@/views/User/UserView';
import { observer } from 'mobx-react';
import React, { Fragment, useState } from 'react';
import {
    Link, Route, Routes, Navigate
} from 'react-router-dom';
import { Scrollbar, ScrollbarProvider } from '../Scrollbar/Scrollbar';
import LoginModal from './LoginModal';
import styles from './MainContainer.scss';
import RegisterModal from './RegisterModal';
import RemindPasswordModal from './RemindPasswordModal';
import ResetPasswordModal from './ResetPasswordModal';
import prof from '@/static/images/prof.png';

const MainContainer: React.FC<{ store: UserStore }> = (props) => {
    const [isLoginModalActive, setIsLoginModalActive] = useState(false);
    const [isRegisterModalActive, setIsRegisterModalActive] = useState(false);
    const [isResetPasswordModalActive, setIsResetPasswordModalActive] = useState(true);
    const [isRemindPasswordModalActive, setIsRemindPasswordModalActive] = useState(false);

    const user = props.store.user;
    let Buttons = null;

    if (user) { //TODO user
        Buttons = (
            <div className={styles.userButton}>
                {/* TODO zamiana na selecta */}
                <img src={prof} alt="" className={styles.avatar}/>
                Krystian Borowicz
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
                    <Route path="/konto" element={<UserView store={props.store} />} />
                    <Route path="/obiekty/1" element={<SportFacilityDetails />} />
                    <Route path="/ogloszenia/1" element={<EventDetails />} />
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