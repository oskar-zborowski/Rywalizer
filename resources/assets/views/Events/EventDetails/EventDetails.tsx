
import StarRatings from '@/components/StarRating/StarRating';
import React from 'react';
import styles from './EventDetails.scss';
import prof from '@/static/images/prof.png';
import Icon from '@/components/Icon/Icon';

import UserSvg from '@/static/icons/ball.svg';
import ContactSvg from '@/static/icons/food.svg';

const EventDetails: React.FC = (props) => {
    return (
        <div className={styles.eventDetails}>
            <header className={styles.header}>
                <img className={styles.backgroundImage} src="https://static01.nyt.com/images/2021/08/03/sports/03olympics-basketball1/03olympics-basketball1-videoSixteenByNineJumbo1600.jpg" alt="" />
                <div className={styles.gradientOverlay}></div>
                <div className={styles.userData}>
                    <img className={styles.userImage} src={prof} alt="" />
                    <div className={styles.userDetails}>
                        <div className={styles.userDetailsRow}>
                            <span className={styles.detailsTitle}>Organizator:</span>
                            <Icon icon={ContactSvg}>Krystian Borowicz</Icon>
                            <div className={styles.detailsSeparator}></div>
                            <StarRatings rating={96} />
                        </div>

                        <div className={styles.userDetailsRow}>
                            <span className={styles.detailsTitle}>Kontakt:</span>
                            <Icon icon={UserSvg}>123 456 789</Icon>
                            <div className={styles.detailsSeparator}></div>
                            <Icon icon={ContactSvg}>siatkowka@obiekt.pl</Icon>
                            <div className={styles.detailsSeparator}></div>
                            <Icon icon={UserSvg}>fb.jakis.profil.23</Icon>
                        </div>
                    </div>
                </div>
            </header>
        </div>
    );
};

export default EventDetails;