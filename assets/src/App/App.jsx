import Map from '../components/Map/Map';
import React, { Fragment } from 'react';
import './App.scss';
import Card from '../components/Card/Card';
import Topnav from '../layout/Topnav/Topnav';

const App = () => {
    return (
        <Fragment>
            <Topnav/>
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', height: '100%' }}>
                <div style={{ display: 'flex', flexDirection: 'row', gap: '20px' }}>
                    <Card
                        imageUrl="https://posir.poznan.pl/images/galerie/249/large/Boisko-pikarskie-ze-sztuczn-nawierzchni-i-bieni-owietlone-w-nocy-ujcie-z-lotu-ptaka.jpg"
                        popularity="70"
                        facilityName="Chwiałka"
                        location="Poznań, Wilda"
                        price="Od 30zł"
                    />
                    <Card imageUrl="https://napiachu.pl/images/2/1546/3dd80ff-trening%20meski.jpg"
                        popularity="95"
                        facilityName="Strzeszynek"
                        location="Poznań, Jeżyce"
                        price="Od 25zł"
                    />
                    <Card imageUrl="https://www.tcz.pl/foto/tcz_sport/bd9a5a13ff20525a.jpg"
                        popularity="40"
                        facilityName="Hala sportowa SP4"
                        location="Poznań, Świerczewo"
                        price="Darmowe"
                    />
                </div>
            </div>
        </Fragment>
    );
};

export default App;