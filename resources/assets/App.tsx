import Content from '@/layout/Content/Content';
import Footer from '@/layout/Footer/Footer';
import Topbar from '@/layout/Topbar/Topbar';
import MainContainer from '@/components/MainContainer/MainContainer';
import React, { Fragment } from 'react';
import MapViewer from '@/components/MapViewer/MapViewer';

const App: React.FC = () => {
    return (
        <Fragment>
            <Topbar />
            <Content>
                <MainContainer />
                <MapViewer/>
            </Content>
            <Footer />
        </Fragment>
    );
};

export default App;