import { Button, ButtonLight } from '@/src/components/Button/Button';
import React from 'react';

// @ts-ignore
import styles from './Topnav.scss?module';

const Topnav = props => {
    return (
        <header className={styles.topnav}>
            <div className={styles.logo}>NASZA NAZWA</div>
            <div className={styles.linksWrapper}>
                <ul className={styles.links}>
                    <li>Rezerwacje</li>
                    <li>Wydarzenia</li>
                    <li>Współpraca</li>
                </ul>
                <Button>Zaloguj się</Button>
                <ButtonLight>Rejestracja</ButtonLight>
            </div>
        </header>
    );
};

export default Topnav;