import Content from '@/layout/Content/Content';
import Footer from '@/layout/Footer/Footer';
import Topbar from '@/layout/Topbar/Topbar';
import MainContainer from '@/components/MainContainer/MainContainer';
import React, { Fragment } from 'react';
import MapViewer from '@/components/MapViewer/MapViewer';
import EventsView from '@/views/Events/EventsView';
import { BrowserRouter as Router } from 'react-router-dom';

const App: React.FC = () => {
    return (
        <Router>
            <Fragment>
                <Topbar />
                <Content>
                    <MainContainer />
                    <MapViewer />
                </Content>
                <Footer />
            </Fragment>
        </Router>
    );
};

export default App;