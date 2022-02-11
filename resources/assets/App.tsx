import Content from '@/layout/Content/Content';
import MapViewer from '@/layout/Content/MapViewer/MapViewer';
import Footer from '@/layout/Footer/Footer';
import Topbar from '@/layout/Topbar/Topbar';
import { observer } from 'mobx-react';
import React, { Fragment, useEffect, useState } from 'react';
import { BrowserRouter as Router } from 'react-router-dom';
import MainContainer from './layout/Content/MainContainer/MainContainer';
import Modals from './modals/Modals';
import appStore from './store/AppStore';
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