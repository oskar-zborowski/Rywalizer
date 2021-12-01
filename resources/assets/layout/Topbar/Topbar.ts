import Component, { el } from '@/app/Component';
import styles from './Topbar.scss';

export default class Topbar extends Component {

    protected render(): JQuery {
        return el(`div.${styles.topbar}`,
            el(`div.${styles.logo}`, 'LOGO'),
            el(`nav.${styles.links}`,
                el('span', 'Rezerwacje'),
                el('span', 'Wydarzenia'),
                el('span', 'Współpraca')
            )
        );
    }

}