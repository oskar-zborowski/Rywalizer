import { BlackButton, OrangeButton } from '@/components/form/Button/Button';
import StarRatings from '@/components/StarRating/StarRating';
import React from 'react';
import styles from './SportFacilityDetails.scss';

const SportFacilityDetails: React.FC = (props) => {
    return (
        <div className={styles.sportFacilityDetails}>
            <header className={styles.header}>
                <div className={styles.logo}>
                    <img src="https://posir.poznan.pl/images/layout/logo-posir.svg" />
                </div>
                <div className={styles.details}>
                    <div className={styles.stars}><StarRatings rating={20} /></div>
                    <div className={styles.name}>Chwiałka Lorem Ipsum</div>
                    <div className={styles.location}>Poznań, Dolna Wilda Lorem Ipsum</div>
                </div>
                <div className={styles.buttons}>
                    <OrangeButton>Zapisz się w kalendarzu</OrangeButton>
                    <BlackButton>Zobacz galerię zdjęć</BlackButton>
                </div>
            </header>
            <div className={styles.contactSection}>
                <span className={styles.sectionName}>Kontakt:</span>
            </div>
        </div>
    );
};

export default SportFacilityDetails;