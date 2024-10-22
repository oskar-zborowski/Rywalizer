import { BlackButton, GrayButton, OrangeButton } from '@/components/Form/Button/Button';
import Icon from '@/components/Icon/Icon';
import StarRatings from '@/components/StarRating/StarRating';
import React, { useRef } from 'react';
import Calendar from './Calendar/Calendar';
import styles from './SportFacilityDetails.scss';

import UserSvg from '@/static/icons/my-account.svg';
import BallSvg from '@/static/icons/ball.svg';
import NetSvg from '@/static/icons/siatka.svg';
import LinesSvg from '@/static/icons/lines.svg';
import TelephoneSvg from '@/static/icons/telephone.svg';
import MailSvg from '@/static/icons/mail.svg';
import WebsiteSvg from '@/static/icons/website.svg';
import GrandstandsSvg from '@/static/icons/grandstands.svg';
import ChangingRoomsSvg from '@/static/icons/changing-rooms.svg';
import LightingSvg from '@/static/icons/Lighting.svg';
import ToiletsSvg from '@/static/icons/toilets.svg';
import WaterSvg from '@/static/icons/water.svg';
import FoodSvg from '@/static/icons/food.svg';
import ShowerSvg from '@/static/icons/shower.svg';

import prof from '@/static/images/prof.png';
import Link from '@/components/Link/Link';
import { scrollToElement } from '@/utils/scrollToElement';
import Comments, { IComment } from '@/components/Comments/Comments';

const comments: IComment[] = [
    {
        username: 'K. Borowicz',
        createdAt: '2 dzień temu',
        comment: 'Reasumując wszystkie aspekty kwintesencji tematu, dochodzę do fundamentalnej konkluzji, warto studiować.\n~ Mariusz pudzianowski',
    },
    {
        username: 'O. Zborowski',
        createdAt: 'Przed chwilą',
        comment: 'To nie dałoby nic, nic by nie dało.\nhttps://www.youtube.com/watch?v=8AwVRlXsxlA&ab_channel=CACACACACA'
    },
    {
        username: 'M. Pudzianowski',
        createdAt: 'Przed chwilą',
        comment: 'Odtworzyłem wczoraj po nocy oryginalną klasyfikację z tych zawodów. Policzyłem wszystko skrupulatnie i zrobiłem double check z regulaminem. Okazało się, że to i tak by nic nie dało.'
    }, 
    {
        username: 'B. Babiaczyk',
        createdAt: 'Przed chwilą',
        comment: 'Skład rzeczy które i tak by nic nie dały:\n- wygrana bo on by musiał być 4 w kulach\n- mała różnica czasów\n- branie kuli od boku'
    },
    {
        username: 'B. Babiaczyk',
        createdAt: '1 dni temu',
        comment: 'Bez ryzyka nie ma gry.',
    }
];

const SportFacilityDetails: React.FC = (props) => {
    const containerRef = useRef<HTMLDivElement>(null);

    return (
        <div className={styles.sportFacilityDetails} ref={containerRef}>
            <header className={styles.header}>
                <div className={styles.logo}>
                    <img src="https://posir.poznan.pl/images/layout/logo-posir.svg" alt="Chwiałka Lorem Ipsum" />
                </div>
                <div className={styles.details}>
                    <div className={styles.stars}><StarRatings rating={90} /></div>
                    <h1 className={styles.name}>Chwiałka Lorem Ipsum</h1>
                    <span className={styles.location}>Poznań, <b>Dolna Wilda Lorem Ipsum</b></span>
                </div>
                <div className={styles.buttons}>
                    <OrangeButton onClick={() => scrollToElement(containerRef, 'calendarSection', 20)}>Zapisz się</OrangeButton>
                    <BlackButton onClick={() => scrollToElement(containerRef, 'gallerySection', 20)}>Zobacz galerię</BlackButton>
                </div>
            </header>
            <div className={styles.navButtonsWrapper}>
                <div className={styles.navButtonsInnerWrapper}>
                    <nav className={styles.navButtons}>
                        <GrayButton onClick={() => scrollToElement(containerRef, 'contactSection', 20)}>Kontakt</GrayButton>
                        <GrayButton onClick={() => scrollToElement(containerRef, 'descriptionSection', 20)}>Opis</GrayButton>
                        <GrayButton onClick={() => scrollToElement(containerRef, 'calendarSection', 20)}>Kalendarz</GrayButton>
                        <GrayButton onClick={() => scrollToElement(containerRef, 'equipmentSection', 20)}>Wyposażenie</GrayButton>
                        <GrayButton onClick={() => scrollToElement(containerRef, 'gallerySection', 20)}>Galeria</GrayButton>
                        <GrayButton onClick={() => scrollToElement(containerRef, 'commentsSection', 20)}>Komentarze</GrayButton>
                    </nav>
                </div>
            </div>
            <div className={styles.contactSection} id="contactSection">
                <h1>Kontakt:</h1>
                <Icon svg={UserSvg}>Krystian Borowicz</Icon>
                <Icon svg={TelephoneSvg}>123 456 789</Icon>
                <Icon svg={MailSvg}>siatkowka@obiekt.pl</Icon>
                <Icon svg={WebsiteSvg}><Link href="https://posir.poznan.pl">www.siata.org</Link></Icon>
            </div>
            <div className={styles.separator}></div>
            <div className={styles.descriptionSection} id="descriptionSection">
                <h1>Opis obiektu:</h1>
                <div className={styles.description}>
                    Na terenie Centrum  Rekreacyjno-Sportowego „Ukiel” zlokalizowano kilkanaście boisk sportowych i całoroczną halę do siatkówki plażowej.
                    Miłośnicy sportów zespołowych mają do swojej dyspozycji kompleks niżej wymienionych boisk:<br /><br />
                    <ul>
                        <li>• 11 boisk do siatkówki plażowej (obiekty przy ul. Kapitańskiej 23)</li>
                        <li>• 2 boiska do siatkówki plazowej (obiekty przy ul. Olimpijskiej 1)</li>
                        <li>• boisko do koszykówki (ul. Kapitańska 23)</li>
                    </ul>
                    <br />
                    Rezerwacji odpłatnych boisk do sportów plażowych znajdujacych się za Hotelem "Omega" dokonywac można drogą mailową: rezerwacja@ukiel.olsztyn.eu. Jednocześnie informujemy, że pozostałe boiska zlokalizowane na terenie Centrum Rekreacyjno-Sportowego "Ukiel" w Olsztynie udostępniane są bez opłat.
                    <div className={styles.seeMoreButton}>
                        <OrangeButton>Zobacz więcej</OrangeButton>
                    </div>
                </div>
            </div>
            <div className={styles.separator}></div>
            <div className={styles.calenadrSection} id="calendarSection">
                <h1>Kalendarz:</h1>
                <div className={styles.calendar}>
                    <Calendar></Calendar>
                </div>
            </div>
            <div className={styles.equipmentSection} id="equipmentSection">
                <h1>Wyposażenie:</h1>
                <div className={styles.equipmentGrid}>
                    <Icon svg={NetSvg} size={25} textPosition="bottom">Siatka</Icon>
                    <Icon svg={BallSvg} size={25} textPosition="bottom">Piłka</Icon>
                    <Icon svg={LinesSvg} size={25} textPosition="bottom">Linie</Icon>
                    <Icon svg={GrandstandsSvg} size={25} textPosition="bottom" className={styles.disabledEquipmentItem}>Trybuny</Icon>
                    <Icon svg={LightingSvg} size={25} textPosition="bottom">Oświetlenie</Icon>
                    <Icon svg={ChangingRoomsSvg} size={25} textPosition="bottom" className={styles.disabledEquipmentItem}>Przebieralnie</Icon>
                    <Icon svg={ToiletsSvg} size={25} textPosition="bottom">Toalety</Icon>
                    <Icon svg={WaterSvg} size={25} textPosition="bottom">Woda</Icon>
                    <Icon svg={FoodSvg} size={25} textPosition="bottom">Jedzenie</Icon>
                    <Icon svg={ShowerSvg} size={25} textPosition="bottom">Przysznic</Icon>
                </div>
            </div>
            <div className={styles.separator}></div>
            <div className={styles.gallerySection} id="gallerySection">
                <h1>Galeria:</h1>
                <div className={styles.gallery}>
                    <div className={styles.image}><img src="https://posir.poznan.pl/images/obiekty/Strzeszynek/K%C4%85pielisko/strzeszynek.jpg" alt="" /></div>
                    <div className={styles.image}><img src="https://upload.wikimedia.org/wikipedia/commons/f/f9/Klub_Sportowy_Wojskowy_GRUNWALD_3_boisko_do_hokeja_na_trawie_F.jpg" alt="" /></div>
                    <div className={styles.image}><img src="https://posir.poznan.pl/images/galerie/171/large/MG4242.jpg" alt="" /></div>
                    <div className={styles.image}><img src="https://posir.poznan.pl/images/obiekty/Gol%C4%99cin/Boiska_pi%C5%82karskie/intro-full.jpg" alt="" /></div>
                    {/* <div className={styles.image}><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a6/Lost_World_of_Tambun.jpg/1200px-Lost_World_of_Tambun.jpg" alt="" /></div>
                    <div className={styles.image}><img src="https://s.redefine.pl/file/o2/redefine/cp/nx/nxsmmwz9h2zrqofb7n54tsiu3f27fep3.jpg" alt="" /></div>
                    <div className={styles.image}><img src="https://ocdn.eu/pulscms-transforms/1/0A6k9kpTURBXy9iMzQxOTkyNDM3YTFlYWJiZWRjN2M2YjZmY2Q4ODBmMi5qcGeTlQMAFc0CZM0BWJMFzQMUzQG8kwmmNDQzNmRhBoGhMAE/piotr-kantor-i-bartosz-losiak-tokio-2020.jpg" alt="" /></div> */}
                </div>
            </div>
            <div className={styles.separator}></div>
            <div id="commentsSection">
                <h1>Komentarze:</h1>
                <Comments comments={comments} />
            </div>
        </div>
    );
};

export default SportFacilityDetails;