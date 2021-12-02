import { BlackButton } from '@/components/form/Button/Button';
import useScrollbar from '@/components/MainContainer/Scrollbar/Scrollbar';
import React from 'react';
import styles from './UserView.scss';
import prof from '@/images/prof.png';

const UserView: React.FC = () => {
    const { containerRef } = useScrollbar();

    return (
        <div className={styles.userView} ref={containerRef}>
            <header className={styles.header}>
                <span className={styles.title}>Moje konto</span>
                <BlackButton>Zostań partnerem</BlackButton>
            </header>
            <div className={styles.userDataWrapper}>
                <div className={styles.leftColumn}>
                    <img src={prof} alt="" className={styles.image} />
                </div>
                <div className={styles.rightColumn}>
                    <div className={styles.fieldName}>Imię i Nazwisko:</div>
                    <div className={styles.fieldValue}>Maciej Badura</div>

                    <div className={styles.fieldName}>Płeć:</div>
                    <div className={styles.fieldValue}>Mężczyzna</div>

                    <div className={styles.fieldName}>Urodziny:</div>
                    <div className={styles.fieldValue}>21 Wrzesień 1991</div>

                    <div className={styles.fieldName + ' ' + styles.withMargin}>E-mail:</div>
                    <div className={styles.fieldValue + ' ' + styles.withMargin}>maciej.bad@gmail.com</div>

                    <div className={styles.fieldName}>Telefon:</div>
                    <div className={styles.fieldValue}>+48 656 727 313</div>

                    <div className={styles.fieldName + ' ' + styles.withMargin}>Hasło:</div>
                    <div className={styles.fieldValue + ' ' + styles.withMargin}></div>

                    <div className={styles.fieldName}>Usuń konto:</div>
                    <div className={styles.fieldValue}></div>
                </div>
            </div>
        </div>
    );
};

export default UserView;