import Component, { el } from '@/app/Component';
import styles from './MapViewer.scss';

export class MapViewer extends Component {

    protected render(): JQuery {
        return el(`div.${styles.mapViewer}`);
    }

}