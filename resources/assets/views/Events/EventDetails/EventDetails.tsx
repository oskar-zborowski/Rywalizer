
import StarRatings from '@/components/StarRating/StarRating';
import React from 'react';
import styles from './EventDetails.scss';
import prof from '@/static/images/prof.png';
import Icon from '@/components/Icon/Icon';

import UserSvg from '@/static/icons/my-account.svg';
import ContactSvg from '@/static/icons/food.svg';
import TelephoneSvg from '@/static/icons/telephone.svg';
import MailSvg from '@/static/icons/mail.svg';
import FacebookSvg from '@/static/icons/facebook.svg';
import Link from '@/components/Link/Link';

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
                            <Icon svg={UserSvg}>Krystian Borowicz</Icon>
                            {/* <div className={styles.detailsSeparator}></div> */}
                            <StarRatings rating={67} />
                        </div>

                        <div className={styles.userDetailsRow + ' ' + styles.contact}>
                            <span className={styles.detailsTitle}>Kontakt:</span>
                            <Icon svg={TelephoneSvg}>123 456 789</Icon>
                            {/* <div className={styles.detailsSeparator}></div> */}
                            <Icon svg={MailSvg}>siatkowka@obiekt.pl</Icon>
                            {/* <div className={styles.detailsSeparator}></div> */}
                            <Icon svg={FacebookSvg}><Link href="https://www.facebook.com/groups/356092872309341">fb.jakis.profil.23</Link></Icon>
                        </div>
                    </div>
                </div>
            </header>
            <div className={styles.userDetailsRow + ' ' + styles.contactSM}>
                <span className={styles.detailsTitle}>Kontakt:</span>
                <Icon svg={MailSvg}>siatkowka@obiekt.pl</Icon>
                {/* <div className={styles.detailsSeparator}></div> */}
                <Icon svg={TelephoneSvg}>123 456 789</Icon>
                {/* <div className={styles.detailsSeparator}></div> */}
                <Icon svg={FacebookSvg}>fb.jakis.profil.23</Icon>
            </div>
        </div>
    );
};

export default EventDetails;