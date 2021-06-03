import { ButtonLight } from '@/src/components/Button/Button';
import { Button } from '@/src/components/Button/Button';
import React, { useRef } from 'react';
import styles from './Topnav.scss?module';

const Topnav = props => {
    return (
        <nav className={styles.topnav}>
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
        </nav>
    );
};

export default Topnav;