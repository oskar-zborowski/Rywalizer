import React from 'react';
import styles from './Section.scss';

export interface ISectionProps {
    title: string;
}

const Section: React.FC<ISectionProps> = ({ children, title }) => {
    return (
        <section>
            <h1 className={styles.sectionTitle}>{title}</h1>
            {children}
        </section>
    );
};

export default Section;