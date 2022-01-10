import { BlackButton, GrayButton, OrangeButton } from '@/components/form/Button/Button';
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

const SportFacilityDetails: React.FC = (props) => {
    const containerRef = useRef<HTMLDivElement>(null);

    return (
        <div className={styles.sportFacilityDetails} ref={containerRef}>
            <header className={styles.header}>
                <div className={styles.logo}>
                    <img src="https://posir.poznan.pl/images/layout/logo-posir.svg" />
                </div>
                <div className={styles.details}>
                    <div className={styles.stars}><StarRatings rating={90} /></div>
                    <span className={styles.name}>Chwiałka Lorem Ipsum</span>
                    <span className={styles.location}>Poznań, Dolna Wilda Lorem Ipsum</span>
                </div>
                <div className={styles.buttons}>
                    <OrangeButton onClick={() => scrollToElement(containerRef, 'calendarSection', 20)}>Zapisz się</OrangeButton>
                    <BlackButton onClick={() => scrollToElement(containerRef, 'gallerySection', 20)}>Zobacz galerię</BlackButton>
                </div>
            </header>
            <div className={styles.navButtonsWrapper}>
                <nav className={styles.navButtons}>
                    <GrayButton onClick={() => scrollToElement(containerRef, 'contactSection', 20)}>Kontakt</GrayButton>
                    <GrayButton onClick={() => scrollToElement(containerRef, 'descriptionSection', 20)}>Opis</GrayButton>
                    <GrayButton onClick={() => scrollToElement(containerRef, 'calendarSection', 20)}>Kalendarz</GrayButton>
                    <GrayButton onClick={() => scrollToElement(containerRef, 'equipmentSection', 20)}>Wyposażenie</GrayButton>
                    <GrayButton onClick={() => scrollToElement(containerRef, 'gallerySection', 20)}>Galeria</GrayButton>
                    <GrayButton onClick={() => scrollToElement(containerRef, 'commentsSection', 20)}>Komentarze</GrayButton>
                </nav>
            </div>
            <div className={styles.contactSection} id="contactSection">
                <span className={styles.sectionName}>Kontakt:</span>
                <Icon svg={UserSvg}>Krystian Borowicz</Icon>
                <Icon svg={TelephoneSvg}>123 456 789</Icon>
                <Icon svg={MailSvg}>siatkowka@obiekt.pl</Icon>
                <Icon svg={WebsiteSvg}><Link url="https://posir.poznan.pl">www.siata.org</Link></Icon>
            </div>
            <div className={styles.separator}></div>
            <div className={styles.descriptionSection} id="descriptionSection">
                <span className={styles.sectionName}>Opis obiektu:</span>
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
                <span className={styles.sectionName}>Kalendarz:</span>
                <div className={styles.calendar}>
                    <Calendar></Calendar>
                </div>
            </div>
            <div className={styles.equipmentSection} id="equipmentSection">
                <span className={styles.sectionName}>Wyposażenie:</span>
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
                <span className={styles.sectionName}>Galeria:</span>
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
            <div className={styles.contactSection} id="commentsSection">
                <span className={styles.sectionName}>Komentarze:</span>
            </div>
        </div>
    );
};

export default SportFacilityDetails;