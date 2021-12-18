import { GrayButton, OrangeButton } from '@/components/form/Button/Button';
import userStore, { UserStore } from '@/store/UserStore';
import EventDetails from '@/views/Events/EventDetails/EventDetails';
import EventsView from '@/views/Events/EventsView';
import SportFacilityDetails from '@/views/SportFacilities/SportFacilityDetails/SportFacilityDetails';
import UserView from '@/views/User/UserView';
import { observer } from 'mobx-react';
import React from 'react';
import {
    Link, Route, Routes
} from 'react-router-dom';
import { Scrollbar, ScrollbarProvider } from '../Scrollbar/Scrollbar';
import styles from './MainContainer.scss';

const MainContainer: React.FC<{ store: UserStore }> = (props) => {
    const user = props.store.user;

    return (
        <ScrollbarProvider>
            <main className={styles.mainContainer}>
                {!user && <div className={styles.authButtons}>
                    <Link to="/konto"><OrangeButton>Zaloguj się</OrangeButton></Link>
                    <GrayButton>
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
        </ScrollbarProvider>
    );
};

export default observer(MainContainer);