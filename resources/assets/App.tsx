import Content from '@/layout/Content/Content';
import MapViewer from '@/layout/Content/MapViewer/MapViewer';
import Footer from '@/layout/Footer/Footer';
import Topbar from '@/layout/Topbar/Topbar';
import chroma from 'chroma-js';
import React, { Fragment, useEffect, useState } from 'react';
import { BrowserRouter as Router } from 'react-router-dom';
import MainContainer from './layout/Content/MainContainer/MainContainer';
import Modals from './modals/Modals';
import appStore from './store/AppStore';
import mapViewerStore, { IEventPin } from './store/MapViewerStore';
import userStore from './store/UserStore';

const App: React.FC = () => {
    const [dataLoaded, setDataLoaded] = useState(false);

    const fetchData = async () => {
        try {
            await userStore.getUser();
            await appStore.fetchData();
        } catch (_e) {
            console.log(_e);
        } finally {
            setDataLoaded(true);
        }
    };

    useEffect(() => {
        fetchData();

        // setTimeout(() => {
        //     const pins: IEventPin[] = [];

        //     for (let i = 0; i < 100000; i++) {
        //         const lat = 52 + (Math.random() - 0.5) * 4;
        //         const lng = 19 + (Math.random() - 0.5) * 6;

        //         pins.push({
        //             lat, lng, id: 0, color: chroma.random()
        //         });
        //     }

        //     mapViewerStore.setEventPins(pins);
        // }, 4000);
    }, []);

    return (
        <Router>
            <Fragment>
                <Topbar />
                <Content>
                    {dataLoaded && (
                        <Fragment>
                            <MainContainer />
                            <MapViewer />
                        </Fragment>
                    )}
                </Content>
                <Footer />
            </Fragment>
            <Modals />
        </Router>
    );
};

export default App;