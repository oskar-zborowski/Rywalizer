import useBounds from '@/hooks/useBounds';
import AuthButtons from '@/layout/Topbar/AuthButtons';
import CreateEventView from '@/views/Events/CreateEventView';
import EventDetailsView from '@/views/Events/EventDetailsView/EventDetailsView';
import EventsView from '@/views/Events/EventsView/EventsView';
import SportFacilityDetails from '@/views/Facilities/FacilityDetailsView/SportFacilityDetails';
import UserView from '@/views/User/UserView';
import { observer } from 'mobx-react';
import React, { useRef, useState } from 'react';
import { Route, Routes } from 'react-router-dom';
import { Scrollbar, ScrollbarProvider } from '../Scrollbar/Scrollbar';
import styles from './MainContainer.scss';

const MainContainer: React.FC = () => {
    return (
        <ScrollbarProvider>
            <main className={styles.mainContainer}>
                <Routes>
                    <Route path="/" element={<EventsView />} />
                    {/* <Route path="/reset-hasla" element={
                        <Fragment>
                            {user ? <Navigate to="/" /> : null}
                            <EventsView />
                            <ResetPasswordModal
                                isOpen={isResetPasswordModalActive}
                                onClose={() => setIsResetPasswordModalActive(false)}
                            />
                        </Fragment>
                    } /> */}
                    <Route path="/konto" element={<UserView />} />
                    <Route path="/obiekty/1" element={<SportFacilityDetails />} />
                    <Route path="/ogloszenia/dodaj" element={<CreateEventView />} />
                    <Route path="/ogloszenia/:id" element={<EventDetailsView />} />
                </Routes>
                <AuthButtons/>
            </main>
            <Scrollbar />
        </ScrollbarProvider>
    );
};

export default observer(MainContainer);