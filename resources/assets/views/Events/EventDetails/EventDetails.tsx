
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
import faker from 'faker';

faker.locale = 'pl';

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
                            <h1>Organizator:</h1>
                            <Icon svg={UserSvg}>Krystian Borowicz</Icon>
                            <StarRatings rating={90} />
                        </div>

                        <div className={styles.userDetailsRow + ' ' + styles.contact}>
                            <h1>Kontakt:</h1>
                            <Icon svg={TelephoneSvg}>123 456 789</Icon>
                            <Icon svg={MailSvg}>siatkowka@obiekt.pl</Icon>
                            <Icon svg={FacebookSvg}>
                                <Link href="https://www.facebook.com/groups/356092872309341">
                                    fb.jakis.profil.23
                                </Link>
                            </Icon>
                        </div>
                    </div>
                </div>
            </header>
            <section className={styles.userDetailsRow + ' ' + styles.contactSM}>
                <h1>Kontakt:</h1>
                <Icon svg={MailSvg}>siatkowka@obiekt.pl</Icon>
                <Icon svg={TelephoneSvg}>123 456 789</Icon>
                <Icon svg={FacebookSvg}>fb.jakis.profil.23</Icon>
            </section>
            <div className={styles.separator}></div>
            <section>
                <h1>Lista zapisanych:</h1>
                <div className={styles.participantsListWrapper}>
                    <div className={styles.participantsList}>
                        {[... new Array(11)].map((_v, i) => {
                            return <div className={styles.participantCell} key={i}>
                                {i + 1}. {faker.name.firstName()} {faker.name.lastName()}
                            </div>;
                        })}
                        
                        {/*TODO zamiana na <Link>??*/}

                        <div className={styles.participantCell + ' ' + styles.signUpCell}>
                            12.&nbsp;<span className={styles.signUp}>Zapisz się</span>
                        </div>
                        <div className={styles.participantCell + ' ' + styles.signUpCell}>
                            13.&nbsp;<span className={styles.signUp}>Zapisz się</span>
                        </div>
                        <div className={styles.participantCell + ' ' + styles.signUpCell}>
                            14.&nbsp;<span className={styles.signUp}>Zapisz się</span>
                        </div>
                    </div>
                </div>
            </section>
            <div className={styles.separator}></div>
            <section>
                <h1>Opis:</h1>
                <div className={styles.description}>
                    <b>TODO: jeżeli opis jest pusty to wywalać całą sekcje ??<br/></b>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                    ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                    laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit
                    in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                    occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </div>
            </section>
            <div className={styles.separator}></div>
            <section>
                <h1>Komentarze:</h1>
                <div className={styles.description}>
                    brak komentarzy
                </div>
            </section>
        </div>
    );
};

export default EventDetails;