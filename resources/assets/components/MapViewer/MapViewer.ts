import Component, { el } from '@/app/Component';
import styles from './MapViewer.scss';

export class MapViewer extends Component {

    protected render(): JQuery<HTMLElement> {
        return el(`div.${styles.mapViewer}`);
    }

}