import React from 'react';

// @ts-ignore
import styles from './Content.scss?module';

const Content = props => {
    return (
        <main className={styles.content}>
            {props.children}
        </main>
    );
};

export default Content;