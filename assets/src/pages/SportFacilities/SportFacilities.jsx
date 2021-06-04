import useResize from '@/src/hooks/useResize';
import React, { useEffect, useRef, useState } from 'react';
import Card from './Card/Card';
import Map from './Map/Map';

// @ts-ignore
import styles from './SportFacilities.scss?module';

const cardsData = [
    {
        imageUrl: 'https://posir.poznan.pl/images/galerie/249/large/Boisko-pikarskie-ze-sztuczn-nawierzchni-i-bieni-owietlone-w-nocy-ujcie-z-lotu-ptaka.jpg',
        facilityName: 'Chwiałka',
        popularity: 70,
        location: 'Poznań, Wilda',
        price: 'Od 30zł'
    },
    {
        imageUrl: 'https://napiachu.pl/images/2/1546/3dd80ff-trening%20meski.jpg',
        facilityName: 'Strzeszynek',
        popularity: 95,
        location: 'Poznań, Jeżyce',
        price: 'Od 25zł'
    },
    {
        imageUrl: 'https://www.tcz.pl/foto/tcz_sport/bd9a5a13ff20525a.jpg',
        facilityName: 'Hala sportowa SP4',
        popularity: 40,
        location: 'Poznań, Jeżyce',
        price: 'Darmowe'
    },
    {
        imageUrl: 'https://posir.poznan.pl/images/galerie/249/large/Boisko-pikarskie-ze-sztuczn-nawierzchni-i-bieni-owietlone-w-nocy-ujcie-z-lotu-ptaka.jpg',
        facilityName: 'Chwiałka',
        popularity: 70,
        location: 'Poznań, Wilda',
        price: 'Od 30zł'
    },
];

const cards = cardsData.map((data, i) => <Card key={i} {...data} />);

const SportFacilities = props => {
    const scrollTrackRef = useRef(null);
    const cardsContainerRef = useRef(null);

    const [thumbPosition, setThumbPosition] = useState(0);
    const [thumbLength, setThumbLength] = useState(0);
    const [gradientOpacity, setGradientOpacity] = useState(0);

    const scrollTrackHeight = useResize(scrollTrackRef).height;
    const cardsContainerHeight = useResize(cardsContainerRef).height;

    const updateScroll = (scrollTop, scrollHeight) => {
        setThumbLength(cardsContainerHeight * scrollTrackHeight / scrollHeight);
        setThumbPosition(scrollTop / scrollHeight * 100);
        setGradientOpacity(scrollTop != 0 ? 1 : 0);
    };

    useEffect(() => {
        updateScroll(cardsContainerRef.current.scrollTop, cardsContainerRef.current.scrollHeight);
    }, []);

    return (
        <div className={styles.container}>
            <div className={styles.cardsContainer}>
                <div className={styles.cardsInnerContainer}>
                    <div className={styles.cardsBackgroundLayer}></div>
                    <div className={styles.filters}>
                    </div>
                    <div className={styles.cardsWrapper}>
                        <div className={styles.cards} ref={cardsContainerRef} onScroll={e => updateScroll(e.target.scrollTop, e.target.scrollHeight)}>
                            <section className={styles.cardsGroup}>
                                {cards}
                            </section>
                            <section className={styles.cardsGroup}>
                                {cards}
                            </section>
                            <section className={styles.cardsGroup}>
                                {cards}
                            </section>
                        </div>
                        <div className={styles.grad} style={{ opacity: gradientOpacity }}></div>
                    </div>
                </div>
                <div className={styles.scroll}>
                    <div className={styles.scrollTrack} ref={scrollTrackRef}>
                        <div className={styles.scrollThumb} style={{ height: thumbLength + 'px', top: thumbPosition + '%' }}></div>
                    </div>
                </div>
            </div>
            <div className={styles.mapContainer}>
                <div className={styles.mapInnerContainer}>
                    <Map />
                </div>
                <footer className={styles.mapFooter}>
                    <ul className={styles.links}>
                        <li>Regulamin</li>
                        <li>Polityka prywatności</li>
                        <li>Pliki cookies</li>
                    </ul>
                    <span className={styles.brandName}>Nasza nazwa © 2021</span>
                </footer>
            </div>
        </div>
    );
};

export default SportFacilities;