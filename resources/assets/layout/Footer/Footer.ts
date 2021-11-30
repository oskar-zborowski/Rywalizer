import Component, { el } from '@/app/Component';
import styles from './Footer.scss';

export default class Footer extends Component {

    protected render(): JQuery {
        return el(`div.${styles.footer}`,
            el('span', 'Nasza nazwa 2021'),
            el(`span.${styles.links}`,
                el('span', 'Polityka prywatności'),
                el('span', 'Regulamin')
            )
        );
    }

}