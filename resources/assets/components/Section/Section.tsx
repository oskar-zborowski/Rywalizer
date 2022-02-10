import React from 'react';
import styles from './Section.scss';

export interface ISectionProps {
    title: string;
    titleAlign?: 'left' | 'right';
    titleSize?: number;
}

const Section: React.FC<ISectionProps> = ({ children, title, titleSize, titleAlign = 'left' }) => {
    return (
        <section>
            <h1 
                className={styles.sectionTitle} 
                style={{
                    textAlign: titleAlign,
                    fontSize: titleSize + 'px',
                }}
            >
                {title}
            </h1>
            {children}
        </section>
    );
};

export default Section;