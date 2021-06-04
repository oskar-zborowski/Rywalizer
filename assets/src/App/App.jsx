import './App.scss';
import { Route, BrowserRouter as Router, Switch } from 'react-router-dom';
import React from 'react';
import Topnav from '../layout/Topnav/Topnav';
import Content from '../layout/Content/Content';
import SportFacilities from '../pages/SportFacilities/SportFacilities';

const App = () => {
    return (
        <Router>
            <Topnav />
            <Content>
                <Switch>
                    <Route path="" component={SportFacilities} />
                    <Route path="" component={null} />
                    <Route path="" component={null} />
                    <Route render={() => <div>Not Found</div>} />
                </Switch>
            </Content>
        </Router>
    );
};

export default App;