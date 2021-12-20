import { BlackButton, GrayButton, OrangeButton } from '@/components/form/Button/Button';
import Icon from '@/components/Icon/Icon';
import StarRatings from '@/components/StarRating/StarRating';
import React from 'react';
import Calendar from './Calendar/Calendar';
import styles from './SportFacilityDetails.scss';

import UserSvg from '@/static/icons/ball.svg';
import ContactSvg from '@/static/icons/food.svg';

const SportFacilityDetails: React.FC = (props) => {
    return (
        <div className={styles.sportFacilityDetails}>
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
                    <OrangeButton>Zapisz się</OrangeButton>
                    <BlackButton>Zobacz galerię</BlackButton>
                </div>
            </header>
            <div className={styles.navButtonsWrapper}>
                <nav className={styles.navButtons}>
                    <GrayButton>Kontakt</GrayButton>
                    <GrayButton>Opis</GrayButton>
                    <GrayButton>Kalendarz</GrayButton>
                    <GrayButton>Wyposażenie</GrayButton>
                    <GrayButton>Galeria</GrayButton>
                    <GrayButton>Komentarze</GrayButton>
                </nav>
            </div>
            <div className={styles.contactSection}>
                <span className={styles.sectionName}>Kontakt:</span>
                <Icon icon={UserSvg}>Krystian Borowicz</Icon>
                <Icon icon={ContactSvg}>123 456 789</Icon>
                <Icon icon={UserSvg}>siatkowka@obiekt.pl</Icon>
                <Icon icon={ContactSvg}>www.siata.org</Icon>
            </div>
            <div className={styles.separator}></div>
            <div className={styles.descriptionSection}>
                <span className={styles.sectionName}>Opis obiektu:</span>
                <Calendar></Calendar>
            </div>
            <div className={styles.contactSection}>
                <span className={styles.sectionName}>Wyposażenie:</span>
            </div>
            <div className={styles.separator}></div>
            <div className={styles.contactSection}>
                <span className={styles.sectionName}>Galeria:</span>
            </div>
            <div className={styles.separator}></div>
            <div className={styles.contactSection}>
                <span className={styles.sectionName}>Komentarze:</span>
            </div>
        </div>
    );
};

export default SportFacilityDetails;