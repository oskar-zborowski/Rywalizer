import Component, { el } from '../../components/Component';
import styles from './Footer.scss';

export default class Footer extends Component {

    public constructor() {
        super();

        this._vdom = el(`div.${styles.footer}`, [
            el('span', 'Nasza nazwa 2021'),
            el(`span.${styles.links}`, [
                el('span', 'Polityka prywatności'),
                el('span', 'Regulamin')
            ])
        ]);
    }

}