import React from 'react';
import styles from './Footer.scss';

const Footer = () => {
    return (
        <div className={styles.footer}>
            <span>
                Nasza nazwa 2021
            </span>
            <span className={styles.links}>
                <span>Polityka prywatności</span>
                <span>Regulamin</span>
            </span>
        </div>
    );
};

export default Footer;