
import StarRatings from '@/components/StarRating/StarRating';
import React from 'react';
import styles from './EventDetails.scss';
import prof from '@/static/images/prof.png';

const EventDetails: React.FC = (props) => {
    return (
        <div className={styles.eventDetails}>
            <header className={styles.header}>
                <img className={styles.backgroundImage} src="https://static01.nyt.com/images/2021/08/03/sports/03olympics-basketball1/03olympics-basketball1-videoSixteenByNineJumbo1600.jpg" alt="" />
                <div className={styles.gradientOverlay}></div>
                <div className={styles.userData}>
                    <img className={styles.userImage} src={prof} alt="" />
                    <div className={styles.userDetails}>
                        <span className={styles.detailsTitle}>Organizator:</span>
                        <div className={styles.userDetailsRow}>
                            <span>Krystian Borowicz</span>
                            <div className={styles.detailsSeparator}></div>
                            <StarRatings rating={96} />
                        </div>

                        <span className={styles.detailsTitle}>Kontakt:</span>
                        <div className={styles.userDetailsRow}>
                            <span>123 456 789</span>
                            <div className={styles.detailsSeparator}></div>
                            <span>siatkowka@obiekt.pl</span>
                            <div className={styles.detailsSeparator}></div>
                            <span>fb.jakis.profil.23</span>
                        </div>
                    </div>
                </div>
            </header>
        </div>
    );
};

export default EventDetails;