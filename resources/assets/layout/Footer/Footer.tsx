import React from 'react';
import { useNavigate } from 'react-router-dom';
import styles from './Footer.scss';

const Footer = () => {
    const navigate = useNavigate();

    return (
        <div className={styles.footer}>
            <span>
                Nasza nazwa 2021
            </span>
            <span className={styles.links}>
                <span 
                    className={styles.link}
                    onClick={() => navigate('/regulamin')}
                >
                    Regulamin i polityka prywatności
                </span>
            </span>
        </div>
    );
};

export default Footer;