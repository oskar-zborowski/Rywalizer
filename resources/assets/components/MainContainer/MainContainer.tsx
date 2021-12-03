import { GrayButton, OrangeButton } from '@/components/form/Button/Button';
import React from 'react';
import {
    Routes,
    Route,
    Link
} from 'react-router-dom';
import { Scrollbar, ScrollbarProvider } from './Scrollbar/Scrollbar';
import styles from './MainContainer.scss';
import EventsView from '@/views/Events/EventsView';
import UserView from '@/views/User/UserView';
import axios from 'axios';

const MainContainer: React.FC = (props) => {
    const login = async () => {
        await axios.post('/api/login', {
            email: 'oskarzborowski@gmail.com',
            password: 'zaq12wsx'
        });
    };

    return (
        <ScrollbarProvider>
            <main className={styles.mainContainer}>
                <div className={styles.authButtons}>
                    <Link to="/konto"><OrangeButton>Zaloguj się</OrangeButton></Link>
                    <GrayButton>
                        <span onClick={login}>
                            Zarejestruj się
                        </span>
                    </GrayButton>
                </div>
                <Routes>
                    <Route path="/" element={<EventsView />} />
                    <Route path="/konto" element={<UserView />} />
                </Routes>
            </main>
            <Scrollbar />
        </ScrollbarProvider>
    );
};

export default MainContainer;