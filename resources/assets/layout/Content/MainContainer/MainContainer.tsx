import useQuery from '@/hooks/useQuery';
import AuthButtons from '@/layout/Topbar/AuthButtons';
import EmailVerifiedModal from '@/modals/EmailVerifiedModal';
import ResetPasswordModal from '@/modals/ResetPasswordModal';
import mapViewerStore from '@/store/MapViewerStore';
import modalsStore from '@/store/ModalsStore';
import userStore, { UserStore } from '@/store/UserStore';
import AgreementView from '@/views/Agreement/AgreementView';
import CreateEventView from '@/views/Events/CreateEventView';
import EventDetailsView from '@/views/Events/EventDetailsView/EventDetailsView';
import EventsView from '@/views/Events/EventsView/EventsView';
import SportFacilityDetails from '@/views/Facilities/FacilityDetailsView/SportFacilityDetails';
import PartnerView from '@/views/User/PartnerView';
import UserView from '@/views/User/UserView';
import axios from 'axios';
import { observer } from 'mobx-react';
import React, { Fragment, useEffect, useState } from 'react';
import { Navigate, Route, Routes, useNavigate } from 'react-router-dom';
import { Scrollbar, ScrollbarProvider } from '../Scrollbar/Scrollbar';
import styles from './MainContainer.scss';

const VerifyEmailRoute: React.FC = observer(() => {
    const query = useQuery();
    const [dataLoaded, setDataLoaded] = useState(null);

    if (!query.has('vmtoken')) {
        return <Navigate to="/" />;
    }

    const fetchData = async () => {
        try {
            await userStore.verifyEmail(query.get('vmtoken'));
            setDataLoaded(true);
        } catch (e) {
            setDataLoaded(false);
        }
    };

    useEffect(() => {
        if (userStore.user) {
            if (userStore.user.isEmailVerified) {
                setDataLoaded(true);
            } else {
                fetchData();
            }
        }
    }, [userStore.user]);

    if (!userStore.user) {
        modalsStore.setIsLoginEnabled(true);

        return (
            <EventsView/>
        );
    }

    if (dataLoaded === null) {
        return null;
    }

    if (!dataLoaded) {
        return <Navigate to="/" />;
    } else {
        return (
            <Fragment>
                <EventsView />
                <EmailVerifiedModal />
            </Fragment>
        );
    }
});

const PasswordResetRoute: React.FC = observer(() => {
    const [dataLoaded, setDataLoaded] = useState(false);
    const query = useQuery();
    const navigate = useNavigate();

    if (!query.has('rptoken') || userStore.user) {
        return <Navigate to="/"/>;
    }

    useEffect(() => {
        axios.post('/api/v1/account/password/valid', {
            token: query.get('rptoken')
        }).then(() => {
            setDataLoaded(true);
        }).catch(() => {
            navigate('/');
        });
    }, []);

    if (!dataLoaded){
        return null;
    }

    return (
        <Fragment>
            <EventsView />
            <ResetPasswordModal />
        </Fragment>
    );
});

const MainContainer: React.FC = () => {
    const [mapLoaded, setMapLoaded] = useState(false);

    useEffect(() => {
        setMapLoaded(!!mapViewerStore.map);
    }, [mapViewerStore.map]);

    return (
        <ScrollbarProvider>
            <main className={styles.mainContainer}>
                {mapLoaded &&
                    <Routes>
                        <Route path="/" element={<EventsView />} />
                        <Route path="/reset-hasla" element={<PasswordResetRoute/>} />
                        <Route path="/potwierdzenie-maila" element={<VerifyEmailRoute/>}/>
                        <Route path="/konto" element={<UserView />} />
                        {/* <Route path="/partnerstwo" element={<PartnerView />} /> */}
                        <Route path="/regulamin" element={<AgreementView />} />
                        {/* <Route path="/obiekty/1" element={<SportFacilityDetails />} /> */}
                        <Route path="/ogloszenia/partner/:alias" element={<EventsView />} />
                        <Route path="/ogloszenia/dodaj" element={<CreateEventView />} />
                        <Route path="/ogloszenia/edytuj/:id" element={<CreateEventView />} />
                        <Route path="/ogloszenia/:id" element={<EventDetailsView />} />
                    </Routes>}
                <AuthButtons />
            </main>
            <Scrollbar />
        </ScrollbarProvider>
    );
};

export default observer(MainContainer);