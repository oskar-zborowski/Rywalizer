import { BlackButton } from '@/components/form/Button/Button';
import { UserStore } from '@/store/UserStore';
import { observer } from 'mobx-react';
import React from 'react';
import styles from './UserView.scss';
import prof from '@/static/images/prof.png';

const UserView: React.FC<{ store: UserStore }> = (props) => {
    const data = props.store.user;

    return (
        <div className={styles.userView}>
            <header className={styles.header}>
                <span className={styles.title}>Moje konto</span>
                <BlackButton>Zostań partnerem</BlackButton>
            </header>
            <div className={styles.userDataWrapper}>
                <div className={styles.leftColumn}>
                    <img src={prof} alt="" className={styles.image} />
                </div>
                <div className={styles.rightColumn}>
                    {/* DANE PODSTAWOWE */}
                    <div className={styles.groupHeader}>Dane podstawowe</div>
                    <div className={styles.fieldName}>Imię i Nazwisko:</div>
                    <div className={styles.fieldValue}>{data?.firstName + ' ' + data?.lastName}</div>

                    <div className={styles.fieldName}>Płeć:</div>
                    <div className={styles.fieldValue}>{data?.gender.name}</div>

                    <div className={styles.fieldName}>Urodziny:</div>
                    <div className={styles.fieldValue}>{data?.birthDate}</div>

                    <div className={styles.fieldName}>Lokalizacja:</div>
                    <div className={styles.fieldValue}>52.3567, 18.2341</div>

                    {/* KONTAKT */}
                    <div className={styles.groupHeader}>Kontakt</div>
                    <div className={styles.fieldName}>E-mail:</div>
                    <div className={styles.fieldValue}>{data?.email}</div>

                    <div className={styles.fieldName}>Telefon:</div>
                    <div className={styles.fieldValue}>{data?.phoneNumber}</div>

                    {/* SOCIAL MEDIA */}
                    <div className={styles.groupHeader}>Social media</div>
                    <div className={styles.fieldName}>Facebook:</div>
                    <div className={styles.fieldValue}>fb.jakis.user.23</div>

                    <div className={styles.fieldName}>Instagram:</div>
                    <div className={styles.fieldValue}>@ig.siata.123</div>

                    {/* KONTO */}
                    <div className={styles.groupHeader}>Konto</div>
                    <div className={styles.fieldName}>Hasło:</div>
                    <div className={styles.fieldValue}></div>

                    <div className={styles.fieldName}>Usuń konto:</div>
                    <div className={styles.fieldValue}></div>
                </div>
            </div>
        </div>
    );
};

export default observer(UserView);