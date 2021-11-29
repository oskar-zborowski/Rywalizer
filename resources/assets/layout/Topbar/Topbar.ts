import Component, { el } from '@/app/Component';
import styles from './Topbar.scss';

export default class Topbar extends Component<HTMLDivElement> {

    protected render(): JQuery<HTMLDivElement> {
        return el(`div.${styles.topbar}`,
            el(`div.${styles.logo}`, 'LOGO'),
            el(`nav.${styles.links}`,
                el('span', 'Rezerwacje'),
                el('span', 'Rezerwacje'),
                el('span', 'Rezerwacje')
            )
        );
    }

}